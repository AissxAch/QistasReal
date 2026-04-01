<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LawFirm;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LawFirmAdminController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $search = trim((string) $request->query('search', ''));

        $lawFirmsQuery = LawFirm::query()
            ->withCount('users')
            ->with(['users' => function ($query) {
                $query->select('id', 'law_firm_id', 'name', 'email', 'role')
                    ->where('role', 'owner')
                    ->orderBy('id');
            }])
            ->latest();

        if ($search !== '') {
            $lawFirmsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhereHas('users', function ($usersQuery) use ($search) {
                        $usersQuery->where('email', 'like', '%' . $search . '%')
                            ->orWhere('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $lawFirms = $lawFirmsQuery->paginate(12)->withQueryString();
        $lawFirmOptions = LawFirm::query()->orderBy('name')->get(['id', 'name']);
        $existingUsers = User::query()
            ->where('role', '!=', 'admin')
            ->orderBy('email')
            ->get(['id', 'name', 'email', 'law_firm_id', 'role']);

        return view('admin.law-firms.index', compact('lawFirms', 'search', 'lawFirmOptions', 'existingUsers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'اسم المكتب مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
        ]);

        LawFirm::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'subscription_status' => 'expired',
            'subscription_ends_at' => null,
        ]);

        return back()->with('success', 'تم إنشاء المكتب بنجاح.');
    }

    public function storeOwner(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'law_firm_id' => ['required', 'exists:law_firms,id'],
            'email' => ['required', 'email', 'max:255', 'exists:users,email'],
        ], [
            'law_firm_id.required' => 'اختيار المكتب مطلوب',
            'law_firm_id.exists' => 'المكتب المحدد غير موجود',
            'email.exists' => 'لا يوجد مستخدم بهذا البريد الإلكتروني',
        ]);

        $lawFirm = LawFirm::findOrFail((int) $validated['law_firm_id']);
        $user = User::where('email', $validated['email'])->firstOrFail();

        if ($user->isAdmin()) {
            return back()->with('error', 'لا يمكن تعيين حساب إداري كمالك مكتب.');
        }

        if ($user->law_firm_id && (int) $user->law_firm_id !== (int) $lawFirm->id) {
            return back()->with('error', 'هذا المستخدم مرتبط بالفعل بمكتب آخر، ولا يمكن نقله تلقائيًا.');
        }

        DB::transaction(function () use ($user, $lawFirm) {
            $user->update([
                'law_firm_id' => $lawFirm->id,
                'role' => 'owner',
            ]);
        });

        return back()->with('success', 'تم ربط المستخدم بالمكتب كمالك بنجاح.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }
}
