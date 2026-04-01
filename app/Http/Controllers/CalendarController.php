<?php

namespace App\Http\Controllers;

use App\Models\CourtSession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        $month = max(1, min(12, $month));

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $sessions = CourtSession::with('case')
            ->whereBetween('session_date', [$startOfMonth, $endOfMonth])
            ->orderBy('session_date')
            ->orderBy('session_time')
            ->get();

        $sessions = $sessions->groupBy(fn ($session) => $session->session_date->format('Y-m-d'));

        return view('calendar', compact('sessions', 'month', 'year', 'startOfMonth'));
    }
}
