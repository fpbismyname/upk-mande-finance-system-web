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
        $request_method = $this->getMethod();

        $request_validation = [
            'nik' => ['required', 'numeric'],
            'nama_lengkap' => ['required', 'string'],
            'alamat' => ['required', 'string'],
            'nomor_rekening' => ['required', 'numeric'],
            'nomor_telepon' => ['required', 'numeric'],
        ];

        if ($request_method === 'POST') {
            $request_validation['ktp'] = ['required', 'file', 'mimes:png,jpg,jpeg,webp'];
        }
        if ($request_method === 'PUT') {
            $request_validation['ktp'] = ['nullable', 'file', 'mimes:png,jpg,jpeg,webp'];
        }

        return $request_validation;
    }
}
