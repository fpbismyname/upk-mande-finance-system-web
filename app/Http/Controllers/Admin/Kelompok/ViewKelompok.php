<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Enum\Admin\Roles\RolesName;
use App\Http\Controllers\Admin\AnggotaKelompok\ListAnggotaKelompok;
use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Status\StatusKelompok;
use App\Models\User;

class ViewKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id_kelompok, ListAnggotaKelompok $list_anggota_kelompok)
    {
        // Data kelompok
        $kelompok = Kelompok::findOrFail($id_kelompok);
        $role_ketua = RolesName::ANGGOTA;
        $list_ketua_kelompok = User::get_users_for_kelompok($role_ketua, user: $kelompok->ketua_kelompok)->get();
        $list_status = StatusKelompok::latest()->get();

        // Data anggota kelompok
        $data_anggota = $list_anggota_kelompok->list($kelompok, $id_kelompok);

        // Breadcrumbs
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.kelompok.index') => 'Kelompok Upk',
            null => "Detail kelompok"
        ];

        $payload = compact('breadcrumbs', 'kelompok', 'list_ketua_kelompok', 'list_status', 'data_anggota');
        return view('admin.pages.kelompok.view', $payload);
    }
}
