<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKelompok;
use Illuminate\Http\Request;

class AnggotaKelompokController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id_kelompok, string $id_anggota_kelompok, AnggotaKelompok $anggota_kelompok_model)
    {
        $anggota_kelompok = $anggota_kelompok_model->findOrFail($id_anggota_kelompok);
        $payload = compact('anggota_kelompok', 'id_kelompok');
        return view('admin.kelompok.anggota.show', $payload);
    }
}
