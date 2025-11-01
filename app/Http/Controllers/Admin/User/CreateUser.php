<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\User;

class CreateUser extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Roles $roles_model)
    {
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.users.index') => 'Akun pengguna',
            route('admin.users.create') => 'Tambah akun pengguna'
        ];
        $list_role = $roles_model->get_roles_without_admin_role();
        $payload = compact('breadcrumbs', 'list_role');
        return view('admin.pages.users.create', $payload);
    }
}
