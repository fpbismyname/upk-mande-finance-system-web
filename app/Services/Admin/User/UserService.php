<?php

namespace App\Services\Admin\User;
use App\Models\User;
use App\Services\Utils\Result;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function addUser($datas)
    {
        if (empty($datas)) {
            return false;
        }
        $added_user = DB::transaction(function () use ($datas) {
            return User::create($datas);
        });

        if ($added_user->wasRecentlyCreated) {
            return Result::success(__('crud.create_success', ['item' => $added_user->name]));
        }
        return Result::error(__('crud.create_failed', ['item' => $added_user->name]));
    }
    public function updateUser($id, $datas)
    {
        if (empty($datas) || empty($id)) {
            return false;
        }
        // is request reset password
        $is_reset_pass = isset($datas['reset_password']) ? $datas['reset_password'] == 'on' : false;

        // Data User
        $new_user_pass = $datas['new_password'];
        $data_user = [
            'nik' => $datas['nik'],
            'alamat' => $datas['alamat'],
            'name' => $datas['name'],
            'email' => $datas['email'],
            'role' => $datas['role'],
            'nomor_telepon' => $datas['nomor_telepon'],
        ];
        // Update User
        $updated_user = DB::transaction(function () use ($is_reset_pass, $data_user, $new_user_pass, $id) {
            $current_user = User::findOrFail($id);
            // reset jika input mengizinkan reset password
            if ($is_reset_pass) {
                $current_user->resetPassword($new_user_pass);
            }
            $current_user->fill($data_user);
            $current_user->save();
            return $current_user;
        });

        if ($updated_user) {
            return Result::success(__('crud.update_success', ['item' => $updated_user->name]));
        }

        return Result::error(__('crud.update_failed', ['item' => $updated_user->name]));
    }
    public function deleteUser($id)
    {
        if (empty($id)) {
            return false;
        }
        // Delete user
        $deleted_user = DB::transaction(function () use ($id) {
            $current_user = User::findOrFail($id);
            $current_user->delete();
            return $current_user;
        });

        if ($deleted_user) {
            return Result::success(__('crud.delete_success', ['item' => $deleted_user->name]));
        } else {
            return Result::error(__('crud.delete_success', ['item' => $deleted_user->name]));
        }
    }
}