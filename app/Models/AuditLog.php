<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Scopes\LawFirmScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'law_firm_id', 'user_id', 'action', 'model_type',
        'model_id', 'old_values', 'new_values', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relationships
    public function lawFirm(): BelongsTo
    {
        return $this->belongsTo(LawFirm::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        // Add the global scope to automatically filter by law_firm_id
        // NOTE: Do NOT auto-set law_firm_id on creating
        // law_firm_id must always be set explicitly by the code that creates the audit log
        static::addGlobalScope(new LawFirmScope);
    }

    public static function record(
        ?User $actor,
        string $action,
        string $modelType,
        ?int $modelId,
        ?int $lawFirmId,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): self {
        $request = app()->bound('request') ? app(Request::class) : null;

        return static::withoutGlobalScopes()->create([
            'law_firm_id' => $lawFirmId,
            'user_id' => $actor?->id,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}