<?php

namespace App\Jobs;

use App\Models\OcrScanJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessCaseOcrJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public OcrScanJob $scanJob,
    ) {}

    public function handle(): void
    {
        $this->scanJob->markProcessing();

        try {
            $filePath = Storage::disk('local')->path($this->scanJob->file_path);

            if (! file_exists($filePath)) {
                $this->scanJob->markFailed('الملف غير موجود.');
                return;
            }

            // ── Call the OCR / AI extraction service ──────────────────
            // For now, this is a placeholder that marks the job done
            // with empty result. Replace with actual OCR integration
            // (e.g. Google Vision, Tesseract, OpenAI, etc.)
            $result = $this->extractData($filePath);

            $this->scanJob->markDone($result);
        } catch (\Throwable $e) {
            Log::error('OCR processing failed', [
                'scan_job_id' => $this->scanJob->id,
                'error'       => $e->getMessage(),
            ]);

            $this->scanJob->markFailed('فشل استخراج البيانات: ' . $e->getMessage());
        }
    }

    /**
     * Extract case data from the uploaded document.
     *
     * TODO: Replace this stub with real OCR/AI integration.
     */
    private function extractData(string $filePath): array
    {
        // Placeholder — returns empty fields so the review form opens
        // and the user can fill everything manually.
        return [
            'case_number'       => '',
            'title'             => '',
            'court'             => '',
            'case_type'         => '',
            'degree'            => 'ابتدائي',
            'priority'          => 'medium',
            'start_date'        => null,
            'next_session_date' => null,
            'fees_total'        => 0,
            'fees_paid'         => 0,
            'description'       => '',
        ];
    }

    public function failed(\Throwable $e): void
    {
        $this->scanJob->markFailed('فشلت المعالجة بعد عدة محاولات: ' . $e->getMessage());
    }
}
