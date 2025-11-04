<?php

namespace Database\Seeders;

use App\Enum\Admin\User\EnumRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'nik' => '4259322081123131',
                'name' => 'Administrasi',
                'email' => 'admin@gmail.com',
                'password' => 'admin123',
                'alamat' => '204 Davsu Junction',
                'nomor_telepon' => "089542352354",
                'role' => EnumRole::ADMIN
            ],
            [
                'nik' => '1761933777123141',
                'name' => 'Kepala UPK',
                'email' => 'kepalaupkmande@gmail.com',
                'password' => 'executive123',
                'alamat' => '292 Atonut Extension',
                'nomor_telepon' => "089253233354",
                'role' => EnumRole::KEPALA_INSTITUSI
            ],
            [
                'nik' => '1237496597123131',
                'name' => 'Keuangan UPK',
                'email' => 'akuntanupkmande@gmail.com',
                'password' => 'akuntan123',
                'alamat' => '1816 Puvjaj Glen',
                'nomor_telepon' => "0895235235354",
                'role' => EnumRole::AKUNTAN
            ],
            [
                'nik' => '1241421241414141',
                'name' => 'Pengelola Pendanaan UPK',
                'email' => 'pendanaanupkmande@gmail.com',
                'password' => 'pendanaan123',
                'alamat' => '598 Taab Street',
                'nomor_telepon' => "0892352525334",
                'role' => EnumRole::PENGELOLA_DANA
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
