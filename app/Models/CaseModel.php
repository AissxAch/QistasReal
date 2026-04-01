<?php

namespace App\Models;

use App\Models\Scopes\LawFirmScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use App\Models\CourtSession;

class CaseModel extends Model
{
    use HasFactory;

    protected $table = 'legal_cases';

    protected $fillable = [
        'law_firm_id',
        'created_by_user_id',
        'assigned_lawyer_id',
        'case_number',
        'title',
        'court',
        'case_type',
        'degree',
        'status',
        'priority',
        'fees_total',
        'fees_paid',
        'fees_remaining',   // DB column — always set by controller (fees_total - fees_paid)
        'description',
        'start_date',        // FIX: was missing
        'next_session_date', // FIX: was missing
    ];

    protected $casts = [
        'start_date'        => 'date',
        'next_session_date' => 'date',
        'fees_total'        => 'decimal:2',
        'fees_paid'         => 'decimal:2',
        'fees_remaining'    => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new LawFirmScope);

        static::creating(function ($model) {
            if (Auth::check() && !$model->law_firm_id) {
                $user = Auth::user();
                $model->law_firm_id = $user->isAdmin()
                    ? (session('support_firm_id') ? (int) session('support_firm_id') : null)
                    : $user->law_firm_id;
            }
        });
    }

    // Relationships
    public function lawFirm()
    {
        return $this->belongsTo(LawFirm::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function assignedLawyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_lawyer_id');
    }

    public function lawyers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'case_lawyer', 'case_id', 'user_id')->withTimestamps();
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'case_client', 'case_id', 'client_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(CourtSession::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // FIX: Removed getFeesRemainingAttribute() accessor — it conflicted with the
    // real DB column. fees_remaining is now always computed and stored by the
    // controller (fees_total - fees_paid), and read directly from the DB column.
}
