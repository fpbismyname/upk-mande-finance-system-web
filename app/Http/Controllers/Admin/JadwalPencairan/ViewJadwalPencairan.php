<?php

namespace App\Http\Controllers\Admin\JadwalPencairan;

use App\Http\Controllers\Controller;
use App\Models\JadwalPencairan;

class ViewJadwalPencairan extends Controller
{
    public function __invoke($id, JadwalPencairan $jadwal_pencairan_model)
    {
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.jadwal-pencairan.index') => 'Jadwal pencairan',
            null => 'Penjadwalan pengajuan pinjaman'
        ];
        $jadwal_pencairan = $jadwal_pencairan_model::findOrFail($id);
        $payload = compact('breadcrumbs', 'jadwal_pencairan');
        return view('admin.pages.jadwal-pencairan.view', $payload);
    }
}
