<?php

namespace App\Services\Admin\PengajuanPinjaman;

use App\Enum\Admin\Status\EnumStatusPengajuanPinjaman;
use App\Models\JadwalPencairan;
use App\Models\PengajuanPinjaman;
use Illuminate\Support\Facades\DB;

class PengajuanPinjamanService
{
    public function create_jadwal_pencairan($datas)
    {
        if (empty($datas))
            return false;
        return DB::transaction(function () use ($datas) {
            $created_data = JadwalPencairan::create($datas);
            return $created_data->wasRecentlyCreated;
        });
    }
    public function submit_review($id_pengajuan, $data_review)
    {
        // Validasi input data
        if (empty($data_review)) {
            return false;
        }

        // Update Data pengajuan
        return DB::transaction(function () use ($id_pengajuan, $data_review) {
            // Data pengajuan
            $data_pengajuan = PengajuanPinjaman::findOrFail($id_pengajuan);
            // Check status disetujui
            $is_approved = EnumStatusPengajuanPinjaman::DISETUJUI->value === $data_review['status'];
            // Current time
            $current_time = now();
            // New data pengajuan
            $new_data_pengajuan = $is_approved
                ? [
                    'status' => $data_review['status'],
                    'catatan' => $data_review['catatan'],
                    'disetujui_pada' => $current_time
                ]
                : [
                    'status' => $data_review['status'],
                    'catatan' => $data_review['catatan'],
                    'ditolak_pada' => $current_time
                ];
            // Bikin jadwal pencairan
            if ($is_approved) {
                $data_jadwal_pencairan = [
                    'kelompok_id' => $data_pengajuan->kelompok_id,
                    'pengajuan_pinjaman_id' => $data_pengajuan->id,
                ];
                $this->create_jadwal_pencairan($data_jadwal_pencairan);
            }
            return $data_pengajuan->update($new_data_pengajuan);
        });
    }
}