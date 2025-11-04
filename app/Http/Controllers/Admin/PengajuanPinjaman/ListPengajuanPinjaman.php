<?php

namespace App\Http\Controllers\Admin\PengajuanPinjaman;

use App\Enum\Admin\PengajuanPinjaman\EnumTenor;
use App\Enum\Admin\Status\EnumStatusPengajuanPinjaman;
use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use App\Models\Status\StatusPengajuanPinjaman;
use App\Services\Utils\Debug;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListPengajuanPinjaman extends Controller
{
    /**
     * Handle the incoming request.
     */
    public $relations = ['kelompok'];
    public $paginate = 10;
    public function __invoke(Request $request, PengajuanPinjaman $pengajuan_pinjaman_model, StatusPengajuanPinjaman $status_pengajuan_pinjaman_model)
    {
        // Get search query
        $search = request()->get('search');
        $status = request()->get('status');
        $tenor = request()->get('tenor');

        // Data breadcrumbs untuk menu 
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.pengajuan-pinjaman.index') => 'Pengajuan pinjaman'
        ];

        // Query model
        $query = $pengajuan_pinjaman_model->with($this->relations);

        // Search data if any search input
        if (!empty($search)) {
            $query->filter($search);
        }
        // Seachh data by status
        if (!empty($status)) {
            $query->filterStatus($status);
        }
        // Seachh data by tenor
        if (!empty($tenor)) {
            $query->filterTenor($tenor);
        }

        // Datas
        $datas = $query->latest()->paginate($this->paginate)->withQueryString();
        $list_status = EnumStatusPengajuanPinjaman::options();
        $list_tenor = EnumTenor::options();

        // Debug dump
        Debug::dump($datas, $search);

        // Payload untuk dipassing ke view
        $payload = compact('breadcrumbs', 'datas', 'list_status', 'list_tenor');

        // kembalikan view list user
        return view('admin.pages.pengajuan-pinjaman.list', $payload);
    }
}
