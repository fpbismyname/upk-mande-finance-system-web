<?php

namespace App\Http\Controllers\Admin\CicilanKelompok;

use App\Enum\Admin\Status\EnumStatusCicilanKelompok;
use App\Http\Controllers\Controller;
use App\Models\PinjamanKelompok;

class ListCicilanKelompok extends Controller
{/**
 * Handle the incoming request.
 */
    public $relations = ['pinjaman_kelompok'];
    public $paginate = 10;
    public function list(PinjamanKelompok $pinjaman_kelompok_model, $id_pinjaman)
    {
        // Get search query
        $search = request()->get('search');
        $status = request()->get('status');

        // Query model
        $query = $pinjaman_kelompok_model->cicilan_kelompok()->with($this->relations);

        // Search data if any search input
        if (!empty($search)) {
            $query->filter($search);
        }
        // Search data if any status search
        if (!empty($status)) {
            $query->filter($status);
        }

        // Datas
        $datas = $query->latest()->paginate($this->paginate)->withQueryString();
        $list_status = EnumStatusCicilanKelompok::options();

        // kembalikan view list user
        return $datas ?? [];
    }
}
