<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $cleanNif = $this->filled('nif') ? strtoupper(trim(str_replace([' ', '-'], '', $this->input('nif')))) : null;
        $cleanPhone = $this->filled('phone') ? trim(str_replace([' ', '-', '.'], '', $this->input('phone'))) : null;

        $this->merge([
            'nif' => $cleanNif,
            'phone' => $cleanPhone,
        ]);

        return [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'nif' => ['nullable', 'string', new \App\Rules\ValidNif, Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:50', Rule::unique(User::class)->ignore($this->user()->id)],
            'address' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'father_phone' => ['nullable', 'string', 'max:50'],
            'mother_phone' => ['nullable', 'string', 'max:50'],
            'guardian_phone' => ['nullable', 'string', 'max:50'],
            'joining_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
        ];
    }
}
