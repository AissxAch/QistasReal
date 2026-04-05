<?php

namespace App\Models;

use App\Models\Scopes\LawFirmScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OcrScanJob extends Model
{
    protected $table = 'ocr_scan_jobs';

    protected $fillable = [
        'law_firm_id',
        'user_id',
        'file_path',
        'original_name',
        'mime_type',
        'status',
        'result',
        'error_message',
    ];

    protected $casts = [
        'result' => 'array',
    ];

    // Status helpers
    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isProcessing(): bool { return $this->status === 'processing'; }
    public function isDone(): bool       { return $this->status === 'done'; }
    public function isFailed(): bool     { return $this->status === 'failed'; }

    public function markProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    public function markDone(array $result): void
    {
        $this->update(['status' => 'done', 'result' => $result]);
    }

    public function markFailed(string $message): void
    {
        $this->update(['status' => 'failed', 'error_message' => $message]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lawFirm()
    {
        return $this->belongsTo(LawFirm::class);
    }
}
