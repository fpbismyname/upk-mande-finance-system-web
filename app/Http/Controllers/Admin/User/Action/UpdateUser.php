<?php

namespace App\Http\Controllers\Admin\User\Action;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\User\UserService;
use App\Services\UI\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateUser extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id, UserService $user_service)
    {
        // Validasi pembaruan data user
        $new_entries = $request->validate([
            'nik' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'alamat' => 'required',
            'nomor_telepon' => 'required',
            'role_id' => 'required',
            'reset_password' => '',
            'new_password' => '',
        ]);
        // Update user data
        $username = $new_entries['name'];
        $update_user = $user_service->updateUser($id, $new_entries);
        // cek validasi pembaruan data
        if ($update_user) {
            // data berhasil di perbarui
            Toast::show(__('crud.update_success', ['item' => $username]), 'success');
            return redirect()->route('admin.users.index');
        }
        // data gagal di perbarui
        Toast::show(__('crud.update_failed'), 'error');
        return redirect()->back();
    }
}
