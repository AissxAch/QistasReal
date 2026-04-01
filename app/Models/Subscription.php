<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Scopes\LawFirmScope;
use Illuminate\Support\Facades\Auth;

class Subscription extends Model
{
    use HasFactory;

    public const LOCKED_STATUSES = ['expired', 'suspended', 'cancelled', 'canceled'];

    protected $table = 'subscriptions';

    protected $fillable = [
        'law_firm_id', 'plan', 'status', 'starts_at', 'ends_at',
        'trial_ends_at', 'amount', 'currency',
        'contract_number', 'contract_starts_at', 'contract_ends_at',
        'user_limit', 'billing_cycle', 'enterprise_account_name',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'amount' => 'decimal:2',
        'contract_starts_at' => 'datetime',
        'contract_ends_at' => 'datetime',
        'user_limit' => 'integer',
    ];

    // Relationships
    public function lawFirm(): BelongsTo
    {
        return $this->belongsTo(LawFirm::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at && $this->ends_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->isLockedByStatus() || ($this->ends_at ? $this->ends_at->isPast() : false);
    }

    public function isLockedByStatus(): bool
    {
        return in_array(strtolower((string) $this->status), self::LOCKED_STATUSES, true);
    }

    public function daysRemaining(): int
    {
        if (!$this->ends_at) {
            return 0;
        }

        return max(0, now()->diffInDays($this->ends_at, false));
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