<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Scopes\LawFirmScope;
use Illuminate\Support\Facades\Auth;
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'law_firm_id', 'case_id', 'assigned_to', 'title', 'description',
        'due_date', 'due_time', 'priority', 'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'due_time' => 'datetime:H:i',
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

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function lawyers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_lawyer', 'task_id', 'user_id')->withTimestamps();
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