<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'law_firm_id', 'user_id', 'type', 'title', 'body', 'data', 'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lawFirm(): BelongsTo
    {
        return $this->belongsTo(LawFirm::class);
    }

    // Scope for unread notifications
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }
}
