<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a paginated list of clients with optional search/filters.
     */
    public function index(Request $request): View|JsonResponse
    {
        $user = Auth::user();

        $query = Client::query();

        if ($user->isLawyer()) {
            $query->whereHas('lawyers', function ($lawyerQuery) use ($user) {
                $lawyerQuery->where('users.id', $user->id);
            });
        }

        // Search by name, phone, or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Optional filter by case count or other fields could be added

        $clients = $query->latest()->paginate(15);

        if ($request->wantsJson()) {
            return response()->json($clients);
        }

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create(): View|JsonResponse
    {
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Form data not available via API'], 400);
        }

        $lawFirmId = $this->resolveLawFirmId();

        $lawyers = User::query()
            ->where('law_firm_id', $lawFirmId)
            ->where('role', 'lawyer')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('clients.create', compact('lawyers'));
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse|JsonResponse
    {
        $lawFirmId = $this->resolveLawFirmId();
        if (!$lawFirmId) {
            $target = Auth::user()->isAdmin() ? 'support.dashboard' : 'settings';
            return redirect()->route($target)
                ->with('error', 'لا يمكن إضافة عميل قبل تحديد المكتب القانوني.');
        }

        $validated = $request->validated();
        $lawyerIds = array_values(array_unique(array_map('intval', $validated['lawyer_ids'] ?? [])));
        $validated['law_firm_id'] = $lawFirmId;
        unset($validated['lawyer_ids']);

        $client = Client::create($validated);
        $client->lawyers()->sync($lawyerIds);

        if ($request->wantsJson()) {
            $client->load('lawyers');
            return response()->json($client, 201);
        }

        return redirect()->route('clients.index')
                         ->with('success', 'تمت إضافة العميل بنجاح');
    }

    /**
     * Display the specified client with its related cases (including pivot role).
     */
    public function show(Client $client): View|JsonResponse
    {
        $this->authorizeClientAccess($client);

        $client->load(['cases' => function ($query) {
            $query->withPivot('role')->orderBy('created_at', 'desc');
        }, 'lawyers']);

        if (request()->wantsJson()) {
            return response()->json($client);
        }

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client): View|JsonResponse
    {
        $this->authorizeClientAccess($client);

        if (request()->wantsJson()) {
            return response()->json($client);
        }

        $lawyers = User::query()
            ->where('law_firm_id', Auth::user()->law_firm_id)
            ->where('role', 'lawyer')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('clients.edit', compact('client', 'lawyers'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse|JsonResponse
    {
        $this->authorizeClientAccess($client);

        $validated = $request->validated();
        $lawyerIds = array_values(array_unique(array_map('intval', $validated['lawyer_ids'] ?? [])));
        unset($validated['lawyer_ids']);

        $client->update($validated);
        $client->lawyers()->sync($lawyerIds);

        if ($request->wantsJson()) {
            $client->load('lawyers');
            return response()->json($client);
        }

        return redirect()->route('clients.index')
                         ->with('success', 'تم تحديث بيانات العميل بنجاح');
    }

    /**
     * Remove the specified client from storage.
     * Detaches all cases automatically via cascade (if set) or manually.
     */
    public function destroy(Client $client): RedirectResponse|JsonResponse
    {
        $this->authorizeClientAccess($client);

        // If you have foreign keys with ON DELETE SET NULL or CASCADE, you may skip this.
        // But to be safe, we detach related cases.
        $client->cases()->detach();
        $client->lawyers()->detach();

        $client->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Client deleted successfully']);
        }

        return redirect()->route('clients.index')
                         ->with('success', 'تم حذف العميل بنجاح');
    }

    private function authorizeClientAccess(Client $client): void
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->isOwner()) {
            return;
        }

        if ($user->isLawyer() && $client->lawyers()->where('users.id', $user->id)->exists()) {
            return;
        }

        abort(403, 'لا تملك صلاحية الوصول إلى هذا العميل.');
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