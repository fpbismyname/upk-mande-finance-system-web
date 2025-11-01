<?php

namespace App\Services\Admin\AnggotaKelompok;
use App\Models\Kelompok;
use Illuminate\Support\Facades\DB;

class AnggotaKelompokService
{
    protected $kelompok;
    public function __construct($id_kelompok)
    {
        $this->kelompok = Kelompok::findOrFail($id_kelompok);
    }
    public function add_anggota($datas)
    {
        if (empty($datas))
            return false;
        return DB::transaction(function () use ($datas) {
            $create_anggota = $this->kelompok->anggota_kelompok()->create($datas);
            return $create_anggota->wasRecentlyCreated;
        });
    }
    public function update_anggota($id, $datas)
    {
        if (empty($datas) || empty($id))
            return false;
        return DB::transaction(function () use ($id, $datas) {
            $current_anggota = $this->kelompok->anggota_kelompok()
                ->findOrFail($id);
            return $current_anggota->update($datas);
        });
    }
    public function delete_anggota($id)
    {
        if (empty($id))
            return false;
        return DB::transaction(function () use ($id) {
            $current_anggota = $this->kelompok->anggota_kelompok()
                ->findOrFail($id);
            return $current_anggota->delete();
        });
    }
}