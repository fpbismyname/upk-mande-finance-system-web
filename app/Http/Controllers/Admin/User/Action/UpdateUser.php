<?php

namespace App\Http\Controllers\Admin\User\Action;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\User\UserService;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

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
            'role' => 'required',
            'reset_password' => '',
            'new_password' => '',
        ]);
        // Update user data
        $result = $user_service->updateUser($id, $new_entries);
        // validasi hasil update
        Toast::show($result->message, $result->type_message);
        return redirect()->back();
    }
}
