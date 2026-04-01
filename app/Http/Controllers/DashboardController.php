<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Client;
use App\Models\CourtSession;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCases = CaseModel::count();
        $activeCases = CaseModel::where('status', 'active')->count();
        $pendingCases = CaseModel::where('status', 'pending')->count();
        $closedCases = max($totalCases - ($activeCases + $pendingCases), 0);

        $taskScope = Task::query()->where(function ($query) {
            $query->where('assigned_to', Auth::id())
                ->orWhereHas('lawyers', function ($lawyerQuery) {
                    $lawyerQuery->where('users.id', Auth::id());
                });
        });

        $totalTasksCount = (clone $taskScope)->count();
        $pendingTasksCount = (clone $taskScope)->where('status', 'pending')->count();
        $completedTasksCount = (clone $taskScope)->whereIn('status', ['completed', 'done'])->count();

        $upcomingSessionsCount = CourtSession::where('session_date', '>=', today())->count();
        $feesTotal = (float) CaseModel::sum('fees_total');
        $feesRemaining = (float) CaseModel::sum('fees_remaining');

        $stats = [
            'total_cases'       => $totalCases,
            'active_cases'      => $activeCases,
            'total_clients'     => Client::count(),
            'pending_tasks'     => $pendingTasksCount,
            'upcoming_sessions' => $upcomingSessionsCount,
            'fees_total'        => $feesTotal,
            'fees_remaining'    => $feesRemaining,
        ];

        $analytics = [
            'case_status' => [
                'active'  => $activeCases,
                'pending' => $pendingCases,
                'closed'  => $closedCases,
            ],
            'tasks' => [
                'total'     => $totalTasksCount,
                'pending'   => $pendingTasksCount,
                'completed' => $completedTasksCount,
            ],
            'fees' => [
                'collected' => max($feesTotal - $feesRemaining, 0),
                'remaining' => $feesRemaining,
                'total'     => $feesTotal,
            ],
        ];

        $upcoming_sessions = CourtSession::with('case')
            ->where('session_date', '>=', today())
            ->orderBy('session_date')
            ->orderBy('session_time')
            ->limit(5)
            ->get();

        $pending_tasks = Task::with(['case', 'assignee', 'lawyers'])
            ->where(function ($query) {
                $query->where('assigned_to', Auth::id())
                    ->orWhereHas('lawyers', function ($lawyerQuery) {
                        $lawyerQuery->where('users.id', Auth::id());
                    });
            })
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        $recent_cases = CaseModel::with('clients')
            ->withCount('clients')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'analytics',
            'upcoming_sessions',
            'pending_tasks',
            'recent_cases'
        ));
    }
}