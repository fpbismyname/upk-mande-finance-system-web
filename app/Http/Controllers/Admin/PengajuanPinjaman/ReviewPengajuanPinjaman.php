<?php

namespace App\Http\Controllers\Admin\PengajuanPinjaman;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use App\Models\Status\StatusPengajuanPinjaman;
use App\Services\Admin\PengajuanPinjaman\PengajuanPinjamanService;
use Illuminate\Http\Request;

class ReviewPengajuanPinjaman extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id, PengajuanPinjaman $pengajuan_pinjaman_model, PengajuanPinjamanService $pengajuan_pinjaman_service, StatusPengajuanPinjaman $status_pengajuan_pinjaman_model)
    {
        $pengajuan_pinjaman = $pengajuan_pinjaman_model::findOrFail($id);
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.pengajuan-pinjaman.index') => 'Daftar pengajuan pinjaman',
            null => "Review Pengajuan pinjaman"
        ];
        $list_status_pengajuan = $status_pengajuan_pinjaman_model->withoutRelations()->whereNot('name', PengajuanPinjamanService::PROSES_PENGAJUAN)->get();
        $payload = compact('breadcrumbs', 'pengajuan_pinjaman', 'list_status_pengajuan');
        return view('admin.pages.pengajuan-pinjaman.review', $payload);
    }
}
