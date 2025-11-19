<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\PengajuanPinjaman\EnumTenor;
use App\Enums\Admin\Status\EnumStatusJadwalPencairan;
use App\Enums\Admin\Status\EnumStatusPengajuanPinjaman;
use App\Enums\Table\PaginateSize;
use App\Exports\Admin\PengajuanPinjamanExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PengajuanPinjamanRequest;
use App\Models\JadwalPencairan;
use App\Models\Pendanaan;
use App\Models\PengajuanPinjaman;
use App\Models\Rekening;
use App\Services\UI\Toast;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class PengajuanPinjamanController extends Controller
{
    protected $relations = ['kelompok'];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PengajuanPinjaman $pengajuan_pinjaman_model)
    {
        // Get search query
        $filters = $request->only('search', 'status', 'tenor');

        // Query model
        $query = $pengajuan_pinjaman_model->with($this->relations);

        // Search data if any search input
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                match ($key) {
                    'search' => $query->search($value),
                    default => $query->search_by_column($key, $value),
                };
            }
        }

        // Datas
        $datas = $query->latest()->paginate(PaginateSize::SMALL->value)->withQueryString();
        $list_status = EnumStatusPengajuanPinjaman::options();
        $list_tenor = EnumTenor::options();

        // Payload untuk dipassing ke view
        $payload = compact('datas', 'list_status', 'list_tenor');

        // kembalikan view list user
        return view('admin.pengajuan-pinjaman.index', $payload);
    }

    public function show(string $id, PengajuanPinjaman $pengajuan_pinjaman_model)
    {
        $pengajuan_pinjaman = $pengajuan_pinjaman_model::findOrFail($id);
        $list_status = collect(EnumStatusPengajuanPinjaman::options())->except(['proses_pengajuan', 'dibatalkan']);
        $payload = compact('pengajuan_pinjaman', 'list_status');
        return view('admin.pengajuan-pinjaman.show', $payload);
    }

    public function edit(string $id, PengajuanPinjaman $pengajuan_pinjaman_model)
    {
        $pengajuan_pinjaman = $pengajuan_pinjaman_model::findOrFail($id);
        $list_status = collect(EnumStatusPengajuanPinjaman::options())->except(['proses_pengajuan', 'dibatalkan']);
        $payload = compact('pengajuan_pinjaman', 'list_status');
        return view('admin.pengajuan-pinjaman.edit', $payload);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        PengajuanPinjamanRequest $request,
        string $id_pengajuan,
        PengajuanPinjaman $pengajuan_pinjaman_model,
        JadwalPencairan $jadwal_pencairan_model,
    ) {
        // Data review
        $data_review = $request->validated();

        // Data pengajuan
        $data_pengajuan = $pengajuan_pinjaman_model->findOrFail($id_pengajuan);

        // Check status disetujui
        $is_approved = EnumStatusPengajuanPinjaman::DISETUJUI->value === $data_review['status'];

        // Current time
        $current_time = now();

        // New data pengajuan
        $new_data_pengajuan = $is_approved
            ? [
                'status' => $data_review['status'],
                'catatan' => $data_review['catatan'],
                'tanggal_disetujui' => $current_time,
            ]
            : [
                'status' => $data_review['status'],
                'catatan' => $data_review['catatan'],
                'tanggal_ditolak' => $current_time,
            ];

        // Bikin jadwal pencairan
        if ($is_approved) {
            // Buat data jadwal
            $data_jadwal_pencairan = [
                'kelompok_id' => $data_pengajuan->kelompok_id,
                'pengajuan_pinjaman_id' => $data_pengajuan->id,
            ];

            $jadwal_pencairan_model->create($data_jadwal_pencairan);
        }

        $data_pengajuan->update($new_data_pengajuan);

        if ($data_pengajuan->wasChanged()) {
            Toast::success('Pengajuan pinjaman berhasil direview.');
        } else {
            Toast::error('Pengajuan pinjaman gagal direview.');
        }
        return redirect()->back();
    }

    public function export(Request $request, PengajuanPinjaman $pengajuan_pinjaman_model, Excel $excel)
    {
        // Get search query
        $filters = $request->only('search', 'status', 'tenor');

        // Query model
        $query = $pengajuan_pinjaman_model->with($this->relations);

        // Search data if any search input
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                match ($key) {
                    'search' => $query->search($value),
                    default => $query->search_by_column($key, $value),
                };
            }
        }
        $data_pengajuan = $query->get();

        $today = now()->format('d_M_Y-H_i_s');
        $file_name = "data_pengajuan_pinjaman_{$today}.xlsx";

        return $excel->download(new PengajuanPinjamanExport($data_pengajuan), $file_name);
    }
}
