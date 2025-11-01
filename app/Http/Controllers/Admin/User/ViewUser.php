<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class ViewUser extends Controller
{
    public function __invoke(int $id)
    {
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.users.index') => 'Akun pengguna',
            route('admin.users.view', ['id' => $id]) => 'Detail akun pengguna'
        ];
        $user = User::findOrFail($id);
        $payload = compact('breadcrumbs', 'user');
        return view('admin.pages.users.view', $payload);
    }
}
