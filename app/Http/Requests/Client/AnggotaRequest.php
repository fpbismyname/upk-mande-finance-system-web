<?php

namespace App\Http\Requests\Client;

use App\Enums\Admin\User\EnumRole;
use Illuminate\Foundation\Http\FormRequest;

class AnggotaRequest extends FormRequest
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
            'nik' => 'required',
            'name' => 'required',
            'nomor_telepon' => 'required',
            'alamat' => 'required'
        ];
    }
}
