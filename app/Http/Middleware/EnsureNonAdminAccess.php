<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNonAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isAdmin() && !session('support_firm_id')) {
            return redirect()->route('support.dashboard')
                ->with('error', 'اختر مكتبًا أولاً من بوابة الدعم قبل الدخول إلى الأقسام التشغيلية.');
        }

        return $next($request);
    }
}
