<?php

namespace App\Livewire;

use App\Models\CaseModel;
use App\Models\OcrScanJob;
use App\Jobs\ProcessCaseOcrJob;
use App\Services\NotificationTemplateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CaseScanner extends Component
{
    use WithFileUploads;

    // ── Wizard step ──────────────────────────────────────────────────
    public string $step = 'upload'; // upload → processing → review → done

    // ── Upload step ──────────────────────────────────────────────────
    public $file;
    public string $errorMessage = '';

    // ── Processing step ──────────────────────────────────────────────
    public ?int $jobId = null;
    public int $pollCount = 0;
    public ?string $previewUrl = null;

    // ── Review step (extracted fields) ───────────────────────────────
    public string $case_number = '';
    public string $title = '';
    public string $court = '';
    public string $case_type = '';
    public string $degree = 'ابتدائي';
    public string $priority = 'medium';
    public ?string $start_date = null;
    public ?string $next_session_date = null;
    public float $fees_total = 0;
    public float $fees_paid = 0;
    public string $description = '';

    // ─────────────────────────────────────────────────────────────────
    //  UPLOAD — validate file, store, dispatch OCR job
    // ─────────────────────────────────────────────────────────────────
    public function upload(): void
    {
        $this->validate([
            'file' => ['required', 'file', 'max:20480', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        try {
            $path = $this->file->store('ocr-uploads', 'local');

            $user = Auth::user();

            $job = OcrScanJob::create([
                'law_firm_id'   => $user->law_firm_id,
                'user_id'       => $user->id,
                'file_path'     => $path,
                'original_name' => $this->file->getClientOriginalName(),
                'mime_type'     => $this->file->getMimeType(),
                'status'        => 'pending',
            ]);

            $this->jobId = $job->id;

            // Build a preview URL for images
            $mime = $this->file->getMimeType();
            if (str_starts_with($mime, 'image/')) {
                $this->previewUrl = $this->file->temporaryUrl();
            }

            ProcessCaseOcrJob::dispatch($job);

            $this->pollCount = 0;
            $this->step = 'processing';
        } catch (\Throwable $e) {
            $this->errorMessage = 'حدث خطأ أثناء رفع الملف: ' . $e->getMessage();
        }
    }

    // ─────────────────────────────────────────────────────────────────
    //  POLLING — check if the OCR job finished
    // ─────────────────────────────────────────────────────────────────
    public function checkJobStatus(): void
    {
        $this->pollCount++;

        if (! $this->jobId) {
            return;
        }

        $job = OcrScanJob::find($this->jobId);

        if (! $job) {
            $this->errorMessage = 'لم يتم العثور على مهمة المعالجة.';
            $this->step = 'upload';
            return;
        }

        if ($job->isDone()) {
            $this->hydrateFromResult($job->result ?? []);
            $this->step = 'review';
        } elseif ($job->isFailed()) {
            $this->errorMessage = $job->error_message ?: 'فشلت عملية استخراج البيانات. حاول مرة أخرى.';
            $this->step = 'upload';
        }
        // else still pending/processing — keep polling
    }

    // ─────────────────────────────────────────────────────────────────
    //  SAVE — create the case from reviewed fields
    // ─────────────────────────────────────────────────────────────────
    public function saveCase(): void
    {
        $this->validate([
            'case_number' => ['required', 'string', 'max:100'],
            'court'       => ['required', 'string', 'max:255'],
            'degree'      => ['required', 'string'],
        ]);

        $user = Auth::user();
        $lawFirmId = $user->law_firm_id;

        if (! $lawFirmId) {
            $this->errorMessage = 'لا يمكن إنشاء قضية بدون مكتب محاماة.';
            return;
        }

        $feesTotal     = max(0, (float) $this->fees_total);
        $feesPaid      = max(0, (float) $this->fees_paid);
        $feesRemaining = max(0, $feesTotal - $feesPaid);

        try {
            $case = CaseModel::create([
                'law_firm_id'        => $lawFirmId,
                'created_by_user_id' => $user->id,
                'case_number'        => $this->case_number,
                'title'              => $this->title ?: null,
                'court'              => $this->court,
                'case_type'          => $this->case_type ?: null,
                'degree'             => $this->degree,
                'status'             => 'active',
                'priority'           => $this->priority ?: 'medium',
                'fees_total'         => $feesTotal,
                'fees_paid'          => $feesPaid,
                'fees_remaining'     => $feesRemaining,
                'description'        => $this->description ?: null,
                'start_date'         => $this->start_date ?: null,
                'next_session_date'  => $this->next_session_date ?: null,
            ]);

            // Fire notification
            if (class_exists(NotificationTemplateService::class)) {
                NotificationTemplateService::caseCreated($case);
            }

            $this->step = 'done';

            // Redirect after a brief flash
            $this->redirect(route('cases.show', $case), navigate: true);
        } catch (\Throwable $e) {
            $this->errorMessage = 'حدث خطأ أثناء حفظ القضية: ' . $e->getMessage();
        }
    }

    // ─────────────────────────────────────────────────────────────────
    //  RESET — back to upload step
    // ─────────────────────────────────────────────────────────────────
    public function resetScanner(): void
    {
        $this->reset([
            'step', 'file', 'errorMessage', 'jobId', 'pollCount', 'previewUrl',
            'case_number', 'title', 'court', 'case_type', 'degree', 'priority',
            'start_date', 'next_session_date', 'fees_total', 'fees_paid', 'description',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    //  RENDER
    // ─────────────────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.case-scanner');
    }

    // ─────────────────────────────────────────────────────────────────
    //  PRIVATE — fill review fields from OCR result
    // ─────────────────────────────────────────────────────────────────
    private function hydrateFromResult(array $data): void
    {
        $this->case_number      = $data['case_number']      ?? '';
        $this->title            = $data['title']             ?? '';
        $this->court            = $data['court']             ?? '';
        $this->case_type        = $data['case_type']         ?? '';
        $this->degree           = $data['degree']            ?? 'ابتدائي';
        $this->priority         = $data['priority']          ?? 'medium';
        $this->start_date       = $data['start_date']        ?? null;
        $this->next_session_date = $data['next_session_date'] ?? null;
        $this->fees_total       = (float) ($data['fees_total'] ?? 0);
        $this->fees_paid        = (float) ($data['fees_paid']  ?? 0);
        $this->description      = $data['description']       ?? '';
    }
}
