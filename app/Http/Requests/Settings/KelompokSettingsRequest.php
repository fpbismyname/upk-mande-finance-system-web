<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class KelompokSettingsRequest extends FormRequest
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
            'minimal_anggota_kelompok' => 'required|numeric|min:1|max:49|lt:maksimal_anggota_kelompok',
            'maksimal_anggota_kelompok' => 'required|numeric|min:2|max:50|gt:minimal_anggota_kelompok',
        ];
    }
}
