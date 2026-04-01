<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\CaseModel;
use App\Models\Task;
use App\Models\User;
use App\Services\NotificationTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'list');
        if (!in_array($view, ['list', 'kanban'])) {
            $view = 'list';
        }

        $lawFirmId = $this->resolveLawFirmId();

        $tasksQuery = Task::with(['case.clients', 'assignedTo', 'lawyers']);

        if (!Auth::user()->isAdmin()) {
            $tasksQuery->where(function ($query) {
                $query->where('assigned_to', Auth::id())
                    ->orWhereHas('lawyers', function ($lawyerQuery) {
                        $lawyerQuery->where('users.id', Auth::id());
                    });
            });
        }

        $tasks = $tasksQuery
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $users = User::where('law_firm_id', $lawFirmId)
            ->where('role', 'lawyer')
            ->orderBy('name')
            ->get();

        return view('tasks.index', compact('tasks', 'users', 'view'));
    }

    public function create()
    {
        $lawFirmId = $this->resolveLawFirmId();

        $cases = CaseModel::all();
        $users = User::where('law_firm_id', $lawFirmId)
            ->where('role', 'lawyer')
            ->orderBy('name')
            ->get();

        return view('tasks.create', compact('cases', 'users'));
    }

    public function store(StoreTaskRequest $request)
    {
        $lawFirmId = $this->resolveLawFirmId();
        if (!$lawFirmId) {
            $target = Auth::user()->isAdmin() ? 'support.dashboard' : 'settings';
            return redirect()->route($target)
                ->with('error', 'لا يمكن إضافة مهمة قبل تحديد المكتب القانوني.');
        }

        $validated = $request->validated();
        $lawyerIds = array_values(array_unique(array_map('intval', $validated['lawyer_ids'] ?? [])));
        $validated['assigned_to'] = $lawyerIds[0] ?? ($validated['assigned_to'] ?? null);
        $validated['law_firm_id'] = $lawFirmId;
        unset($validated['lawyer_ids']);

        $task = Task::create($validated);
        $task->lawyers()->sync($lawyerIds);
        $task->load(['case', 'assignedTo', 'lawyers']);

        NotificationTemplateService::taskCreated(Auth::user(), $task);
        NotificationTemplateService::taskAssigned(Auth::user(), $task);

        return redirect()->route('tasks.index')
            ->with('success', 'تمت إضافة المهمة بنجاح');
    }

    public function show(Task $task)
    {
        $this->authorizeTaskAccess($task);

        $task->load(['case.clients', 'assignedTo', 'lawyers']);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorizeTaskAccess($task);

        $lawFirmId = $this->resolveLawFirmId();

        $cases = CaseModel::all();
        $users = User::where('law_firm_id', $lawFirmId)
            ->where('role', 'lawyer')
            ->orderBy('name')
            ->get();

        return view('tasks.edit', compact('task', 'cases', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorizeTaskAccess($task);

        $oldAssignedTo = $task->assigned_to;
        $oldLawyerIds = $task->lawyers()->pluck('users.id')->sort()->values()->all();

        $validated = $request->validated();
        $lawyerIds = array_values(array_unique(array_map('intval', $validated['lawyer_ids'] ?? [])));
        $validated['assigned_to'] = $lawyerIds[0] ?? ($validated['assigned_to'] ?? null);
        unset($validated['lawyer_ids']);

        $task->update($validated);
        $task->lawyers()->sync($lawyerIds);
        $task->load(['case', 'assignedTo', 'lawyers']);

        $newLawyerIds = collect($lawyerIds)->sort()->values()->all();

        if (($task->assigned_to && $task->assigned_to !== $oldAssignedTo) || $newLawyerIds !== $oldLawyerIds) {
            NotificationTemplateService::taskAssigned(Auth::user(), $task);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'تم تحديث المهمة بنجاح');
    }

    public function destroy(Task $task)
    {
        $this->authorizeTaskAccess($task);

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'تم حذف المهمة بنجاح');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorizeTaskAccess($task);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,done'],
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json(['success' => true]);
    }

    private function authorizeTaskAccess(Task $task): void
    {
        if (Auth::user()?->isAdmin()) {
            return;
        }

        $userId = Auth::id();
        $canAccess = (int) $task->assigned_to === (int) $userId
            || $task->lawyers()->where('users.id', $userId)->exists();

        abort_unless($canAccess, 403, 'لا تملك صلاحية الوصول إلى هذه المهمة.');
    }

    private function resolveLawFirmId(): ?int
    {
        $user = Auth::user();

        if ($user?->isAdmin()) {
            return session('support_firm_id') ? (int) session('support_firm_id') : null;
        }

        return $user?->law_firm_id ? (int) $user->law_firm_id : null;
    }
}
