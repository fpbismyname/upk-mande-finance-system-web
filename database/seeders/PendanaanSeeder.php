<?php

namespace Database\Seeders;

use App\Models\Pendanaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PendanaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $AddedPendanaan = Pendanaan::get()->first();
            if(!$AddedPendanaan){
                Pendanaan::create([
                'saldo' => 0,
            ]);
        }
    }
}
