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
            'nik' => '',
            'alamat' => '',
            'name' => 'required',
            'email' => 'required',
            'nomor_rekening' => '',
            'nomor_telepon' => 'required',
            'reset_password' => '',
            'new_password' => ['required_if:reset_password,on'],
        ];
    }
}
