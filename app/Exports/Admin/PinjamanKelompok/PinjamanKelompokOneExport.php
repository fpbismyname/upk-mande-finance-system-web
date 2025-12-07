<?php

namespace App\Exports\Admin\PinjamanKelompok;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PinjamanKelompokOneExport implements WithMultipleSheets
{
    protected $pinjaman;
    protected $cicilan;

    public function __construct($pinjaman, $cicilan)
    {
        $this->pinjaman = $pinjaman;
        $this->cicilan = $cicilan;
    }

    public function sheets(): array
    {
        return [
            new PinjamanSheet($this->pinjaman),
            new CicilanSheet($this->cicilan),
        ];
    }
}
