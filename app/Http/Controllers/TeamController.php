<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteTeamMemberRequest;
use App\Http\Requests\UpdateTeamMemberRequest;
use App\Mail\TeamInvitationMail;
use App\Mail\TeamRemovalMail;
use App\Models\AuditLog;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    private const INVITATION_EXPIRY_HOURS = 24;

    public function index()
    {
        $this->authorizeOwner();

        $owner = Auth::user();
        $seatsUsed = User::where('law_firm_id', $owner->law_firm_id)->count();
        $seatLimit = $this->resolveSeatLimit($owner->law_firm_id);
        $canAddMembers = $seatLimit === null || $seatsUsed < $seatLimit;

        $users = User::where('law_firm_id', $owner->law_firm_id)
            ->orderByDesc('role')
            ->orderBy('name')
            ->get();

        return view('team.index', compact('users', 'seatsUsed', 'seatLimit', 'canAddMembers'));
    }

    public function create()
    {
        $this->authorizeOwner();

        if (!$this->hasAvailableSeat(Auth::user()->law_firm_id)) {
            return redirect()->route('team.index')
                ->with('error', 'لا يمكن إضافة عضو جديد: تم الوصول إلى الحد الأقصى للمستخدمين حسب خطة الاشتراك.');
        }

        return view('team.create');
    }

    public function store(InviteTeamMemberRequest $request)
    {
        $this->authorizeOwner();

        if (!$this->hasAvailableSeat(Auth::user()->law_firm_id)) {
            return redirect()->route('team.index')
                ->with('error', 'لا يمكن إضافة عضو جديد: تم الوصول إلى الحد الأقصى للمستخدمين حسب خطة الاشتراك.');
        }

        $owner = Auth::user();
        $firmName = $owner->lawFirm?->name ?? config('app.name');
        $member = null;

        DB::transaction(function () use ($request, $owner, $firmName, &$member) {
            $member = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(16)),
                'law_firm_id' => $owner->law_firm_id,
                'role' => $request->role,
                'phone' => $request->phone,
                'specialty' => $request->specialty,
                'invited_by_user_id' => $owner->id,
                'invited_at' => now(),
                'invitation_expires_at' => now()->addHours(self::INVITATION_EXPIRY_HOURS),
            ]);

            $this->sendInvitationEmail($member, $firmName);

            AuditLog::record(
                actor: $owner,
                action: 'team_member_invited',
                modelType: User::class,
                modelId: $member->id,
                lawFirmId: $owner->law_firm_id,
                oldValues: null,
                newValues: [
                    'name' => $member->name,
                    'email' => $member->email,
                    'role' => $member->role,
                    'invited_at' => optional($member->invited_at)?->toDateTimeString(),
                    'invitation_expires_at' => optional($member->invitation_expires_at)?->toDateTimeString(),
                ],
            );
        });

        return redirect()->route('team.index')
            ->with('success', 'تمت إضافة عضو الفريق وإرسال دعوة التفعيل لمدة 24 ساعة بنجاح');
    }

    public function resendInvitation(User $team)
    {
        $this->authorizeOwner();
        $this->authorizeSameFirm($team);

        if ($team->activated_at !== null) {
            return back()->with('error', 'هذا العضو فعّل حسابه بالفعل، ولا يمكن إعادة إرسال دعوة التفعيل له.');
        }

        $owner = Auth::user();
        $firmName = $owner->lawFirm?->name ?? config('app.name');
        $oldValues = [
            'invited_at' => optional($team->invited_at)?->toDateTimeString(),
            'invitation_expires_at' => optional($team->invitation_expires_at)?->toDateTimeString(),
        ];

        DB::transaction(function () use ($team, $owner, $firmName, $oldValues) {
            DB::table('password_reset_tokens')->where('email', $team->email)->delete();

            $team->forceFill([
                'invited_by_user_id' => $owner->id,
                'invited_at' => now(),
                'invitation_expires_at' => now()->addHours(self::INVITATION_EXPIRY_HOURS),
            ])->save();

            $this->sendInvitationEmail($team, $firmName);

            AuditLog::record(
                actor: $owner,
                action: 'team_invitation_resent',
                modelType: User::class,
                modelId: $team->id,
                lawFirmId: $team->law_firm_id,
                oldValues: $oldValues,
                newValues: [
                    'invited_at' => optional($team->invited_at)?->toDateTimeString(),
                    'invitation_expires_at' => optional($team->invitation_expires_at)?->toDateTimeString(),
                ],
            );
        });

        return back()->with('success', 'تمت إعادة إرسال دعوة التفعيل بنجاح لمدة 24 ساعة إضافية.');
    }

    public function show(User $team)
    {
        $this->authorizeOwner();
        $this->authorizeSameFirm($team);

        return redirect()->route('team.edit', $team);
    }

    public function edit(User $team)
    {
        $this->authorizeOwner();
        $this->authorizeSameFirm($team);

        return view('team.edit', ['member' => $team]);
    }

    public function update(UpdateTeamMemberRequest $request, User $team)
    {
        $this->authorizeOwner();
        $this->authorizeSameFirm($team);

        $original = $team->only(['name', 'email', 'role', 'phone', 'specialty']);
        $team->update($request->validated());

        if ($original !== $team->only(['name', 'email', 'role', 'phone', 'specialty'])) {
            AuditLog::record(
                actor: Auth::user(),
                action: 'team_member_updated',
                modelType: User::class,
                modelId: $team->id,
                lawFirmId: $team->law_firm_id,
                oldValues: $original,
                newValues: $team->only(['name', 'email', 'role', 'phone', 'specialty']),
            );
        }

        return redirect()->route('team.index')
            ->with('success', 'تم تحديث بيانات العضو بنجاح');
    }

    public function destroy(User $team)
    {
        $this->authorizeOwner();
        $this->authorizeSameFirm($team);

        if ($team->id === Auth::id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الحالي');
        }

        if ($team->role === 'owner') {
            $ownersCount = User::where('law_firm_id', Auth::user()->law_firm_id)
                ->where('role', 'owner')
                ->count();

            if ($ownersCount <= 1) {
                return back()->with('error', 'لا يمكن حذف آخر مالك في المكتب');
            }
        }

        $owner = Auth::user();
        $firmName = $owner->lawFirm?->name ?? config('app.name');
        $memberSnapshot = $team->only([
            'name', 'email', 'role', 'phone', 'specialty',
            'invited_at', 'invitation_expires_at', 'activated_at',
        ]);

        Mail::to($team->email)
            ->send(new TeamRemovalMail($team, $firmName, $owner->name));

        DB::transaction(function () use ($team, $owner, $memberSnapshot) {
            DB::table('password_reset_tokens')->where('email', $team->email)->delete();

            AuditLog::record(
                actor: $owner,
                action: 'team_member_removed',
                modelType: User::class,
                modelId: $team->id,
                lawFirmId: $team->law_firm_id,
                oldValues: $memberSnapshot,
                newValues: [
                    'removed_by' => $owner->name,
                    'removed_at' => now()->toDateTimeString(),
                ],
            );

            $team->delete();
        });

        return redirect()->route('team.index')
            ->with('success', 'تم حذف عضو الفريق بنجاح');
    }

    private function authorizeOwner(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->isOwner(),
            403,
            'هذه العملية متاحة لمالك المكتب فقط.'
        );
    }

    private function authorizeSameFirm(User $user): void
    {
        abort_unless($user->law_firm_id === Auth::user()->law_firm_id, 404);
    }

    private function sendInvitationEmail(User $member, string $firmName): void
    {
        $token = Password::createToken($member);
        $setPasswordUrl = route('password.reset', ['token' => $token])
            . '?email=' . urlencode($member->email);

        Mail::to($member->email)
            ->send(new TeamInvitationMail($member, $firmName, $setPasswordUrl));
    }

    private function hasAvailableSeat(int $lawFirmId): bool
    {
        $limit = $this->resolveSeatLimit($lawFirmId);

        if ($limit === null) {
            return true;
        }

        $used = User::where('law_firm_id', $lawFirmId)->count();

        return $used < $limit;
    }

    private function resolveSeatLimit(int $lawFirmId): ?int
    {
        $subscription = Subscription::withoutGlobalScopes()
            ->where('law_firm_id', $lawFirmId)
            ->latest()
            ->first();

        if (!$subscription) {
            return null;
        }

        $plan = strtolower((string) $subscription->plan);

        if ($plan === 'enterprise') {
            return $subscription->user_limit > 0 ? (int) $subscription->user_limit : null;
        }

        return match ($plan) {
            'basic' => 2,
            'office' => 5,
            'premium' => null,
            default => null,
        };
    }
}
