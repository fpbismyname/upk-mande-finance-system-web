<?php

namespace App\Http\Controllers\Admin\Pendanaan;

use App\Enum\Admin\CatatanPendanaan\EnumCatatanPendanaan;
use App\Http\Controllers\Controller;
use App\Models\CatatanPendanaan;
use App\Models\Pendanaan;
use App\Services\Admin\CatatanPendanaan\CatatanPendanaanService;
use App\Services\Utils\Debug;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListPendanaan extends Controller
{
    /**
     * Handle the incoming request.
     */
    public $paginate = 10;
    public $pendanan_relations = [];
    public $catatan_relations = [];
    public function __invoke(Request $request, CatatanPendanaan $catatan_pendanaan_model, Pendanaan $pendanaan_model, CatatanPendanaanService $catatan_pendanaan_service)
    {
        // Get search query
        $search = request()->get('search');

        // Query model
        $query_catatan = $catatan_pendanaan_model->with($this->catatan_relations);
        $query_pendanaan = $pendanaan_model->with($this->pendanan_relations);

        // Search data if any search input
        if (!empty($search)) {
            $query_catatan->filter($search);
        }

        // Datas
        $datas_pendanaan = $query_pendanaan->first();
        $datas_catatan_pendanaan = $query_catatan->latest()->paginate($this->paginate)->withQueryString();
        $list_tipe_catatan = EnumCatatanPendanaan::options();

        // Debug dump
        Debug::dump($datas_catatan_pendanaan, $datas_pendanaan, $search);

        // Data breadcrumbs untuk menu 
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            null => 'Saldo pendanaan'
        ];

        // Payload untuk dipassing ke view
        $payload = compact('breadcrumbs', 'datas_pendanaan', 'datas_catatan_pendanaan', 'list_tipe_catatan');

        // kembalikan view list user
        return view('admin.pages.pendanaan.list', $payload);
    }
}
