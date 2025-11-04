<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class ViewUser extends Controller
{
    public function __invoke(int $id, User $user_model)
    {
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.users.index') => 'Akun pengguna',
            null => 'Detail akun pengguna'
        ];
        $user = $user_model::findOrFail($id);
        $payload = compact('breadcrumbs', 'user');
        return view('admin.pages.users.view', $payload);
    }
}
