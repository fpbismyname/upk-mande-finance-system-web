<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\PengajuanPinjaman\EnumTenor;
use App\Enums\Admin\Status\EnumStatusJadwalPencairan;
use App\Enums\Admin\Status\EnumStatusPengajuanPinjaman;
use App\Enums\Table\PaginateSize;
use App\Exports\Admin\JadwalPencairanExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JadwalPencairanRequest;
use App\Models\JadwalPencairan;
use App\Services\UI\Toast;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class JadwalPencairanController extends Controller
{
    protected $relations = ['pengajuan_pinjaman'];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, JadwalPencairan $jadwal_pencairan_model)
    {
        // Get search query
        $filters = $request->only(['search', 'status_jadwal', 'tenor_pengajuan', 'status_pengajuan']);

        // Query model
        $query = $jadwal_pencairan_model->with($this->relations);

        // Search data by filters
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                match ($key) {
                    'search' => $query->search($value),
                    'tenor_pengajuan' => $query->filterTenorPengajuan($value),
                    'status_pengajuan' => $query->filterStatusPengajuan($value),
                    'status_jadwal' => $query->filterStatusJadwal($value),
                    default => $query->search_by_column($key, $value)
                };

            }
        }

        // Datas
        $datas = $query->latest()->paginate(PaginateSize::SMALL->value)->withQueryString();
        $list_status_jadwal = EnumStatusJadwalPencairan::options();
        $list_status_pengajuan = EnumStatusPengajuanPinjaman::options();
        $list_tenor_pengajuan = EnumTenor::options();

        // Payload untuk dipassing ke view
        $payload = compact('datas', 'list_status_jadwal', 'list_status_pengajuan', 'list_tenor_pengajuan');

        // kembalikan view list user
        return view('admin.jadwal-pencairan.index', $payload);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, JadwalPencairan $jadwal_pencairan_model)
    {
        $jadwal_pencairan = $jadwal_pencairan_model::findOrFail($id);
        $payload = compact('jadwal_pencairan');
        return view('admin.jadwal-pencairan.edit', $payload);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function show(string $id, JadwalPencairan $jadwal_pencairan_model)
    {
        $jadwal_pencairan = $jadwal_pencairan_model->findOrFail($id);
        $payload = compact('jadwal_pencairan');
        return view('admin.jadwal-pencairan.show', $payload);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, JadwalPencairanRequest $request, JadwalPencairan $jadwal_pencairan_model)
    {
        $data_jadwal = $request->validated();
        $data_jadwal['status'] = EnumStatusJadwalPencairan::TERJADWAL;

        $jadwal_pencairan = $jadwal_pencairan_model->findOrFail($id);
        $jadwal_pencairan->update($data_jadwal);

        if ($jadwal_pencairan->wasChanged()) {
            Toast::success('Jadwal pencairan berhasil terjadwal.');
        } else {
            Toast::error('Jadwal pencairan gagal melakukan penjadwalan.');
        }
        return redirect()->back();
    }

    public function export(Request $request, JadwalPencairan $jadwal_pencairan_model, Excel $excel)
    {
        // Get search query
        $filters = $request->only(['search', 'status_jadwal', 'tenor_pengajuan', 'status_pengajuan']);

        // Query model
        $query = $jadwal_pencairan_model->with($this->relations);

        // Search data by filters
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                match ($key) {
                    'search' => $query->search($value),
                    'tenor_pengajuan' => $query->filterTenorPengajuan($value),
                    'status_jadwal' => $query->filterStatusJadwal($value),
                    default => $query->search_by_column($key, $value)
                };

            }
        }

        $data_jadwal_pencairan = $query->get();

        $today = now()->format('d_M_Y-H_i_s');
        $file_name = "data_jadwal_pencairan_{$today}.xlsx";

        return $excel->download(new JadwalPencairanExport($data_jadwal_pencairan), $file_name);
    }
}
