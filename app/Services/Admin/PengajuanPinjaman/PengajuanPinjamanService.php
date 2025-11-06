<?php

namespace App\Services\Admin\PengajuanPinjaman;

use App\Enum\Admin\Status\EnumStatusJadwalPencairan;
use App\Enum\Admin\Status\EnumStatusPengajuanPinjaman;
use App\Models\JadwalPencairan;
use App\Models\Pendanaan;
use App\Models\PengajuanPinjaman;
use App\Services\Utils\Result;
use Illuminate\Support\Facades\DB;

class PengajuanPinjamanService
{
    public function submit_review($id_pengajuan, $data_review)
    {
        // Validasi input data
        if (empty($data_review)) {
            return false;
        }

        // Data pengajuan
        $data_pengajuan = PengajuanPinjaman::findOrFail($id_pengajuan);

        // Pendanaan yang terjadwalkan
        $jumlah_pendanaan_terjadwalkan = JadwalPencairan::where('jadwal_pencairan.status', [EnumStatusJadwalPencairan::TERJADWAL, EnumStatusJadwalPencairan::BELUM_TERJADWAL])
            ->join('pengajuan_pinjaman', 'jadwal_pencairan.pengajuan_pinjaman_id', '=', 'pengajuan_pinjaman.id')
            ->sum('nominal_pinjaman');

        // Cek saldo pendanaan
        $pendanaan = Pendanaan::first();
        if ($pendanaan->saldo <= $jumlah_pendanaan_terjadwalkan) {
            return Result::info('Saldo pendanaan tidak cukup untuk menyetujui pengajuan pinjaman.');
        }

        // Update Data pengajuan
        $submitted_review = DB::transaction(function () use ($data_pengajuan, $data_review) {
            // Data pengajuan
            // Check status disetujui
            $is_approved = EnumStatusPengajuanPinjaman::DISETUJUI->value === $data_review['status'];
            // Current time
            $current_time = now();
            // New data pengajuan
            $new_data_pengajuan = $is_approved
                ? [
                    'status' => $data_review['status'],
                    'catatan' => $data_review['catatan'],
                    'tanggal_disetujui' => $current_time
                ]
                : [
                    'status' => $data_review['status'],
                    'catatan' => $data_review['catatan'],
                    'tanggal_ditolak' => $current_time
                ];
            // Bikin jadwal pencairan
            if ($is_approved) {
                // Buat data jadwal
                $data_jadwal_pencairan = [
                    'kelompok_id' => $data_pengajuan->kelompok_id,
                    'pengajuan_pinjaman_id' => $data_pengajuan->id,
                ];
                $this->create_jadwal_pencairan($data_jadwal_pencairan);
            }
            $data_pengajuan->fill($new_data_pengajuan);
            $data_pengajuan->save();
            return $data_pengajuan;
        });

        if ($submitted_review) {
            return Result::success('Pengajuan pinjaman berhasil direview.');
        }
        return Result::success('Terjadi kesalahan proses review pengajuan pinjaman.');
    }
    public function create_jadwal_pencairan($datas)
    {
        if (empty($datas))
            return false;
        DB::transaction(function () use ($datas) {
            return JadwalPencairan::create($datas);
        });
    }
}