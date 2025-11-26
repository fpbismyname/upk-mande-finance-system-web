<?php
namespace App\Http\Controllers\Admin;

use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Enums\Admin\Status\EnumStatusKelompok;
use App\Enums\Admin\Status\EnumStatusPengajuanPinjaman;
use App\Enums\Admin\Status\EnumStatusPinjaman;
use App\Enums\Table\PaginateSize;
use App\Http\Controllers\Controller;
use App\Models\CicilanKelompok;
use App\Models\Kelompok;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanKelompok;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        Kelompok $kelompok_model,
        PengajuanPinjaman $pengajuan_pinjaman_model,
        PinjamanKelompok $pinjaman_kelompok_model,
        CicilanKelompok $cicilan_kelompok
    ) {
        // Data kelompok
        $jumlah_kelompok = $kelompok_model
            ->get()->count();
        $jumlah_kelompok_aktif = $kelompok_model->search_by_column('status', EnumStatusKelompok::AKTIF)
            ->get()->count();
        $jumlah_kelompok_non_aktif = $kelompok_model->search_by_column('status', EnumStatusKelompok::NON_AKTIF)
            ->get()->count();
        $jumlah_anggota = $kelompok_model->jumlah_anggota_kelompok()
            ->get()->count();

        // Data pengajuan pinjaman
        $jumlah_pengajuan_pinjaman = $pengajuan_pinjaman_model
            ->get()->count();
        $jumlah_pengajuan_proses_pengajuan = $pengajuan_pinjaman_model->search_by_column('status', EnumStatusPengajuanPinjaman::PROSES_PENGAJUAN)
            ->get()->count();
        $jumlah_pengajuan_disetujui = $pengajuan_pinjaman_model->search_by_column('status', EnumStatusPengajuanPinjaman::PROSES_PENGAJUAN)
            ->get()->count();
        $jumlah_pengajuan_ditolak = $pengajuan_pinjaman_model->search_by_column('status', EnumStatusPengajuanPinjaman::PROSES_PENGAJUAN)
            ->get()->count();

        // Data pinjaman
        $jumlah_pinjaman = $pinjaman_kelompok_model
            ->get()->count();
        $jumlah_pinjaman_berlangsung = $pinjaman_kelompok_model->search_by_column('status', EnumStatusPinjaman::BERLANGSUNG)
            ->get()->count();
        $jumlah_pinjaman_menunggak = $pinjaman_kelompok_model->search_by_column('status', EnumStatusPinjaman::MENUNGGAK)
            ->get()->count();
        $jumlah_pinjaman_selesai = $pinjaman_kelompok_model->search_by_column('status', EnumStatusPinjaman::SELESAI)
            ->get()->count();

        // Data cicilan
        $kelompok_pinjaman = $kelompok_model->cicilan_belum_bayar()->paginate(PaginateSize::SMALL->value)->withQueryString();

        $payload = compact(
            // Vars kelompok
            'jumlah_kelompok',
            'jumlah_kelompok_aktif',
            'jumlah_kelompok_non_aktif',
            'jumlah_anggota',
            // Vars pengajuan
            'jumlah_pengajuan_pinjaman',
            'jumlah_pengajuan_proses_pengajuan',
            'jumlah_pengajuan_disetujui',
            'jumlah_pengajuan_ditolak',
            // Vars pinjaman
            'jumlah_pinjaman',
            'jumlah_pinjaman_berlangsung',
            'jumlah_pinjaman_menunggak',
            'jumlah_pinjaman_selesai',
            // Belum bayar cicilan
            'kelompok_pinjaman'
        );

        return view('admin.dashboard.index', $payload);
    }
}
