<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\Status\EnumStatusPengajuanKeanggotaan;
use App\Enums\Admin\User\EnumRole;
use App\Enums\Table\PaginateSize;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PengajuanKeanggotaanRequest;
use App\Models\PengajuanKeanggotaan;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class PengajuanKeanggotaanController extends Controller
{
    protected $relations = ['users'];
    public function index(Request $request, PengajuanKeanggotaan $pengajuan_keanggotaan_model)
    {
        // Get search query
        $filters = $request->only('search', 'status');

        // Query model
        $query = $pengajuan_keanggotaan_model->with($this->relations);

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

        // Payload untuk dipassing ke view
        $payload = compact('datas');

        // kembalikan view list user
        return view('admin.pengajuan-keanggotaan.index', $payload);
    }
    public function show(string $id, PengajuanKeanggotaan $pengajuan_keanggotaan_model)
    {
        $pengajuan_keanggotaan = $pengajuan_keanggotaan_model->findOrFail($id);
        $payload = compact('pengajuan_keanggotaan');
        return view('admin.pengajuan-keanggotaan.show', $payload);
    }
    public function edit(string $id, PengajuanKeanggotaan $pengajuan_keanggotaan_model)
    {
        $pengajuan_keanggotaan = $pengajuan_keanggotaan_model->findOrFail($id);
        $payload = compact('pengajuan_keanggotaan');
        return view('admin.pengajuan-keanggotaan.edit', $payload);
    }
    public function update(PengajuanKeanggotaanRequest $request, string $id, PengajuanKeanggotaan $pengajuan_keanggotaan_model)
    {
        $data_review = $request->validated();

        // Data pengajuan
        $data_pengajuan = $pengajuan_keanggotaan_model->findOrFail($id);

        $data_pengajuan->update($data_review);

        // ubah role
        $data_pengajuan->users->update(['role' => EnumRole::ANGGOTA]);

        if ($data_pengajuan->wasChanged()) {
            Toast::success('Pengajuan pinjaman berhasil direview.');
        } else {
            Toast::error('Pengajuan pinjaman gagal direview.');
        }
        return redirect()->back();
    }
}
