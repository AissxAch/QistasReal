<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->isAdmin()) {
            return $next($request);
        }

        if ($request->routeIs('access.locked') || $request->routeIs('subscription*') || $request->routeIs('logout')) {
            return $next($request);
        }

        if (!$user->law_firm_id) {
            return redirect()->route('access.locked');
        }

        $lawFirm = $user->lawFirm;
        $subscription = $lawFirm?->subscriptions()->latest()->first();

        if (!$subscription) {
            return redirect()->route('access.locked');
        }

        $locked = $subscription->isLockedByStatus()
            || $subscription->isExpired()
            || in_array(strtolower((string) ($lawFirm?->subscription_status)), Subscription::LOCKED_STATUSES, true);

        if (!$locked) {
            return $next($request);
        }

        return redirect()->route('access.locked');
    }
}
