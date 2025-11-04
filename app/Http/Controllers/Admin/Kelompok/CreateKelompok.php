<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Enum\Admin\Roles\RolesName;
use App\Enum\Admin\Status\EnumStatusKelompok;
use App\Enum\Admin\User\EnumRole;
use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Http\Request;

class CreateKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user_model)
    {
        $role_ketua = EnumRole::ANGGOTA;
        $list_ketua_kelompok = $user_model->doesntHaveKelompok()->get();
        $list_status = EnumStatusKelompok::options();

        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.kelompok.index') => 'kelompok',
            route('admin.kelompok.create') => 'Tambah kelompok'
        ];
        $payload = compact('breadcrumbs', 'list_ketua_kelompok', 'list_status');
        return view('admin.pages.kelompok.create', $payload);
    }
}
