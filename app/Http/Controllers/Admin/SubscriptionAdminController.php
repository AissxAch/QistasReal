<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LawFirm;
use App\Models\Payment;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionAdminController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $subscriptions = Subscription::withoutGlobalScopes()
            ->with('lawFirm:id,name,email')
            ->latest()
            ->paginate(15);

        $pendingPayments = Payment::withoutGlobalScopes()
            ->with(['lawFirm:id,name,email', 'subscription:id,plan,status,ends_at'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10, ['*'], 'pending_page');

        $lawFirms = LawFirm::query()
            ->withCount('users')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'subscription_status', 'subscription_ends_at']);

        $stats = [
            'firms_count' => $lawFirms->count(),
            'active_subscriptions' => Subscription::withoutGlobalScopes()->where('status', 'active')->count(),
            'enterprise_subscriptions' => Subscription::withoutGlobalScopes()->where('plan', 'enterprise')->count(),
            'pending_payments' => Payment::withoutGlobalScopes()->where('status', 'pending')->count(),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'pendingPayments', 'lawFirms', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'law_firm_id' => ['required', 'exists:law_firms,id'],
            'plan' => ['required', 'in:basic,office,premium,enterprise'],
            'status' => ['required', 'in:trial,active,expired,suspended,cancelled'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'trial_ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:8'],
        ], [
            'law_firm_id.required' => 'اختيار المكتب مطلوب',
            'law_firm_id.exists' => 'المكتب المحدد غير موجود',
            'plan.required' => 'اختيار الخطة مطلوب',
            'plan.in' => 'الخطة المحددة غير صالحة',
        ]);

        $subscription = Subscription::withoutGlobalScopes()->create([
            'law_firm_id' => (int) $validated['law_firm_id'],
            'plan' => $validated['plan'],
            'status' => $validated['status'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'] ?? null,
            'trial_ends_at' => $validated['trial_ends_at'] ?? null,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? 'DZD',
        ]);

        $this->syncLawFirmSubscription($subscription);

        return back()->with('success', 'تم إنشاء الاشتراك بنجاح');
    }

    public function updateSubscriptionStatus(Request $request, int $subscription): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => ['required', 'in:trial,active,expired,suspended,cancelled'],
        ]);

        $subscriptionModel = Subscription::withoutGlobalScopes()->findOrFail($subscription);
        $subscriptionModel->update([
            'status' => $validated['status'],
        ]);

        $this->syncLawFirmSubscription($subscriptionModel);

        return back()->with('success', 'تم تحديث حالة الاشتراك');
    }

    public function updatePaymentStatus(Request $request, int $payment): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => ['required', 'in:pending,completed,failed'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $paymentModel = Payment::withoutGlobalScopes()->with('subscription')->findOrFail($payment);

        $paymentData = $paymentModel->payment_data ?? [];
        if (!is_array($paymentData)) {
            $paymentData = [];
        }

        if (!empty($validated['note'])) {
            $paymentData['admin_note'] = $validated['note'];
        }

        $paymentData['reviewed_by'] = Auth::id();
        $paymentData['reviewed_at'] = now()->toDateTimeString();

        $paymentModel->update([
            'status' => $validated['status'],
            'transaction_id' => $validated['transaction_id'] ?? $paymentModel->transaction_id,
            'payment_data' => $paymentData,
        ]);

        if ($validated['status'] === 'completed' && $paymentModel->subscription) {
            $subscription = Subscription::withoutGlobalScopes()->find($paymentModel->subscription->id);

            if ($subscription) {
                $baseDate = $subscription->ends_at && $subscription->ends_at->isFuture()
                    ? $subscription->ends_at->copy()
                    : Carbon::now();

                $subscription->update([
                    'status' => 'active',
                    'ends_at' => $baseDate->addMonth(),
                ]);

                $this->syncLawFirmSubscription($subscription);
            }
        }

        return back()->with('success', 'تم تحديث حالة الدفع');
    }

    public function enterprise()
    {
        $this->authorizeAdmin();

        $enterpriseSubscriptions = Subscription::withoutGlobalScopes()
            ->with('lawFirm:id,name,email')
            ->where('plan', 'enterprise')
            ->latest()
            ->paginate(15);

        return view('admin.subscriptions.enterprise', compact('enterpriseSubscriptions'));
    }

    public function updateEnterprise(Request $request, int $subscription): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'contract_number' => ['nullable', 'string', 'max:255'],
            'enterprise_account_name' => ['nullable', 'string', 'max:255'],
            'contract_starts_at' => ['nullable', 'date'],
            'contract_ends_at' => ['nullable', 'date', 'after_or_equal:contract_starts_at'],
            'user_limit' => ['nullable', 'integer', 'min:1'],
            'billing_cycle' => ['nullable', 'in:monthly,quarterly,yearly,custom'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:8'],
            'status' => ['required', 'in:trial,active,expired,suspended,cancelled'],
        ]);

        $subscriptionModel = Subscription::withoutGlobalScopes()->findOrFail($subscription);

        if ($subscriptionModel->plan !== 'enterprise') {
            return back()->with('error', 'هذا الاشتراك ليس مؤسسيًا');
        }

        $subscriptionModel->update([
            'contract_number' => $validated['contract_number'] ?? null,
            'enterprise_account_name' => $validated['enterprise_account_name'] ?? null,
            'contract_starts_at' => $validated['contract_starts_at'] ?? null,
            'contract_ends_at' => $validated['contract_ends_at'] ?? null,
            'user_limit' => $validated['user_limit'] ?? null,
            'billing_cycle' => $validated['billing_cycle'] ?? null,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? $subscriptionModel->currency,
            'status' => $validated['status'],
            'ends_at' => $validated['contract_ends_at'] ?? $subscriptionModel->ends_at,
        ]);

        $this->syncLawFirmSubscription($subscriptionModel);

        return back()->with('success', 'تم تحديث بيانات الحساب المؤسسي');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }

    private function syncLawFirmSubscription(Subscription $subscription): void
    {
        $lawFirm = LawFirm::find($subscription->law_firm_id);

        if (!$lawFirm) {
            return;
        }

        $lawFirm->update([
            'subscription_status' => $subscription->status,
            'subscription_ends_at' => $subscription->ends_at,
        ]);
    }
}
