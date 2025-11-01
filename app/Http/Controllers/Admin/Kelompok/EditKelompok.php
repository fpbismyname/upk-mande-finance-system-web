<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Enum\Admin\Roles\RolesName;
use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Status\StatusKelompok;
use App\Models\User;
use Illuminate\Http\Request;

class EditKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id_kelompok)
    {
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.kelompok.index') => 'Kelompok Upk',
            route('admin.kelompok.edit', [$id_kelompok]) => 'Edit kelompok'
        ];
        $kelompok = Kelompok::findOrFail($id_kelompok);
        $role_ketua = RolesName::ANGGOTA;
        $list_ketua_kelompok = User::get_users_for_kelompok($role_ketua, user: $kelompok->ketua_name)->get();
        $list_status = StatusKelompok::latest()->get();
        $payload = compact('breadcrumbs', 'kelompok', 'list_ketua_kelompok', 'list_status');
        return view('admin.pages.kelompok.edit', $payload);
    }
}
