<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Enum\Admin\Status\EnumStatusKelompok;
use App\Enum\Admin\User\EnumRole;
use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Http\Request;

class EditKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id_kelompok, Kelompok $kelompok_model, User $user_model)
    {
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.kelompok.index') => 'Kelompok Upk',
            route('admin.kelompok.edit', [$id_kelompok]) => 'Edit kelompok'
        ];
        $kelompok = $kelompok_model->findOrFail($id_kelompok);
        $role_ketua = EnumRole::ANGGOTA;
        $list_ketua_kelompok = $user_model->doesntHaveKelompok($kelompok->users_id)->get();
        $list_status = EnumStatusKelompok::options();
        $payload = compact('breadcrumbs', 'kelompok', 'list_ketua_kelompok', 'list_status');
        return view('admin.pages.kelompok.edit', $payload);
    }
}
