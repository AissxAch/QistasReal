<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\CaseModel;
use App\Models\Client;
use App\Models\User;
use App\Services\NotificationTemplateService;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = CaseModel::with(['clients', 'lawyers']);

        if ($user->isLawyer()) {
            $query->where(function ($builder) use ($user) {
                $builder->where('created_by_user_id', $user->id)
                    ->orWhere('assigned_lawyer_id', $user->id)
                    ->orWhereHas('lawyers', function ($lawyerQuery) use ($user) {
                        $lawyerQuery->where('users.id', $user->id);
                    });
            });
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($builder) use ($search) {
                $builder->where('case_number', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('court', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->string('priority'));
        }

        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'next_session':
                $query->orderByRaw('next_session_date IS NULL')
                      ->orderBy('next_session_date', 'asc');
                break;
            case 'fees_high':
                $query->orderBy('fees_total', 'desc');
                break;
            case 'fees_low':
                $query->orderBy('fees_total', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                $sort = 'newest';
                break;
        }

        $cases = $query->paginate(10)->withQueryString();

        return view('cases.index', [
            'cases' => $cases,
            'filters' => [
                'search' => $request->input('search', ''),
                'status' => $request->input('status', ''),
                'priority' => $request->input('priority', ''),
                'sort' => $sort,
            ],
        ]);
    }

    public function create()
    {
        $clients = Client::all();
        $lawFirmId = $this->resolveLawFirmId();

        $lawyersForAssignment = collect();
        if (Auth::user()->isOwner() || Auth::user()->isAdmin()) {
            $lawyersForAssignment = User::query()
                ->where('law_firm_id', $lawFirmId)
                ->where('role', 'lawyer')
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return view('cases.create', compact('clients', 'lawyersForAssignment'));
    }

    public function store(Request $request)
    {
        $lawFirmId = $this->resolveLawFirmId();

        if (!$lawFirmId) {
            $target = Auth::user()->isAdmin() ? 'support.dashboard' : 'settings';
            return redirect()->route($target)
                ->with('error', 'لا يمكن إضافة قضية قبل تحديد المكتب القانوني.');
        }

        $validated = $request->validate([
            'case_number' => [
                'required',
                'string',
                Rule::unique('legal_cases', 'case_number')
                    ->where('law_firm_id', $lawFirmId),
            ],
            'title'             => 'required|string|max:255',
            'court'             => 'required|string|max:255',
            'case_type'         => 'nullable|string|max:255',
            'degree'            => 'required|in:first,appeal,cassation',
            'status'            => 'required|in:active,pending,closed',
            'priority'          => 'required|in:low,medium,high',
            'fees_total'        => 'nullable|numeric|min:0',
            'fees_paid'         => 'nullable|numeric|min:0',
            'description'       => 'nullable|string',
            'start_date'        => 'nullable|date',
            'next_session_date' => 'nullable|date',
            'client_ids'        => 'nullable|array',
            'client_ids.*'      => 'exists:clients,id',
            'lawyer_ids'         => 'nullable|array',
            'lawyer_ids.*'       => [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) use ($lawFirmId) {
                    $query->where('law_firm_id', $lawFirmId)->where('role', 'lawyer');
                }),
            ],
        ], [
            'case_number.required' => 'رقم القضية مطلوب',
            'case_number.unique'   => 'رقم القضية مستخدم مسبقاً في مكتبك',
            'title.required'       => 'عنوان القضية مطلوب',
            'court.required'       => 'اسم المحكمة مطلوب',
            'degree.required'      => 'درجة القضية مطلوبة',
            'status.required'      => 'حالة القضية مطلوبة',
            'priority.required'    => 'أولوية القضية مطلوبة',
        ]);

        // Compute fees_remaining before saving
        $validated['fees_total']     = $validated['fees_total'] ?? 0;
        $validated['fees_paid']      = $validated['fees_paid'] ?? 0;
        $validated['fees_remaining'] = $validated['fees_total'] - $validated['fees_paid'];
        $validated['created_by_user_id'] = Auth::id();

        $lawyerIds = array_values(array_unique(array_map('intval', $validated['lawyer_ids'] ?? [])));

        if (!Auth::user()->isOwner() && !Auth::user()->isAdmin()) {
            $lawyerIds = [];
        }

        $validated['assigned_lawyer_id'] = $lawyerIds[0] ?? null;
        $validated['law_firm_id'] = $lawFirmId;
        unset($validated['lawyer_ids']);

        $case = CaseModel::create($validated);
        $case->clients()->sync($validated['client_ids'] ?? []);
        $case->lawyers()->sync($lawyerIds);
        NotificationTemplateService::caseCreated(Auth::user(), $case);

        return redirect()->route('cases.index')
                         ->with('success', 'تمت إضافة القضية بنجاح');
    }

    public function show(CaseModel $case)
    {
        $this->authorizeCaseAccess($case);

        $case->load('clients', 'assignedLawyer', 'lawyers');
        return view('cases.show', compact('case'));
    }

    // FIX: was missing $clients — edit view needs the full clients list
    public function edit(CaseModel $case)
    {
        $this->authorizeCaseAccess($case);

        $clients = Client::all();
        $lawFirmId = $this->resolveLawFirmId();

        $lawyersForAssignment = collect();
        if (Auth::user()->isOwner() || Auth::user()->isAdmin()) {
            $lawyersForAssignment = User::query()
                ->where('law_firm_id', $lawFirmId)
                ->where('role', 'lawyer')
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        return view('cases.edit', compact('case', 'clients', 'lawyersForAssignment'));
    }

    // FIX: was completely empty — now fully implemented
    public function update(Request $request, CaseModel $case)
    {
        $this->authorizeCaseAccess($case);

        $lawFirmId = $this->resolveLawFirmId();

        $validated = $request->validate([
            'case_number' => [
                'required',
                'string',
                Rule::unique('legal_cases', 'case_number')
                    ->ignore($case->id)
                    ->where('law_firm_id', $lawFirmId),
            ],
            'title'             => 'required|string|max:255',
            'court'             => 'required|string|max:255',
            'case_type'         => 'nullable|string|max:255',
            'degree'            => 'required|in:first,appeal,cassation',
            'status'            => 'required|in:active,pending,closed',
            'priority'          => 'required|in:low,medium,high',
            'fees_total'        => 'nullable|numeric|min:0',
            'fees_paid'         => 'nullable|numeric|min:0',
            'description'       => 'nullable|string',
            'start_date'        => 'nullable|date',
            'next_session_date' => 'nullable|date',
            'client_ids'        => 'nullable|array',
            'client_ids.*'      => 'exists:clients,id',
            'lawyer_ids'         => 'nullable|array',
            'lawyer_ids.*'       => [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) use ($lawFirmId) {
                    $query->where('law_firm_id', $lawFirmId)->where('role', 'lawyer');
                }),
            ],
        ], [
            'case_number.required' => 'رقم القضية مطلوب',
            'case_number.unique'   => 'رقم القضية مستخدم مسبقاً في مكتبك',
            'title.required'       => 'عنوان القضية مطلوب',
            'court.required'       => 'اسم المحكمة مطلوب',
            'degree.required'      => 'درجة القضية مطلوبة',
            'status.required'      => 'حالة القضية مطلوبة',
            'priority.required'    => 'أولوية القضية مطلوبة',
        ]);

        $validated['fees_total']     = $validated['fees_total'] ?? 0;
        $validated['fees_paid']      = $validated['fees_paid'] ?? 0;
        $validated['fees_remaining'] = $validated['fees_total'] - $validated['fees_paid'];

        $lawyerIds = array_values(array_unique(array_map('intval', $validated['lawyer_ids'] ?? [])));

        if (!Auth::user()->isOwner() && !Auth::user()->isAdmin()) {
            $lawyerIds = $case->lawyers()->pluck('users.id')->all();
        }

        $validated['assigned_lawyer_id'] = $lawyerIds[0] ?? null;
        unset($validated['lawyer_ids']);

        $case->update($validated);
        $case->clients()->sync($validated['client_ids'] ?? []);
        $case->lawyers()->sync($lawyerIds);

        return redirect()->route('cases.index')
                         ->with('success', 'تم تحديث القضية بنجاح');
    }

    public function destroy(CaseModel $case)
    {
        $this->authorizeCaseAccess($case);

        $case->delete();
        return redirect()->route('cases.index')
                         ->with('success', 'تم حذف القضية بنجاح');
    }

    private function authorizeCaseAccess(CaseModel $case): void
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isOwner()) {
            return;
        }

        if ($user->isLawyer() && (
            (int) $case->created_by_user_id === (int) $user->id
            || (int) $case->assigned_lawyer_id === (int) $user->id
            || $case->lawyers()->where('users.id', $user->id)->exists()
        )) {
            return;
        }

        abort(403, 'لا تملك صلاحية الوصول إلى هذه القضية.');
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
