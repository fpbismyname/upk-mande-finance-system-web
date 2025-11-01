<?php

namespace App\Services\Admin\JadwalPencairan;

use App\Models\JadwalPencairan;

class JadwalPencairanService
{
    protected JadwalPencairan $model;
    public function __construct()
    {
        $this->model = new JadwalPencairan();
    }
    public function make_schedule()
    {

    }
}