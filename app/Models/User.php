<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name', 'email', 'password', 'law_firm_id', 'role', 'phone', 'specialty', 'bio',
        'invited_by_user_id', 'invited_at', 'invitation_expires_at', 'activated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'invited_at' => 'datetime',
        'invitation_expires_at' => 'datetime',
        'activated_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    // Relationships
    public function lawFirm(): BelongsTo
    {
        return $this->belongsTo(LawFirm::class);
    }

    public function tasksAssigned(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdCases(): HasMany
    {
        return $this->hasMany(CaseModel::class, 'created_by_user_id');
    }

    public function assignedCases(): HasMany
    {
        return $this->hasMany(CaseModel::class, 'assigned_lawyer_id');
    }

    public function assignedCasesMany(): BelongsToMany
    {
        return $this->belongsToMany(CaseModel::class, 'case_lawyer', 'user_id', 'case_id')->withTimestamps();
    }

    public function assignedClientsMany(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'client_lawyer', 'user_id', 'client_id')->withTimestamps();
    }

    public function assignedTasksMany(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_lawyer', 'user_id', 'task_id')->withTimestamps();
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by_user_id');
    }

    // Role helpers
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isLawyer(): bool
    {
        return $this->role === 'lawyer';
    }

    public function isAssistant(): bool
    {
        return $this->role === 'assistant';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasPendingInvitation(): bool
    {
        return $this->invited_at !== null
            && $this->activated_at === null
            && $this->invitation_expires_at !== null
            && $this->invitation_expires_at->isFuture();
    }
}