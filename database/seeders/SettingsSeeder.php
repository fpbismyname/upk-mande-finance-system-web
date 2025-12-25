<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'key' => 'bunga_pinjaman',
                'value' => '18.00',
                'type' => 'decimal',
                'group' => 'pinjaman'
            ],
            [
                'key' => 'maksimal_limit_pinjaman',
                'value' => '50000000',
                'type' => 'decimal',
                'group' => 'pinjaman'
            ],
            [
                'key' => 'minimal_limit_pinjaman',
                'value' => '2000000',
                'type' => 'decimal',
                'group' => 'pinjaman'
            ],
            [
                'key' => 'kenaikan_limit_per_jumlah_pinjaman',
                'value' => '1',
                'type' => 'decimal',
                'group' => 'pinjaman'
            ],
            [
                'key' => 'toleransi_telat_bayar',
                'value' => '7',
                'type' => 'integer',
                'group' => 'pinjaman'
            ],
            [
                'key' => 'minimal_anggota_kelompok',
                'value' => '5',
                'type' => 'integer',
                'group' => 'kelompok'
            ],
            [
                'key' => 'maksimal_anggota_kelompok',
                'value' => '50',
                'type' => 'integer',
                'group' => 'kelompok'
            ],
            [
                'key' => 'syarat_dan_ketentuan',
                'type' => 'string',
                'group' => 'informasi_website',
                'value' => <<<TEXT
                Syarat & Ketentuan Layanan UPK Mande

                1.	Syarat
                    - Hanya warga yang berdomisili di Kecamatan Mande yang berhak mengajukan pinjaman.
                    - Permohonan peminjaman wajib diajukan secara berkelompok, dengan jumlah anggota minimal 5 orang dalam satu kelompok.
                    - Wajib menyerahkan proposal pinjaman yang berisi nominal pinjaman dan tenor beserta salinan KTP, Kartu Keluarga dan surat ijin dari keluarga atau pihak yang terkait.
                    - Pengguna wajib mematuhi seluruh prosedur penggunaan sistem serta tujuan yang telah ditetapkan.

                2. Ketentuan
                    - Seluruh data dan dokumen yang diajukan digunakan untuk proses verifikasi dan validasi oleh pihak UPK Mande.
                    - Keputusan pengelola terhadap diterima atau ditolaknya pengajuan bersifat final.
                    - Pengelola berwenang mengakses, memproses, serta memverifikasi data untuk keperluan audit, validasi, dan operasional layanan.
                    - Pengelola berhak melakukan pembaruan, pembatasan, peninjauan, atau penonaktifan akun apabila dianggap perlu demi keamanan sistem.
                    - Pengguna dilarang memberikan informasi palsu, menyalahgunakan layanan, atau melakukan aktivitas yang mengganggu operasional sistem.
                    - Data pengguna digunakan semata-mata untuk kebutuhan operasional layanan dan tidak diberikan kepada pihak ketiga tanpa persetujuan, kecuali diwajibkan oleh hukum.
                TEXT
            ],
        ];

        foreach ($datas as $data) {
            Settings::createOrFirst($data);
        }
    }
}
