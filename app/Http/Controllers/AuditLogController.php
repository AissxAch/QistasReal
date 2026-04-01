<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::check() && Auth::user()->isOwner(), 403);

        $logs = AuditLog::with('user')
            ->latest()
            ->paginate(25);

        return view('logs.index', compact('logs'));
    }
}
