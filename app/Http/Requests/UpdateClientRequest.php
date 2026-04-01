<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $lawFirmId = $this->resolveLawFirmId();

        return [
            'name'  => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'id_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'lawyer_ids' => 'nullable|array',
            'lawyer_ids.*' => [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) use ($lawFirmId) {
                    $query->where('law_firm_id', $lawFirmId)->where('role', 'lawyer');
                }),
            ],
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
            'name.required' => 'اسم العميل مطلوب',
            'email.email'   => 'البريد الإلكتروني غير صحيح',
        ];
    }
}