<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class LawFirmScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        if ($user->isAdmin()) {
            $selectedFirmId = session('support_firm_id');
            if (!$selectedFirmId) {
                $builder->whereRaw('1 = 0');
                return;
            }

            $builder->where('law_firm_id', (int) $selectedFirmId);
            return;
        }

        // Critical safety: authenticated users without a firm must not see
        // tenant-scoped records from other firms.
        if (!$user->law_firm_id) {
            $builder->whereRaw('1 = 0');
            return;
        }

        $builder->where('law_firm_id', $user->law_firm_id);
    }
}