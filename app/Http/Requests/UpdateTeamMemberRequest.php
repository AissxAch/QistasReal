<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'role' => ['sometimes', 'required', 'in:owner,lawyer,assistant'],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialty' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم العضو مطلوب',
            'name.max' => 'اسم العضو يجب ألا يتجاوز 255 حرفًا',
            'role.required' => 'الدور مطلوب',
            'role.in' => 'الدور المختار غير صالح',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 حرفًا',
            'specialty.max' => 'التخصص يجب ألا يتجاوز 255 حرفًا',
        ];
    }
}
