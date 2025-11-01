<?php

namespace App\Http\Controllers\Admin\JadwalPencairan;

use App\Http\Controllers\Controller;

class ViewJadwalPencairan extends Controller
{
    public function __invoke($id)
    {
        return view('admin.pages.jadwal-pencairan.view');
    }
}
