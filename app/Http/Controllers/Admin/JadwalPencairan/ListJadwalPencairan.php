<?php

namespace App\Http\Controllers\Admin\JadwalPencairan;

use App\Enum\Admin\PengajuanPinjaman\EnumTenor;
use App\Enum\Admin\Status\EnumStatusJadwalPencairan;
use App\Enum\Admin\Status\EnumStatusPengajuanPinjaman;
use App\Http\Controllers\Controller;
use App\Models\JadwalPencairan;
use App\Services\Utils\Debug;

class ListJadwalPencairan extends Controller
{
    /**
     * Handle the incoming request.
     * 
     */
    protected $relations = ['kelompok', 'pengajuan_pinjaman'];
    protected $paginate = 10;
    public function __invoke(JadwalPencairan $jadwal_pencairan_model)
    {
        // Get search query
        $search = request()->get('search');
        $status_jadwal = request()->get('status_jadwal');
        $tenor_pengajuan = request()->get('tenor_pengajuan');
        $status_pengajuan = request()->get('status_pengajuan');

        // Data breadcrumbs untuk menu 
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.jadwal-pencairan.index') => 'Jadwal Pencairan'
        ];

        // Query model
        $query = $jadwal_pencairan_model->with($this->relations);

        // Search data if any search input
        if (!empty($search)) {
            $query->filter($search);
        }
        // Search data by status jadwal
        if (!empty($status_jadwal)) {
            $query->filterStatusJadwal($status_jadwal);
        }
        // Search data by tenor pengajuan
        if (!empty($tenor_pengajuan)) {
            $query->filterTenorPengajuan($tenor_pengajuan);
        }

        // Datas
        $datas = $query->latest()->paginate($this->paginate)->withQueryString();
        $list_status_jadwal = EnumStatusJadwalPencairan::options();
        $list_status_pengajuan = EnumStatusPengajuanPinjaman::options();
        $list_tenor_pengajuan = EnumTenor::options();

        // Payload untuk dipassing ke view
        $payload = compact('breadcrumbs', 'datas', 'list_status_jadwal', 'list_status_pengajuan', 'list_tenor_pengajuan');

        // kembalikan view list user
        return view('admin.pages.jadwal-pencairan.list', $payload);
    }
}
