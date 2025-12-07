<?php

namespace App\Http\Requests\Admin;

use App\Enums\Admin\User\EnumRole;
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
        $request_method = $this->getMethod();
        $request_role = $this->get('role');

        $is_admin_role = in_array($request_role, EnumRole::getValues('list_admin_role'));

        $role_enum = implode(',', EnumRole::getValues());

        $return_validation = match (true) {
            // admin users
            $is_admin_role && $request_method === 'POST' => [
                'name' => ['required', 'string'],
                'email' => ['required', 'string', 'unique:users,email'],
                'role' => ['required', 'string', "in:$role_enum"],
                'password' => ['required', 'string']
            ],
            $is_admin_role && $request_method === 'PUT' => [
                'name' => ['required', 'string'],
                'email' => ['required', 'string'],
                'role' => ['required', 'string', "in:$role_enum"],
                'reset_password' => ['nullable'],
                'new_password' => ['required_if:reset_password,on']
            ],
            // client users
            !$is_admin_role && $request_method === 'POST' => [
                'name' => ['required', 'string'],
                'email' => ['required', 'string', 'unique:users,email'],
                'role' => ['required', 'string', "in:$role_enum"],
                'password' => ['required', 'string'],
                // profil user
                'nik' => ['required', 'numeric'],
                'ktp' => ['required', 'file', 'mimes:png,jpg,jpeg,webp'],
                'nama_lengkap' => ['required', 'string'],
                'alamat' => ['required', 'string'],
                'nomor_rekening' => ['required', 'numeric'],
                'nomor_telepon' => ['required', 'numeric'],
            ],
            !$is_admin_role && $request_method === 'PUT' => [
                'name' => ['required', 'string'],
                'email' => ['required', 'string'],
                'role' => ['required', 'string', "in:$role_enum"],
                'reset_password' => ['nullable'],
                'new_password' => ['required_if:reset_password,on'],
                // profil user
                'nik' => ['required', 'numeric'],
                'ktp' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp'],
                'nama_lengkap' => ['required', 'string'],
                'alamat' => ['required', 'string'],
                'nomor_rekening' => ['required', 'numeric'],
                'nomor_telepon' => ['required', 'numeric'],
            ],
            default => []
        };

        return $return_validation;
    }
}
