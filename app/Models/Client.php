<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Scopes\LawFirmScope;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'law_firm_id', 'name', 'phone', 'email', 'address', 'id_number', 'notes',
    ];

    // Relationships
    public function lawFirm(): BelongsTo
    {
        return $this->belongsTo(LawFirm::class);
    }

    public function cases(): BelongsToMany
    {
        return $this->belongsToMany(CaseModel::class, 'case_client', 'client_id', 'case_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function lawyers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_lawyer', 'client_id', 'user_id')->withTimestamps();
    }
    protected static function booted(): void
    {
        // Add the global scope to automatically filter by law_firm_id
        static::addGlobalScope(new LawFirmScope);

        // Automatically set law_firm_id when creating a new record
        static::creating(function ($model) {
            if (Auth::check() && !$model->law_firm_id) {
                $user = Auth::user();
                $model->law_firm_id = $user->isAdmin()
                    ? (session('support_firm_id') ? (int) session('support_firm_id') : null)
                    : $user->law_firm_id;
            }
        });
    }
}