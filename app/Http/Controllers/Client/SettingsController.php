<?php

namespace App\Http\Controllers\Client;

use App\Enums\Admin\Status\EnumStatusPengajuanKeanggotaan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Requests\Settings\AccountSettingsRequest;
use App\Models\PengajuanKeanggotaan;
use App\Models\User;
use App\Services\UI\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $current_user = auth()->user();
        $payload = compact('current_user');
        return view('client.settings.index', $payload);
    }
    public function save_changes(AccountSettingsRequest $request, User $user_model, PengajuanKeanggotaan $pengajuan_keanggotaan_model)
    {
        // Data form input
        $datas = $request->validated();

        // is request reset password
        $is_reset_pass = isset($datas['reset_password']) ? $datas['reset_password'] == 'on' : false;

        // New password
        $new_user_pass = $datas['new_password'] ?? '';

        // Data user
        $data_user = $request->only($user_model->getFillable());

        // Data pengajuan keanggotaan
        $entries_data_pengajuan_keanggotaan = $request->only($pengajuan_keanggotaan_model->getFillable());

        // Update data user
        $user = $update_user = $user_model->findOrFail(auth()->user()->id);
        $data_pengajuan_keanggotaan = $user->pengajuan_keanggotaan_disetujui()->first();


        // reset jika input mengizinkan reset password
        if ($is_reset_pass) {
            $update_user->password = Hash::make($new_user_pass);
        }

        // Update data pengajuan
        if (!empty($entries_data_pengajuan_keanggotaan)) {
            // Upload files
            $files = $request->only('ktp');
            $uploaded_file_path = [];
            $storage_private = Storage::disk('local');
            foreach ($files as $key => $value) {
                $file = $value;
                if ($file) {
                    $ext_file = $file->getClientOriginalExtension();
                    $safe_username = Str::snake($entries_data_pengajuan_keanggotaan['nama_lengkap']);
                    $today = now()->clone()->format('d-m-y');
                    $file_name = "{$key}_{$safe_username}_{$today}.{$ext_file}";
                    if ($storage_private->exists($file_name)) {
                        $storage_private->delete($file_name);
                    }
                    $uploaded_file_path[$key] = $storage_private->putFileAs('users', $file, $file_name);
                }
            }
            foreach ($uploaded_file_path as $key => $path) {
                $entries_data_pengajuan_keanggotaan[$key] = $path;
            }
            $data_pengajuan_keanggotaan->update($entries_data_pengajuan_keanggotaan);
        }

        // Update data user
        $update_user = $user->update($data_user);

        // Validasi akun pengguna yang ditambahkan
        if ($update_user) {
            Toast::success('Akun pengguna berhasil diperbarui.');
        } else {
            Toast::error('Akun pengguna gagal diperbarui.');
        }
        return redirect()->back();
    }
}
