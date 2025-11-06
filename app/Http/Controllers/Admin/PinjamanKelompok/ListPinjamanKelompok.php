<?php

namespace App\Http\Controllers\Admin\PinjamanKelompok;

use App\Enum\Admin\PengajuanPinjaman\EnumTenor;
use App\Enum\Admin\Status\EnumStatusPinjaman;
use App\Http\Controllers\Controller;
use App\Models\PinjamanKelompok;
use Illuminate\Http\Request;

class ListPinjamanKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public $relations = ['kelompok'];
    public $paginate = 10;
    public function __invoke(PinjamanKelompok $pinjaman_kelompok_model)
    {
        // Get search query
        $search = request()->get('search');
        $status = request()->get('status');
        $tenor = request()->get('tenor');

        // Data breadcrumbs untuk menu 
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            null => 'Pinjaman kelompok'
        ];

        // Query model
        $query = $pinjaman_kelompok_model->with($this->relations);

        // Search data if any search input
        if (!empty($search)) {
            $query->filter($search);
        }

        // Search data by status
        if (!empty($status)) {
            $query->filterStatus($status);
        }

        // Search data by tenor
        if (!empty($tenor)) {
            $query->filterTenor($tenor);
        }

        // Datas
        $datas = $query->latest()->paginate($this->paginate)->withQueryString();
        $list_status = EnumStatusPinjaman::options();
        $list_tenor = EnumTenor::options();

        // Payload untuk dipassing ke view
        $payload = compact('breadcrumbs', 'datas', 'list_status', 'list_tenor');

        // kembalikan view list user
        return view('admin.pages.pinjaman-kelompok.list', $payload);
    }
}
