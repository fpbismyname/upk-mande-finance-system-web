<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\UI\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function save_changes(UpdateUserRequest $request, User $user_model)
    {
        // Data form input
        $datas = $request->validated();

        // is request reset password
        $is_reset_pass = isset($datas['reset_password']) ? $datas['reset_password'] == 'on' : false;

        // New password
        $new_user_pass = $datas['new_password'] ?? '';

        // Data user
        $data_user = $request->only($user_model->getFillable());

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
    }
}
