<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Scopes\LawFirmScope;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'law_firm_id', 'subscription_id', 'amount', 'currency',
        'payment_method', 'status', 'transaction_id', 'payment_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_data' => 'array',
    ];

    // Relationships
    public function lawFirm(): BelongsTo
    {
        return $this->belongsTo(LawFirm::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
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