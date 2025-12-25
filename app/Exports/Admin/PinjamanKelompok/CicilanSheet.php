<?php

namespace App\Exports\Admin\PinjamanKelompok;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class CicilanSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return ['Id cicilan', 'Id pinjaman', 'Jumlah cicilan', 'Bukti pembayaran', 'Tanggal bayar', 'Status cicilan'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->pinjaman_kelompok_id,
            number_format($row->nominal_cicilan),
            $row->link_bukti_pembayaran,
            $row->formatted_tanggal_dibayar,
            $row->status->label(),
        ];
    }
    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'Cicilan';
    }

}
