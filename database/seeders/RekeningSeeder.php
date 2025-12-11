<?php

namespace Database\Seeders;

use App\Enums\Admin\Rekening\EnumRekening;
use App\Enums\Admin\Rekening\EnumTipeTransaksi;
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
            $rekening_akuntan = Rekening::create([
                'name' => EnumRekening::PENGELOLA_DANA,
                'saldo' => 50000000.00,
            ]);

            if ($rekening_akuntan->wasRecentlyCreated) {
                $rekening_akuntan->transaksi_rekening()->create([
                    'nominal' => $rekening_akuntan->saldo,
                    'keterangan' => 'Pendanaan awal',
                    'tipe_transaksi' => EnumTipeTransaksi::MASUK,
                ]);
            }

            Rekening::create([
                'name' => EnumRekening::AKUNTAN,
                'saldo' => 0,
            ]);
        }
    }
}
