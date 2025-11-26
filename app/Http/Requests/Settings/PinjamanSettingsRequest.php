<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class PinjamanSettingsRequest extends FormRequest
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
            'bunga_pinjaman' => 'required|numeric',
            'minimal_limit_pinjaman' => 'required|numeric|min:1000000|max:50000000|lt:maksimal_limit_pinjaman',
            'maksimal_limit_pinjaman' => 'required|numeric|min:1000000|max:50000000|gt:minimal_limit_pinjaman',
            'kenaikan_limit_per_jumlah_pinjaman' => 'required|numeric',
            'toleransi_telat_bayar' => 'required|numeric|min:1|max:240',
        ];
    }
}
