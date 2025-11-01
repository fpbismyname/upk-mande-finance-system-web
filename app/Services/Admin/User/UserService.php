<?php

namespace App\Services\Admin\User;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }
    public function addUser($datas)
    {
        if (empty($datas)) {
            return false;
        }
        return DB::transaction(function () use ($datas) {
            $createUser = $this->model->create($datas);
            return $createUser->wasRecentlyCreated;
        });
    }
    public function updateUser($id_user, $datas)
    {
        if (empty($datas) || empty($id_user)) {
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
            'role_id' => $datas['role_id'],
            'nomor_telepon' => $datas['nomor_telepon'],
        ];
        if ($is_reset_pass) {
            return DB::transaction(function () use ($id_user, $data_user, $new_user_pass) {
                $currentUser = $this->model->findOrFail($id_user);
                $updateUser = $currentUser->update($data_user);
                $currentUser->resetPassword($new_user_pass);
                return $updateUser;
            });
        } else {
            return DB::transaction(function () use ($id_user, $datas) {
                $currentUser = $this->model->findOrFail($id_user);
                $updateUser = $currentUser->update($datas);
                return $updateUser;
            });
        }
    }
    public function deleteUser($id)
    {
        if (empty($id)) {
            return false;
        }
        return DB::transaction(function () use ($id) {
            $deleteUser = $this->model->findOrFail($id)->delete();
            return $deleteUser;
        });
    }
}