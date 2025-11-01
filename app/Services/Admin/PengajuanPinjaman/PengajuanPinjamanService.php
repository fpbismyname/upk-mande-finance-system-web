<?php

namespace App\Services\Admin\PengajuanPinjaman;

use App\Enum\Admin\StatusJadwalPencairan\EnumStatusJadwalPencairan;
use App\Models\JadwalPencairan;
use App\Models\PengajuanPinjaman;
use App\Models\Status\StatusJadwalPencairan;
use App\Models\Status\StatusPengajuanPinjaman;
use Illuminate\Support\Facades\DB;

class PengajuanPinjamanService
{
    const PROSES_PENGAJUAN = 'proses_pengajuan';
    const DISETUJUI = 'disetujui';
    const DITOLAK = 'ditolak';
    protected PengajuanPinjaman $model;
    protected StatusPengajuanPinjaman $status_pengajuan_model;
    protected JadwalPencairan $jadwal_pencairan_model;
    public function __construct()
    {
        $this->model = new PengajuanPinjaman();
        $this->status_pengajuan_model = new StatusPengajuanPinjaman();
        $this->jadwal_pencairan_model = new JadwalPencairan();
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
            $data_pengajuan = $this->model->findOrFail($id_pengajuan);
            // Data status pengajuan
            $status_pengajuan_model = $this->status_pengajuan_model->findOrFail($data_review['status_id']);
            // Check status disetujui
            $is_approved = $status_pengajuan_model->name == $this::DISETUJUI;
            // Current time
            $current_time = now();
            // New data pengajuan
            $new_data_pengajuan = $is_approved
                ? [
                    'status_id' => $data_review['status_id'],
                    'catatan' => $data_review['catatan'],
                    'disetujui_pada' => $current_time
                ]
                : [
                    'status_id' => $data_review['status_id'],
                    'catatan' => $data_review['catatan'],
                    'ditolak_pada' => $current_time
                ];
            // Validation Approval pengajuan pinjaman
            if ($is_approved) {
                $status_pencairan = $this->status_pengajuan_model->withoutRelations()->all();
                $create_jadwal_pencairan = $this->jadwal_pencairan_model->create([
                    'status_id' => EnumStatusJadwalPencairan::TELAH_DICAIRKAN
                ]);
            }
            return $data_pengajuan->update($new_data_pengajuan);
        });
    }
}