<?php

namespace Database\Seeders;

use App\Enums\Admin\User\EnumRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'nik' => '',
                'name' => 'Administrasi',
                'email' => 'administrasi@gmail.com',
                'password' => 'admin123',
                'alamat' => '204 Davsu Junction',
                'nomor_telepon' => "089542352354",
                'role' => EnumRole::ADMIN
            ],
            [
                'nik' => '',
                'name' => 'Kepala UPK',
                'email' => 'kepalaupkmande@gmail.com',
                'password' => 'kepalaupk123',
                'alamat' => '292 Atonut Extension',
                'nomor_telepon' => "089253233354",
                'role' => EnumRole::KEPALA_INSTITUSI
            ],
            [
                'nik' => '',
                'name' => 'Akuntan UPK',
                'email' => 'akuntanupkmande@gmail.com',
                'password' => 'akuntan123',
                'alamat' => '1816 Puvjaj Glen',
                'nomor_telepon' => "0895235235354",
                'role' => EnumRole::AKUNTAN
            ],
            [
                'nik' => '',
                'name' => 'Pengelola Pendanaan UPK',
                'email' => 'pendanaanupkmande@gmail.com',
                'password' => 'pendanaan123',
                'alamat' => '598 Taab Street',
                'nomor_telepon' => "0892352525334",
                'role' => EnumRole::PENGELOLA_DANA
            ],
            [
                'nik' => '',
                'name' => 'Niki fernando',
                'email' => 'niki@gmail.com',
                'password' => 'niki123',
                'alamat' => 'Jl. pasar senin, kp rambutan',
                'nomor_telepon' => "0817623512313",
                'role' => EnumRole::ANGGOTA
            ],
        ];
        foreach ($datas as $data) {
            $findAddedData = User::query()->where('name', $data['name'])->get()->isNotEmpty();
            if (!$findAddedData) {
                User::create($data);
            }
        }
    }
}
