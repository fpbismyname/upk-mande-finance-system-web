<?php

namespace App\Services\Admin\Settings;

use App\Models\SukuBungaFlat;
use App\Models\User;
use App\Services\Utils\Result;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    public function update_user($datas, $reset_password)
    {
        $updated_current_user = DB::transaction(function () use ($reset_password, $datas) {
            $current_user = User::query()->where('email', Auth::user()->email)->first();
            if ($reset_password) {
                $current_user->resetPassword($datas['new_password']);
            }
            $current_user->fill($datas);
            $current_user->save();
            return $current_user;
        });
        // Logout if email updated
        if (Auth::user()->email !== $datas['email']) {
            Auth::logout();
        }
        // Validation update data
        if ($updated_current_user) {
            return Result::success(__('crud.update_success', ['item' => $updated_current_user->name]));
        }
        return Result::error(__('crud.update_failed', ['item' => $updated_current_user->name]));
    }
    public function update_sukubunga($datas)
    {
        $updated_sukubunga = DB::transaction(function () use ($datas) {
            $current_sukubunga = SukuBungaFlat::first();
            $current_sukubunga->jumlah = $datas['jumlah'];
            $current_sukubunga->save();
            return $current_sukubunga;
        });
        if ($updated_sukubunga) {
            return Result::success(__('crud.update_success', ['item' => 'Suku bunga']));
        }
        return Result::error(__('crud.update_failed', ['item' => 'Suku bunga']));
    }
}