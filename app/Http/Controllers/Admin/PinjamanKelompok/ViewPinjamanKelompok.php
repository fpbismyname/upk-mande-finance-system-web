<?php

namespace App\Http\Controllers\Admin\PinjamanKelompok;

use App\Enum\Admin\Status\EnumStatusCicilanKelompok;
use App\Http\Controllers\Admin\CicilanKelompok\ListCicilanKelompok;
use App\Http\Controllers\Controller;
use App\Models\PinjamanKelompok;
use Illuminate\Http\Request;

class ViewPinjamanKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id_pinjaman, PinjamanKelompok $pinjaman_kelompok_model, ListCicilanKelompok $list_cicilan)
    {
        // Data Pinjaman
        $pinjaman_kelompok = $pinjaman_kelompok_model->findOrFail($id_pinjaman);

        // Data anggota kelompok
        $data_cicilan = $list_cicilan->list($pinjaman_kelompok, $id_pinjaman);
        $list_status_cicilan = EnumStatusCicilanKelompok::options();

        // Breadcrumbs
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.pinjaman-kelompok.index') => 'Pinjaman kelompok',
            null => "Detail pinjaman"
        ];

        $payload = compact('breadcrumbs', 'pinjaman_kelompok', 'data_cicilan', 'list_status_cicilan');
        return view('admin.pages.pinjaman-kelompok.view', $payload);
    }
}
