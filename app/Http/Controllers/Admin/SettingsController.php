<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\User;
use App\Services\UI\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Settings $settings_model)
    {
        $current_user = auth()->user();
        $pinjaman_settings = [
            EnumSettingKeys::BUNGA_PINJAMAN->value => $settings_model->getKeySetting(EnumSettingKeys::BUNGA_PINJAMAN)->value('value') ?? 0,
            EnumSettingKeys::LIMIT_PINJAMAN_MAKSIMAL->value => $settings_model->getKeySetting(EnumSettingKeys::LIMIT_PINJAMAN_MAKSIMAL)->value('value') ?? 0,
            EnumSettingKeys::KENAIKAN_LIMIT_PER_JUMLAH_PINJAMAN->value => $settings_model->getKeySetting(EnumSettingKeys::KENAIKAN_LIMIT_PER_JUMLAH_PINJAMAN)->value('value')
        ];
        $cicilan_settings = [
            EnumSettingKeys::DENDA_TELAT_BAYAR->value => $settings_model->getKeySetting(EnumSettingKeys::DENDA_TELAT_BAYAR)->value('value') ?? 0,
            EnumSettingKeys::TOLERANSI_TELAT_BAYAR->value => $settings_model->getKeySetting(EnumSettingKeys::TOLERANSI_TELAT_BAYAR)->value('value') ?? 0,
        ];
        $payload = compact('current_user', 'pinjaman_settings', 'cicilan_settings');
        return view('admin.settings.index', $payload);
    }

    public function save_changes(Request $request, Settings $settings_model, User $user_model)
    {
        $type_settings = request()->get('type_settings');
        switch ($type_settings) {
            // User settings
            case 'informasi_akun':
                // Data form input
                $datas = $request->validate([
                    'name' => 'required',
                    'email' => 'required',
                    'nomor_telepon' => 'required',
                    'reset_password' => '',
                    'new_password' => ['required_if:reset_password,on'],
                ]);

                // is request reset password
                $is_reset_pass = isset($datas['reset_password']) ? $datas['reset_password'] == 'on' : false;

                // New password
                $new_user_pass = $datas['new_password'] ?? '';

                // Data user
                $data_user = $request->only(['nik', 'alamat', 'name', 'email', 'role', 'nomor_telepon']);

                // Update data user
                $update_user = $user_model->findOrFail(auth()->user()->id);

                // Update data user
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

            // Pinjaman settings
            case 'pinjaman':
                $new_entries = $request->validate([
                    'bunga_pinjaman' => 'required',
                    'kenaikan_limit_per_jumlah_pinjaman' => 'required',
                    'limit_pinjaman_maksimal' => 'required'
                ]);
                foreach ($new_entries as $key => $value) {
                    $update_settings = $settings_model->where('key', $key);
                    $update_settings->update(['value' => $value]);
                }
                Toast::success("Pengaturan pinjaman berhasil diperbarui.");
                return redirect()->back();

            // Cicilan settings
            case 'cicilan':
                $new_entries = $request->validate([
                    'toleransi_telat_bayar' => 'required',
                    'denda_telat_bayar' => 'required',
                ]);
                foreach ($new_entries as $key => $value) {
                    $update_settings = $settings_model->where('key', $key);
                    $update_settings->update(['value' => $value]);
                }
                Toast::success("Pengaturan cicilan berhasil diperbarui.");
                return redirect()->back();
        }
    }
}
