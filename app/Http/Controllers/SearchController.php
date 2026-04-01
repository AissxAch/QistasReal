<?php

namespace App\Http\Controllers;

use App\Models\CaseModel;
use App\Models\Client;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q', ''));

        if ($query === '') {
            return view('search.index', [
                'query' => '',
                'cases' => collect(),
                'clients' => collect(),
                'tasks' => collect(),
                'total' => 0,
            ]);
        }

        $cases = CaseModel::query()
            ->where(function ($builder) use ($query) {
                $builder->where('case_number', 'like', "%{$query}%")
                        ->orWhere('title', 'like', "%{$query}%")
                        ->orWhere('court', 'like', "%{$query}%");
            })
            ->latest()
            ->limit(8)
            ->get();

        $clients = Client::query()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
            })
            ->latest()
            ->limit(8)
            ->get();

        $tasks = Task::query()
            ->where(function ($builder) {
                $builder->where('assigned_to', Auth::id())
                        ->orWhereHas('lawyers', function ($lawyerQuery) {
                            $lawyerQuery->where('users.id', Auth::id());
                        });
            })
            ->where(function ($builder) use ($query) {
                $builder->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->limit(8)
            ->get();

        $total = $cases->count() + $clients->count() + $tasks->count();

        return view('search.index', [
            'query' => $query,
            'cases' => $cases,
            'clients' => $clients,
            'tasks' => $tasks,
            'total' => $total,
        ]);
    }
}
