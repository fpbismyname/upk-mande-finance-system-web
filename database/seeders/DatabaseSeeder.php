<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Status\StatusCicilanSeeder;
use Database\Seeders\Status\StatusJadwalPencairanSeeder;
use Database\Seeders\Status\StatusKelompokSeeder;
use Database\Seeders\Status\StatusPengajuanPinjamanSeeder;
use Database\Seeders\Status\StatusPinjamanSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            StatusPengajuanPinjamanSeeder::class,
            StatusCicilanSeeder::class,
            StatusKelompokSeeder::class,
            StatusPinjamanSeeder::class,
            StatusJadwalPencairanSeeder::class,
            PendanaanSeeder::class,
            KelompokSeeder::class,
        ]);
    }
}
