<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Scopes\LawFirmScope;
use Illuminate\Support\Facades\Auth;
class CourtSession extends Model
{
    use HasFactory;
    protected $table = 'court_sessions';
    protected $fillable = [
        'law_firm_id', 'case_id', 'session_date', 'session_time', 'court',
        'room', 'notes', 'status',
    ];

    protected $casts = [
        'session_date' => 'date',
        'session_time' => 'datetime:H:i',
    ];

    // Relationships
    public function lawFirm(): BelongsTo
    {
        return $this->belongsTo(LawFirm::class);
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(CaseModel::class);
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