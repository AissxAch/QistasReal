<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $lawFirmId = $this->resolveLawFirmId();

        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'case_id' => ['nullable', 'exists:legal_cases,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'lawyer_ids' => ['nullable', 'array'],
            'lawyer_ids.*' => [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) use ($lawFirmId) {
                    $query->where('law_firm_id', $lawFirmId)->where('role', 'lawyer');
                }),
            ],
            'due_date' => ['nullable', 'date'],
            'due_time' => ['nullable', 'date_format:H:i'],
            'priority' => ['sometimes', 'required', 'in:low,medium,high'],
            'status' => ['sometimes', 'required', 'in:pending,in_progress,done'],
        ];
    }

    private function resolveLawFirmId(): ?int
    {
        $user = $this->user();

        if ($user?->isAdmin()) {
            return session('support_firm_id') ? (int) session('support_firm_id') : null;
        }

        return $user?->law_firm_id ? (int) $user->law_firm_id : null;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان المهمة مطلوب',
            'title.max' => 'عنوان المهمة يجب ألا يتجاوز 255 حرفًا',
            'description.string' => 'الوصف يجب أن يكون نصًا',
            'case_id.exists' => 'القضية المختارة غير صالحة',
            'assigned_to.exists' => 'المستخدم المختار غير صالح',
            'due_date.date' => 'تاريخ الاستحقاق غير صالح',
            'due_time.date_format' => 'وقت الاستحقاق يجب أن يكون بصيغة HH:MM',
            'priority.required' => 'الأولوية مطلوبة',
            'priority.in' => 'الأولوية غير صالحة',
            'status.required' => 'الحالة مطلوبة',
            'status.in' => 'الحالة غير صالحة',
        ];
    }
}
