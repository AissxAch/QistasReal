<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    private const BASIC_PLAN_PRICE = 2500;
    private const BASIC_PLAN_CURRENCY = 'DZD';

    public function locked()
    {
        $user = Auth::user();
        $lawFirm = $user?->lawFirm;
        $subscription = $lawFirm?->subscriptions()->latest()->first();
        $owner = $lawFirm
            ? User::where('law_firm_id', $lawFirm->id)->where('role', 'owner')->orderBy('id')->first()
            : null;

        $reason = 'locked_subscription';
        if (!$user?->law_firm_id) {
            $reason = 'no_firm';
        } elseif (!$subscription) {
            $reason = 'no_subscription';
        }

        return view('subscription.locked', compact('reason', 'owner', 'subscription'));
    }

    public function index()
    {
        $lawFirm = Auth::user()?->lawFirm;

        $subscription = $lawFirm?->subscriptions()->latest()->first();
        $payments = $lawFirm?->payments()->latest()->limit(10)->get() ?? collect();
        $billingContact = $lawFirm
            ? User::where('law_firm_id', $lawFirm->id)->where('role', 'owner')->orderBy('id')->first()
            : null;

        $isEnterpriseManaged = strtolower((string) optional($subscription)->plan) === 'enterprise';
        $canManageSubscription = (bool) ($subscription && Auth::user()?->isOwner() && !$isEnterpriseManaged);
        $isReadOnlySubscription = (bool) ($subscription && !$canManageSubscription);
        $subscriptionManagerLabel = $isEnterpriseManaged
            ? 'هذا الاشتراك يُدار من طرف المؤسسة أو المكتب بعقد مؤسسي.'
            : ($isReadOnlySubscription ? 'هذا الاشتراك يُدار من طرف مالك المكتب، ولا يمكنك تعديله من حسابك الحالي.' : 'يمكنك طلب التجديد ومتابعة الدفع من هذا القسم.');

        $plans = [
            'basic' => [
                'name' => 'الأساسي',
                'price' => 2500,
                'users' => 2,
                'cases' => 50,
            ],
            'office' => [
                'name' => 'الاحترافي',
                'price' => 8000,
                'users' => 5,
                'cases' => 'غير محدود',
            ],
            'premium' => [
                'name' => 'المتميز+',
                'price' => 9000,
                'users' => 'غير محدود',
                'cases' => 'غير محدود',
            ],
            'enterprise' => [
                'name' => 'المؤسسي',
                'price' => 0,
                'users' => 'حسب العقد',
                'cases' => 'غير محدود',
            ],
        ];

        $displayPlans = collect($plans)
            ->only(['basic', 'office', 'enterprise'])
            ->all();

        return view('subscription.index', compact(
            'subscription',
            'payments',
            'plans',
            'displayPlans',
            'billingContact',
            'canManageSubscription',
            'isReadOnlySubscription',
            'isEnterpriseManaged',
            'subscriptionManagerLabel'
        ));
    }

    public function renew(Request $request): RedirectResponse
    {
        $lawFirm = Auth::user()?->lawFirm;
        $subscription = $lawFirm?->subscriptions()->latest()->first();

        abort_unless($subscription, 404);
        abort_unless(Auth::user()?->isOwner(), 403);
        abort_if(strtolower((string) $subscription->plan) === 'enterprise', 403);

        $validated = $request->validate([
            'payment_method' => ['required', 'in:ccp,bank_transfer,cash'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
        ], [
            'payment_method.required' => 'طريقة الدفع مطلوبة',
            'payment_method.in' => 'طريقة الدفع المحددة غير صالحة',
        ]);

        Payment::create([
            'law_firm_id' => $lawFirm->id,
            'subscription_id' => $subscription->id,
            'amount' => $subscription->amount,
            'currency' => $subscription->currency ?: 'DZD',
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'transaction_id' => $validated['transaction_id'] ?? null,
            'payment_data' => [
                'renewal_request' => true,
                'requested_by' => Auth::id(),
                'requested_at' => now()->toDateTimeString(),
                'note' => $validated['note'] ?? null,
            ],
        ]);

        return back()->with('success', 'تم إرسال طلب التجديد بنجاح، وسيتم مراجعته من الإدارة.');
    }

    public function purchaseBasic(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $lawFirm = $user?->lawFirm;
        $subscription = $lawFirm?->subscriptions()->latest()->first();

        abort_unless($lawFirm, 403);
        abort_unless($user?->isOwner(), 403);

        if ($subscription) {
            return back()->with('error', 'لديك اشتراك بالفعل، استخدم طلب التجديد بدلًا من طلب شراء جديد.');
        }

        $hasPendingNewSubscriptionRequest = Payment::query()
            ->where('law_firm_id', $lawFirm->id)
            ->where('status', 'pending')
            ->whereNull('subscription_id')
            ->whereJsonContains('payment_data->request_type', 'new_subscription')
            ->exists();

        if ($hasPendingNewSubscriptionRequest) {
            return back()->with('error', 'لديك طلب اشتراك أساسي قيد المراجعة بالفعل.');
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:ccp,bank_transfer,cash'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
        ], [
            'payment_method.required' => 'طريقة الدفع مطلوبة',
            'payment_method.in' => 'طريقة الدفع المحددة غير صالحة',
        ]);

        Payment::create([
            'law_firm_id' => $lawFirm->id,
            'subscription_id' => null,
            'amount' => self::BASIC_PLAN_PRICE,
            'currency' => self::BASIC_PLAN_CURRENCY,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'transaction_id' => $validated['transaction_id'] ?? null,
            'payment_data' => [
                'purchase_request' => true,
                'request_type' => 'new_subscription',
                'requested_plan' => 'basic',
                'requested_by' => Auth::id(),
                'requested_at' => now()->toDateTimeString(),
                'note' => $validated['note'] ?? null,
            ],
        ]);

        return back()->with('success', 'تم إرسال طلب شراء الخطة الأساسية بنجاح، وسيتم مراجعته من الإدارة.');
    }
}
