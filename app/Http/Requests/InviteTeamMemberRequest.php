<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InviteTeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:lawyer,assistant'],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialty' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم العضو مطلوب',
            'name.max' => 'اسم العضو يجب ألا يتجاوز 255 حرفًا',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'role.required' => 'الدور مطلوب',
            'role.in' => 'الدور المختار غير صالح',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 حرفًا',
            'specialty.max' => 'التخصص يجب ألا يتجاوز 255 حرفًا',
        ];
    }
}
