<?php

namespace App\Http\Controllers\Admin\User;

use App\Enum\Admin\User\EnumRole;
use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\User;

class CreateUser extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.users.index') => 'Akun pengguna',
            route('admin.users.create') => 'Tambah akun pengguna'
        ];
        $list_role = EnumRole::options();
        $payload = compact('breadcrumbs', 'list_role');
        return view('admin.pages.users.create', $payload);
    }
}
