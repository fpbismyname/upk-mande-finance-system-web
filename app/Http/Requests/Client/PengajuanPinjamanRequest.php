<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanPinjamanRequest extends FormRequest
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
        $method_request = $this->method();

        return match ($method_request) {
            'PUT' => [
                'nominal_pinjaman' => 'required',
                'file_proposal' => ['file', 'mimes:pdf', 'max:102400'],
                'tenor' => 'required'
            ],
            default => [
                'nominal_pinjaman' => 'required',
                'file_proposal' => ['required', 'file', 'mimes:pdf', 'max:102400'],
                'tenor' => 'required'
            ],
        };
    }
}
