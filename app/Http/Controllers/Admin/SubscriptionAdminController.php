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
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $subscriptionStatus = strtolower((string) $request->query('subscription_status', 'all'));
        if (!in_array($subscriptionStatus, ['all', 'trial', 'active', 'expired', 'suspended', 'cancelled'], true)) {
            $subscriptionStatus = 'all';
        }

        $subscriptionPlan = strtolower((string) $request->query('subscription_plan', 'all'));
        if (!in_array($subscriptionPlan, ['all', 'basic', 'office', 'premium', 'enterprise'], true)) {
            $subscriptionPlan = 'all';
        }

        $subscriptionEmail = trim((string) $request->query('subscription_email', ''));

        $pendingType = strtolower((string) $request->query('pending_type', 'all'));
        if (!in_array($pendingType, ['all', 'new_subscription', 'renewal'], true)) {
            $pendingType = 'all';
        }

        $pendingEmail = trim((string) $request->query('pending_email', ''));

        $subscriptionsQuery = Subscription::withoutGlobalScopes()
            ->with('lawFirm:id,name,email')
            ->latest();

        if ($subscriptionStatus !== 'all') {
            $subscriptionsQuery->where('status', $subscriptionStatus);
        }

        if ($subscriptionPlan !== 'all') {
            $subscriptionsQuery->where('plan', $subscriptionPlan);
        }

        if ($subscriptionEmail !== '') {
            $subscriptionsQuery->whereHas('lawFirm', function ($lawFirmQuery) use ($subscriptionEmail) {
                $lawFirmQuery->where('email', 'like', '%' . $subscriptionEmail . '%')
                    ->orWhereHas('users', function ($usersQuery) use ($subscriptionEmail) {
                        $usersQuery->where('email', 'like', '%' . $subscriptionEmail . '%');
                    });
            });
        }

        $subscriptions = $subscriptionsQuery
            ->paginate(15)
            ->withQueryString();

        $pendingPaymentsQuery = Payment::withoutGlobalScopes()
            ->with(['lawFirm:id,name,email', 'subscription:id,plan,status,ends_at'])
            ->where('status', 'pending')
            ->latest();

        if ($pendingType === 'new_subscription') {
            $pendingPaymentsQuery->whereNull('subscription_id')
                ->whereJsonContains('payment_data->request_type', 'new_subscription');
        } elseif ($pendingType === 'renewal') {
            $pendingPaymentsQuery->where(function ($query) {
                $query->whereNotNull('subscription_id')
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereNull('payment_data->request_type')
                            ->orWhere('payment_data->request_type', '!=', 'new_subscription');
                    });
            });
        }

        if ($pendingEmail !== '') {
            $pendingPaymentsQuery->where(function ($query) use ($pendingEmail) {
                $query->whereHas('lawFirm', function ($lawFirmQuery) use ($pendingEmail) {
                    $lawFirmQuery->where('email', 'like', '%' . $pendingEmail . '%')
                        ->orWhereHas('users', function ($usersQuery) use ($pendingEmail) {
                            $usersQuery->where('email', 'like', '%' . $pendingEmail . '%');
                        });
                });
            });
        }

        $pendingPayments = $pendingPaymentsQuery
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

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

        return view('admin.subscriptions.index', compact(
            'subscriptions',
            'pendingPayments',
            'lawFirms',
            'stats',
            'subscriptionStatus',
            'subscriptionPlan',
            'subscriptionEmail',
            'pendingType',
            'pendingEmail'
        ));
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

        if ($validated['status'] === 'completed' && !$paymentModel->subscription) {
            $paymentData = $paymentModel->payment_data ?? [];
            $requestType = is_array($paymentData) ? ($paymentData['request_type'] ?? null) : null;
            $requestedPlan = is_array($paymentData) ? strtolower((string) ($paymentData['requested_plan'] ?? 'basic')) : 'basic';

            if ($requestType === 'new_subscription') {
                $existingSubscription = Subscription::withoutGlobalScopes()
                    ->where('law_firm_id', $paymentModel->law_firm_id)
                    ->latest()
                    ->first();

                if ($existingSubscription) {
                    $existingSubscription->update([
                        'plan' => $requestedPlan,
                        'status' => 'active',
                        'starts_at' => $existingSubscription->starts_at ?? Carbon::now(),
                        'ends_at' => Carbon::now()->addMonth(),
                        'amount' => $paymentModel->amount,
                        'currency' => $paymentModel->currency ?: $existingSubscription->currency,
                    ]);

                    $this->syncLawFirmSubscription($existingSubscription);
                } else {
                    $newSubscription = Subscription::withoutGlobalScopes()->create([
                        'law_firm_id' => $paymentModel->law_firm_id,
                        'plan' => $requestedPlan,
                        'status' => 'active',
                        'starts_at' => Carbon::now(),
                        'ends_at' => Carbon::now()->addMonth(),
                        'trial_ends_at' => null,
                        'amount' => $paymentModel->amount,
                        'currency' => $paymentModel->currency ?: 'DZD',
                    ]);

                    $paymentModel->update(['subscription_id' => $newSubscription->id]);
                    $this->syncLawFirmSubscription($newSubscription);
                }
            }
        }

        return back()->with('success', 'تم تحديث حالة الدفع');
    }

    public function enterprise(Request $request)
    {
        $this->authorizeAdmin();

        $enterpriseStatus = strtolower((string) $request->query('enterprise_status', 'all'));
        if (!in_array($enterpriseStatus, ['all', 'trial', 'active', 'expired', 'suspended', 'cancelled'], true)) {
            $enterpriseStatus = 'all';
        }

        $enterpriseEmail = trim((string) $request->query('enterprise_email', ''));

        $enterpriseSearch = trim((string) $request->query('enterprise_search', ''));

        $enterpriseSubscriptionsQuery = Subscription::withoutGlobalScopes()
            ->with('lawFirm:id,name,email')
            ->where('plan', 'enterprise')
            ->latest();

        if ($enterpriseStatus !== 'all') {
            $enterpriseSubscriptionsQuery->where('status', $enterpriseStatus);
        }

        if ($enterpriseEmail !== '') {
            $enterpriseSubscriptionsQuery->whereHas('lawFirm', function ($lawFirmQuery) use ($enterpriseEmail) {
                $lawFirmQuery->where('email', 'like', '%' . $enterpriseEmail . '%')
                    ->orWhereHas('users', function ($usersQuery) use ($enterpriseEmail) {
                        $usersQuery->where('email', 'like', '%' . $enterpriseEmail . '%');
                    });
            });
        }

        if ($enterpriseSearch !== '') {
            $enterpriseSubscriptionsQuery->where(function ($query) use ($enterpriseSearch) {
                $query->where('contract_number', 'like', '%' . $enterpriseSearch . '%')
                    ->orWhere('enterprise_account_name', 'like', '%' . $enterpriseSearch . '%')
                    ->orWhereHas('lawFirm', function ($lawFirmQuery) use ($enterpriseSearch) {
                        $lawFirmQuery->where('name', 'like', '%' . $enterpriseSearch . '%');
                    });
            });
        }

        $enterpriseSubscriptions = $enterpriseSubscriptionsQuery
            ->paginate(15)
            ->withQueryString();

        return view('admin.subscriptions.enterprise', compact(
            'enterpriseSubscriptions',
            'enterpriseStatus',
            'enterpriseEmail',
            'enterpriseSearch'
        ));
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
