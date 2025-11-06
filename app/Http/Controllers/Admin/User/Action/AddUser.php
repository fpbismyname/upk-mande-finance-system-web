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

        $result = $user_service->addUser($new_entries);

        Toast::show($result->message, $result->type_message);
        return redirect()->route('admin.users.index');
    }
}
