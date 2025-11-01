<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;

class EditUser extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id, Roles $roles_model)
    {
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.users.index') => 'Akun pengguna',
            route('admin.users.edit', ['id' => $id]) => 'Edit akun pengguna'
        ];
        $user = User::findOrFail($id);
        $list_role = $roles_model->get_roles_without_admin_role();
        $payload = compact('breadcrumbs', 'user', 'list_role');
        return view('admin.pages.users.edit', $payload);
    }
}
