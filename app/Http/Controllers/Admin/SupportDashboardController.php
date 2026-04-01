<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LawFirm;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupportDashboardController extends Controller
{
    public function setFirmContext(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'law_firm_id' => ['nullable', 'integer', 'exists:law_firms,id'],
        ]);

        if (empty($validated['law_firm_id'])) {
            session()->forget('support_firm_id');
            return back()->with('success', 'تم إلغاء تحديد المكتب.');
        }

        session(['support_firm_id' => (int) $validated['law_firm_id']]);

        return back()->with('success', 'تم تحديد المكتب بنجاح.');
    }

    public function index()
    {
        $lawFirms = LawFirm::withoutGlobalScopes()
            ->orderBy('name')
            ->get(['id', 'name', 'subscription_status']);

        $currentFirmId = (int) session('support_firm_id');
        $currentFirm = $lawFirms->firstWhere('id', $currentFirmId);

        $stats = [
            'firms_count' => LawFirm::withoutGlobalScopes()->count(),
            'owners_count' => User::withoutGlobalScopes()->where('role', 'owner')->count(),
            'active_subscriptions' => Subscription::withoutGlobalScopes()->where('status', 'active')->count(),
            'expired_subscriptions' => Subscription::withoutGlobalScopes()->whereIn('status', Subscription::LOCKED_STATUSES)->count(),
            'pending_payments' => Payment::withoutGlobalScopes()->where('status', 'pending')->count(),
        ];

        $latestPayments = Payment::withoutGlobalScopes()
            ->with(['lawFirm:id,name', 'subscription:id,plan,status'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.support.dashboard', compact('stats', 'latestPayments', 'lawFirms', 'currentFirm'));
    }
}
