<?php

namespace App\Services\Admin\JadwalPencairan;

use App\Enum\Admin\Status\EnumStatusJadwalPencairan;
use App\Models\JadwalPencairan;
use Illuminate\Support\Facades\DB;

class JadwalPencairanService
{
    public function make_schedule($id, $datas)
    {
        $current_jadwal = JadwalPencairan::findOrFail($id);
        return DB::transaction(function () use ($current_jadwal, $datas) {
            $data_jadwal = [
                'tanggal_pencairan' => $datas['tanggal_pencairan'],
                'status' => EnumStatusJadwalPencairan::TERJADWAL->value
            ];
            return $current_jadwal->update($data_jadwal);

        });
    }
}