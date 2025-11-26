<?php

namespace Database\Seeders;

use App\Enums\Admin\Rekening\EnumRekening;
use App\Models\Rekening;
use Illuminate\Database\Seeder;

class RekeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Pendanaan = Rekening::get_rekening_pendanaan()->first();
        if (!$Pendanaan) {
            Rekening::create([
                'name' => EnumRekening::PENGELOLA_DANA,
                'saldo' => 50000000.00,
            ]);
            Rekening::create([
                'name' => EnumRekening::AKUNTAN,
                'saldo' => 0,
            ]);
        }
    }
}
