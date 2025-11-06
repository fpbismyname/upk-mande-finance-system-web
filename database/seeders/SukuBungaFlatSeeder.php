<?php

namespace Database\Seeders;

use App\Models\SukuBungaFlat;
use Illuminate\Database\Seeder;

class SukuBungaFlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = ['jumlah' => 18.00];
        SukuBungaFlat::createOrFirst($datas);
    }
}
