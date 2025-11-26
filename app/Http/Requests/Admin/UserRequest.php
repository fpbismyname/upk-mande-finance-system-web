<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'nik' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'nomor_telepon' => 'required',
            'nomor_rekening' => 'required',
            'alamat' => 'required',
            'role' => 'required'
        ];
    }
}
