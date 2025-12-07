<?php

namespace Database\Seeders;

use App\Enums\Admin\Status\EnumStatusKelompok;
use App\Enums\Admin\Status\EnumStatusPengajuanKeanggotaan;
use App\Enums\Admin\Status\EnumStatusPengajuanPinjaman;
use App\Enums\Admin\User\EnumRole;
use App\Models\Kelompok;
use App\Models\PengajuanKeanggotaan;
use App\Models\User;
use Arr;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Admin users 

        $datas = [
            [
                'name' => 'Administrasi',
                'email' => 'administrasi@gmail.com',
                'password' => 'admin123',
                'role' => EnumRole::ADMIN
            ],
            [
                'name' => 'Kepala UPK',
                'email' => 'kepalaupkmande@gmail.com',
                'password' => 'kepalaupk123',
                'role' => EnumRole::KEPALA_INSTITUSI
            ],
            [
                'name' => 'Akuntan UPK',
                'email' => 'akuntanupkmande@gmail.com',
                'password' => 'akuntan123',
                'role' => EnumRole::AKUNTAN
            ],
            [
                'name' => 'Pengelola Pendanaan UPK',
                'email' => 'pendanaanupkmande@gmail.com',
                'password' => 'pendanaan123',
                'role' => EnumRole::PENGELOLA_DANA
            ],
            [
                'name' => 'Niki fernando',
                'email' => 'niki@gmail.com',
                'password' => 'niki123',
                'role' => EnumRole::ANGGOTA
            ],
        ];

        foreach ($datas as $data) {
            $findAddedData = User::query()->where('name', $data['name'])->get()->isNotEmpty();
            if (!$findAddedData) {
                $user = User::create($data);
                if ($user->role === EnumRole::AKUNTAN || $user->role === EnumRole::PENGELOLA_DANA) {
                    $user->pengajuan_keanggotaan()->create([
                        'nomor_rekening' => fake()->numerify('41##########'),
                        'status' => EnumStatusPengajuanKeanggotaan::DISETUJUI
                    ]);
                }
            }
        }

        // Client users
        $jumlah_users = 20;
        $users = User::factory()
            ->count($jumlah_users)
            ->create();

        foreach ($users as $user) {
            $user->pengajuan_keanggotaan()->create([
                'users_id' => $user->id,
                'nama_lengkap' => $user->name,
                'nik' => fake()->numerify(str_repeat('#', 16)),
                'ktp' => 'default/ktp.jpg',
                'alamat' => fake()->unique()->address(),
                'nomor_telepon' => fake()->unique()->numerify('08##########'),
                'nomor_rekening' => fake()->numerify('##############'),
                'status' => Arr::random([EnumStatusPengajuanKeanggotaan::DISETUJUI, EnumStatusPengajuanKeanggotaan::PROSES_PENGAJUAN])
            ]);
        }

        User::doesntHave('kelompok')->clientUsers()->has('pengajuan_keanggotaan_disetujui')->each(function ($user) {
            $kelompok = $user->kelompok()->create([
                'name' => fake()->words(2, true),
                'limit_per_anggota' => 2000000,
                'status' => EnumStatusKelompok::AKTIF,
            ]);

            $anggota_kelompok = [];
            $jumlah_anggota = 10;

            for ($i = 0; $i < $jumlah_anggota; $i++) {
                $anggota_kelompok[] = [
                    'kelompok_id' => $kelompok->id,
                    'nik' => fake()->unique()->numerify(str_repeat('#', 16)),
                    'name' => fake()->name,
                    'alamat' => fake()->unique()->address(),
                    'nomor_telepon' => fake()->unique()->numerify('08#########'),
                ];
            }

            foreach ($anggota_kelompok as $anggota) {
                $kelompok->anggota_kelompok()->create($anggota);
            }
        });
    }
}
