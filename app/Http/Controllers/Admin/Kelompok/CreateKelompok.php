<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Enum\Admin\Roles\RolesName;
use App\Http\Controllers\Controller;
use App\Models\Status\StatusKelompok;
use App\Models\User;
use Illuminate\Http\Request;

class CreateKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $role_ketua = RolesName::ANGGOTA;
        $list_ketua_kelompok = User::get_users_for_kelompok($role_ketua)->get();
        $list_status = StatusKelompok::latest()->get();

        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.kelompok.index') => 'kelompok',
            route('admin.kelompok.create') => 'Tambah kelompok'
        ];
        $payload = compact('breadcrumbs', 'list_ketua_kelompok', 'list_status');
        return view('admin.pages.kelompok.create', $payload);
    }
}
