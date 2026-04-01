<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'case_id' => ['required', 'exists:legal_cases,id'],
            'session_date' => ['required', 'date'],
            'session_time' => ['required', 'date_format:H:i'],
            'court' => ['required', 'string', 'max:255'],
            'room' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:scheduled,done,postponed,cancelled'],
        ];
    }

    public function messages(): array
    {
        return [
            'case_id.required' => 'اختيار القضية مطلوب',
            'session_date.required' => 'تاريخ الجلسة مطلوب',
            'session_time.required' => 'وقت الجلسة مطلوب',
            'court.required' => 'اسم المحكمة مطلوب',
            'status.required' => 'حالة الجلسة مطلوبة',
        ];
    }
}
