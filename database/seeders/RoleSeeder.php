<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['name' => 'tamu'],
            ['name' => 'anggota'],
            ['name' => 'admin'],
            ['name' => 'kepala_institusi'],
            ['name' => 'akuntan'],
            ['name' => 'pengelola_dana']
        ];
        foreach($datas as $data){
            $findAddedData = Roles::query()->where('name',$data['name'])->get()->isNotEmpty();
            if(!$findAddedData){
                Roles::create($data);
            }
        }
    }
}
