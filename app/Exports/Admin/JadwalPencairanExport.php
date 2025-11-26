<?php

namespace App\Exports\Admin;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JadwalPencairanExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected Collection $data_jadwal_pencairan;
    public function __construct($filterred_data_jadwal_pencairan)
    {
        $this->data_jadwal_pencairan = $filterred_data_jadwal_pencairan;
    }
    public function collection()
    {
        return $this->data_jadwal_pencairan->map(function ($data) {
            return [
                'Pencairan Kelompok' => $data->pengajuan_pinjaman->kelompok->name,
                'Nominal pencairan' => $data->pengajuan_pinjaman->nominal_pinjaman,
                'Status pencairan' => $data->formatted_status,
                'Tanggal pencairan' => $data->formatted_tanggal_pencairan,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'Pencairan Kelompok',
            'Nominal pencairan',
            'Status pencairan',
            'Tanggal pencairan',
        ];
    }
}
