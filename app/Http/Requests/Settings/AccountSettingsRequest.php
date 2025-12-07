<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class AccountSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'nik' => 'nullable',
            'ktp' => 'nullable',
            'alamat' => 'nullable',
            'nama_lengkap' => 'nullable',
            'nomor_rekening' => 'nullable',
            'nomor_telepon' => 'nullable',
            'reset_password' => 'nullable',
            'new_password' => ['required_if:reset_password,on'],
        ];
    }
}
