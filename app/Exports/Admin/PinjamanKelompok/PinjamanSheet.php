<?php

namespace App\Exports\Admin\PinjamanKelompok;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PinjamanSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return ['Id', 'Nama kelompok', 'Jumlah', 'Tanggal'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->kelompok_name,
            number_format($row->nominal_pinjaman),
            $row->created_at->format('Y-m-d'),
        ];
    }

    public function title(): string
    {
        return 'Pinjaman';
    }
}
