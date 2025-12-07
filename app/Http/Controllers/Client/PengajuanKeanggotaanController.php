<?php

namespace App\Http\Controllers\Client;

use App\Enums\Admin\Status\EnumStatusPengajuanKeanggotaan;
use App\Enums\Admin\Status\EnumStatusPengajuanPinjaman;
use App\Enums\Table\PaginateSize;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\PengajuanKeanggotaanRequest;
use App\Models\PengajuanKeanggotaan;
use App\Services\UI\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;

class PengajuanKeanggotaanController extends Controller
{
    public function index()
    {
        $datas = auth()->user()->pengajuan_keanggotaan()->paginate(PaginateSize::SMALL->value);
        $payload = compact('datas');
        return view('client.pengajuan-keanggotaan.index', $payload);
    }
    public function show(string $id, PengajuanKeanggotaan $pengajuan_keanggotaan_model)
    {
        $pengajuan_keanggotaan = $pengajuan_keanggotaan_model->findOrFail($id);
        $payload = compact('pengajuan_keanggotaan');
        return view('client.pengajuan-keanggotaan.show', $payload);
    }
    public function create()
    {
        return view('client.pengajuan-keanggotaan.create');
    }
    public function store(PengajuanKeanggotaanRequest $request, PengajuanKeanggotaan $pengajuan_keanggotaan_model)
    {
        // Validasi data form input
        $request->validated();

        $data_users = auth()->user();
        $data_pengajuan_keanggotaan = $request->only($pengajuan_keanggotaan_model->getFillable());

        // Upload files
        $files = $request->only('ktp');
        $uploaded_file_path = [];
        $storage_private = Storage::disk('local');

        foreach ($files as $key => $value) {
            $file = $value;
            $ext_file = $file->getClientOriginalExtension();
            $safe_username = Str::snake($data_pengajuan_keanggotaan['nama_lengkap']);
            $today = now()->clone()->format('d-m-y');
            $file_name = "{$key}_{$safe_username}_{$today}.{$ext_file}";
            if ($storage_private->exists($file_name)) {
                $storage_private->delete($file_name);
            }
            $uploaded_file_path[$key] = $storage_private->putFileAs('users', $file, $file_name);
        }

        foreach ($uploaded_file_path as $key => $path) {
            $data_pengajuan_keanggotaan[$key] = $path;
        }

        // Buat akun pengguna
        $data_pengajuan_keanggotaan['status'] = EnumStatusPengajuanKeanggotaan::PROSES_PENGAJUAN;
        $store_user = $data_users->pengajuan_keanggotaan()->create($data_pengajuan_keanggotaan);


        // Validasi akun pengguna yang ditambahkan
        if ($store_user->wasRecentlyCreated) {
            Toast::success('Pengajuan keanggotaan berhasil dikirimkan.');
        } else {
            Toast::error('Pengajuan keanggotaan gagal dikirim.');
        }
        return redirect()->route('client.pengajuan-keanggotaan.index');

    }
    public function cancel_pengajuan(string $id, PengajuanKeanggotaan $pengajuan_keanggotaan_model)
    {
        $storage_private = Storage::disk('local');
        $pengajuan_keanggotaan = auth()->user()->pengajuan_keanggotaan()->findOrFail($id);
        if ($storage_private->exists($pengajuan_keanggotaan->ktp)) {
            $storage_private->delete($pengajuan_keanggotaan->ktp);
        }
        $pengajuan_keanggotaan->update([
            'status' => EnumStatusPengajuanKeanggotaan::DIBATALKAN,
            'ktp' => null
        ]);
        if ($pengajuan_keanggotaan->wasChanged()) {
            Toast::success('Pengajuan berhasil dibatalkan.');
        } else {
            Toast::error('Pengajuan gagal dibatalkan. Silahkan cobalagi di lain waktu');
        }
        return redirect()->back();
    }
}
