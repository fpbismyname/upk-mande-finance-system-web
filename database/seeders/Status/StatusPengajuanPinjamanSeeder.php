<?php

namespace Database\Seeders\Status;

use App\Models\Status\StatusPengajuanPinjaman;
use Illuminate\Database\Seeder;

class StatusPengajuanPinjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'ditolak', 'slug' => 'rejected'],
            ['name' => 'proses_pengajuan', 'slug' => 'submission_process'],
            ['name' => 'disetujui', 'slug' => 'approved'],
        ];

        foreach ($datas as $data) {
            StatusPengajuanPinjaman::firstOrCreate($data);
        }
    }
}
