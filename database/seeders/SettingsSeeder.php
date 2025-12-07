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
                'value' => '4',
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
                Syarat & Ketentuan Layanan Upk Mande

                1. Pendaftaran dan Akun
                - Pengguna wajib memberikan data yang benar dan akurat pada saat pendaftaran.
                - Pengguna bertanggung jawab penuh atas keamanan akun serta aktivitas yang dilakukan melalui akun tersebut.

                2. Pengajuan Keanggotaan
                - Seluruh data dan dokumen yang diajukan akan digunakan untuk proses verifikasi dan validasi.
                - Keputusan pengelola terhadap pengajuan bersifat final.

                3. Akses dan Pengelolaan Sistem
                - Pengelola sistem berwenang untuk mengakses dan memproses data pengguna dalam rangka verifikasi, validasi, audit, dan operasional layanan.
                - Pengelola berhak melakukan pembaruan, peninjauan, pembatasan, atau penonaktifan akun apabila diperlukan untuk menjaga keamanan dan kelancaran sistem.
                - Aktivitas dalam sistem dapat dicatat untuk keperluan audit dan keamanan.

                4. Kewajiban Pengguna
                - Pengguna wajib menggunakan sistem sesuai dengan prosedur dan tujuan yang ditetapkan.
                - Pengguna dilarang memberikan informasi palsu atau melakukan tindakan yang dapat mengganggu operasional sistem.

                5. Privasi dan Penggunaan Data
                - Data pengguna hanya digunakan untuk keperluan operasional layanan dan tidak akan dibagikan kepada pihak ketiga tanpa persetujuan, kecuali diwajibkan oleh hukum.
                - Pengguna menyetujui bahwa data yang disimpan dalam sistem dapat diolah untuk peningkatan layanan.

                6. Perubahan Ketentuan
                - Pengelola berhak mengubah Syarat dan Ketentuan ini sewaktu-waktu. Perubahan akan diberitahukan melalui sistem.

                Dengan menggunakan layanan ini, pengguna dianggap telah membaca, memahami, dan menyetujui seluruh Syarat dan Ketentuan di atas.

                TEXT
            ],
        ];

        foreach ($datas as $data) {
            Settings::createOrFirst($data);
        }
    }
}
