<?php

namespace App\Http\Controllers\Admin\AnggotaKelompok;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class ViewAnggotaKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id_kelompok, $id_anggota, Kelompok $kelompok_model)
    {
        // Data Anggota
        $kelompok = $kelompok_model->findOrFail($id_kelompok);
        $anggota = $kelompok->anggota_kelompok()->findOrFail($id_anggota);

        // Breadcrumbs
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.kelompok.index') => 'Kelompok Upk',
            route('admin.kelompok.view', [$kelompok->id]) => "Detail kelompok",
            null => "Detail anggota kelompok",
        ];

        $payload = compact('breadcrumbs', 'anggota', 'kelompok', 'id_kelompok', 'id_anggota');
        return view('admin.pages.kelompok.anggota.view', $payload);
    }
}
