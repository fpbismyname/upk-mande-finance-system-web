<?php

namespace App\Exports\Admin;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PinjamanKelompokExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected Collection $data_pinjaman_kelompok;
    public function __construct($filterred_data_pinjaman_kelompok)
    {
        $this->data_pinjaman_kelompok = $filterred_data_pinjaman_kelompok;
    }
    public function collection()
    {
        return $this->data_pinjaman_kelompok->map(function ($data) {
            return [
                'Pinjaman kelompok' => $data->kelompok->name,
                'Bunga pinjaman' => $data->formatted_bunga,
                'Nominal pinjaman' => $data->nominal_pinjaman,
                'Nominal pinjaman final' => $data->nominal_pinjaman_final,
                'Tanggal mulai' => $data->formatted_tanggal_mulai,
                'Tanggal jatuh tempo' => $data->formatted_tanggal_jatuh_tempo,
                'Status Pinjaman' => $data->formatted_status,
                'Progress Cicilan' => $data->progres_cicilan,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'Pinjaman kelompok',
            'Bunga pinjaman',
            'Nominal pinjaman',
            'Nominal pinjaman final',
            'Tanggal mulai',
            'Tanggal jatuh tempo',
            'Status Pinjaman',
            'Progress Cicilan',
        ];
    }
}
