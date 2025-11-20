<?php

namespace Database\Seeders;

use App\Models\AnggotaKelompok;
use App\Models\Kelompok;
use App\Models\PengajuanPinjaman;
use Illuminate\Database\Seeder;

class KelompokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Amount kelompok
        $amount_kelompok = 25;

        // Create data kelompok
        $created_kelompok = Kelompok::get()->count();

        if (!$created_kelompok > 0) {
            $data_kelompok = Kelompok::factory()->count($amount_kelompok)->create();
            $data_kelompok->each(function ($kelompok) {

                // Tambahkan anggota kelompok
                $jumlah_anggota = round(rand(5, 8));
                AnggotaKelompok::factory()->count($jumlah_anggota)->create([
                    'kelompok_id' => $kelompok->id,
                ]);

                // Tambahkan data pengajuan di setiap kelompok
                PengajuanPinjaman::factory()->count(1)->create([
                    'kelompok_id' => $kelompok->id,
                ]);
            });
        }
    }
}
