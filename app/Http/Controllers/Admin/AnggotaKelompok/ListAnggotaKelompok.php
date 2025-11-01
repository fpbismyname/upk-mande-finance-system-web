<?php

namespace App\Http\Controllers\Admin\AnggotaKelompok;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKelompok;
use App\Models\Kelompok;
use App\Services\Utils\Debug;

class ListAnggotaKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public $relations = ['kelompok'];
    public $paginate = 10;
    public function list(Kelompok $kelompok_model, $id)
    {
        // Get search query
        $search = request()->get('search');

        // Query model
        $query = $kelompok_model->anggota_kelompok();

        // Search data if any search input
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('nik', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('nomor_telepon', 'like', "%{$search}%")
                    ->orWhereHas('kelompok', function ($query_relation) use ($search) {
                        $query_relation->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Datas
        $datas = $query->paginate($this->paginate)->withQueryString();

        // Debug dump
        Debug::dump($datas, $search);

        // kembalikan view list user
        return $datas ?? [];
    }
}
