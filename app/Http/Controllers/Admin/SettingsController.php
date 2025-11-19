<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\Settings\EnumSettingGroup;
use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\AccountSettingsRequest;
use App\Http\Requests\Settings\CicilanSettingsRequest;
use App\Http\Requests\Settings\KelompokSettingsRequest;
use App\Http\Requests\Settings\PinjamanSettingsRequest;
use App\Models\Settings;
use App\Models\User;
use App\Services\UI\Toast;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * View settings
     */
    public function index(Settings $settings_model)
    {
        // Settings user
        $data_user = auth()->user();
        // Settings pinjaman
        $data_pinjaman = Settings::getGroupSetting(EnumSettingGroup::PINJAMAN)->get()->flatMap(function ($data) {
            return [
                $data->key->value => $data->value,
            ];
        });
        // Settings kelompok
        $data_kelompok = Settings::getGroupSetting(EnumSettingGroup::KELOMPOK)->get()->flatMap(function ($data) {
            return [
                $data->key->value => $data->value,
            ];
        });

        // dd($data_pinjaman);
        $payload = compact('data_user', 'data_kelompok', 'data_pinjaman');
        return view('admin.settings.index', $payload);
    }

    /**
     * Actions Setting
     */
    public function account_save_changes(AccountSettingsRequest $request, User $user_model)
    {
        // Validate input data user
        $user_data = $request->validated();
        $is_reset_pass = isset($user_data['reset_password']) ? $user_data['reset_password'] == 'on' : false;
        $new_user_pass = $user_data['new_password'] ?? '';

        // Update data user
        $data_user = $request->only($user_model->getFillable());
        $update_user = $user_model->findOrFail(auth()->user()->id);
        $update_user->update($data_user);

        // Validasi akun pengguna yang ditambahkan
        if ($update_user->wasChanged() && !$is_reset_pass) {
            Toast::success('Akun anda berhasil diperbarui.');
        } elseif (empty($update_user->getChanges())) {
            Toast::info('Tidak ada perubahan pada akun pengguna.');
        } else {
            Toast::error('Akun anda gagal diperbarui.');
        }

        // reset jika input mengizinkan reset password
        if ($is_reset_pass) {
            $update_user->resetPassword($new_user_pass);
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            Toast::error('Silahkan login kembali dengan password baru anda.');
        }

        return redirect()->back();
    }
    public function pinjaman_save_changes(PinjamanSettingsRequest $request, Settings $settings_model)
    {
        $data_settings = $request->validated();
        foreach ($data_settings as $key => $value) {
            $item = $settings_model->getKeySetting(EnumSettingKeys::from($key))->first();
            $item->update(['value' => $value]);
        }
        Toast::success('Pengaturan pinjaman berhasil diperbarui.');
        return redirect()->back();
    }
    public function kelompok_save_changes(KelompokSettingsRequest $request, Settings $settings_model)
    {
        $data_settings = $request->validated();
        foreach ($data_settings as $key => $value) {
            $item = $settings_model->getKeySetting(EnumSettingKeys::from($key))->first();
            $item->update(['value' => $value]);
        }
        Toast::success('Pengaturan kelompok berhasil diperbarui.');
        return redirect()->back();
    }
}
