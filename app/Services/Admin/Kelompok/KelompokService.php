<?php

namespace App\Services\Admin\Kelompok;
use App\Models\Kelompok;
use Illuminate\Support\Facades\DB;

class KelompokService
{
    protected Kelompok $model;
    public function __construct()
    {
        $this->model = new Kelompok();
    }
    public function add_kelompok($datas)
    {
        if (empty($datas))
            return false;
        return DB::transaction(function () use ($datas) {
            $this->model->create($datas);
            return true;
        });
    }
    public function update_kelompok($datas, $id)
    {
        if (empty($datas) || empty($id))
            return false;
        return DB::transaction(function () use ($datas, $id) {
            $current_data = $this->model->findOrFail($id);
            $current_data->update($datas);
            return true;
        });
    }
    public function delete_kelompok($id)
    {
        if (empty($id))
            return false;
        return DB::transaction(function () use ($id) {
            $current_data = $this->model->findOrFail($id)->delete();
            return $current_data;
        });
    }
}