<?php

namespace App\Http\Controllers\Admin\User\Action;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\User\UserService;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class AddUser extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, UserService $user_service)
    {
        $new_entries = $request->validate([
            'nik' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'nomor_telepon' => 'required',
            'alamat' => 'required',
            'role' => 'required'
        ]);

        $username = $new_entries['name'];
        $add_new_user = $user_service->addUser($new_entries);

        if (!$add_new_user) {
            Toast::show(__('crud.create_failed', ['item' => $username]));
            return redirect()->route('admin.users.index');
        }
        Toast::show(__('crud.create_success', ['item' => $username]));
        return redirect()->route('admin.users.index');
    }
}
