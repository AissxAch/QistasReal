<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSessionRequest;
use App\Http\Requests\UpdateSessionRequest;
use App\Models\CaseModel;
use App\Models\CourtSession;
use App\Services\NotificationTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $sessions = CourtSession::query()
            ->with('case')
            ->when($search, function ($query) use ($search) {
                $query->join('legal_cases', 'court_sessions.case_id', '=', 'legal_cases.id')
                    ->where(function ($subQuery) use ($search) {
                        $subQuery->where('court_sessions.court', 'like', "%{$search}%")
                            ->orWhere('legal_cases.title', 'like', "%{$search}%");
                    })
                    ->where('legal_cases.law_firm_id', Auth::user()->law_firm_id)
                    ->select('court_sessions.*');
            })
            ->orderByDesc('session_date')
            ->orderByDesc('session_time')
            ->paginate(15)
            ->withQueryString();

        return view('sessions.index', compact('sessions', 'search'));
    }

    public function create(Request $request)
    {
        $cases = CaseModel::all();
        $selectedCaseId = $request->integer('case_id');

        return view('sessions.create', compact('cases', 'selectedCaseId'));
    }

    public function store(StoreSessionRequest $request)
    {
        $session = CourtSession::create($request->validated());
        $session->load('case');

        NotificationTemplateService::sessionCreated(Auth::user(), $session);

        return redirect()->route('sessions.index')
            ->with('success', 'تمت إضافة الجلسة بنجاح');
    }

    public function show(CourtSession $session)
    {
        $session->load('case.clients');

        return view('sessions.show', compact('session'));
    }

    public function edit(CourtSession $session)
    {
        $cases = CaseModel::all();

        return view('sessions.edit', compact('session', 'cases'));
    }

    public function update(UpdateSessionRequest $request, CourtSession $session)
    {
        $session->update($request->validated());
        $session->load('case');

        NotificationTemplateService::sessionUpdated(Auth::user(), $session);

        return redirect()->route('sessions.index')
            ->with('success', 'تم تحديث الجلسة بنجاح');
    }

    public function destroy(CourtSession $session)
    {
        $session->delete();

        return redirect()->route('sessions.index')
            ->with('success', 'تم حذف الجلسة بنجاح');
    }
}
