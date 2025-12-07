<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanKeanggotaanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nik' => ['required', 'numeric'],
            'ktp' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp'],
            'nama_lengkap' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'nomor_rekening' => ['required', 'numeric'],
            'nomor_telepon' => ['required', 'numeric'],
        ];
    }
}
