<?php

namespace App\Exports\Admin;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengajuanPinjamanExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected Collection $data_pengajuan_pinjaman;
    public function __construct($filterred_data_pengajuan_pinjaman)
    {
        $this->data_pengajuan_pinjaman = $filterred_data_pengajuan_pinjaman;
    }
    public function collection()
    {
        return $this->data_pengajuan_pinjaman->map(function ($data) {
            return [
                'Pengajuan kelompok' => $data->kelompok->name,
                'Link file proposal' => $data->formatted_link_file_proposal,
                'Nominal pinjaman' => $data->nominal_pinjaman,
                'Tenor pinjaman' => $data->formatted_tenor,
                'Catatan Kepala Upk' => $data->catatan,
                'Tanggal pengajuan' => $data->formatted_tanggal_pengajuan,
                'Tanggal disetujui' => $data->formatted_tanggal_disetujui,
                'Tanggal ditolak' => $data->formatted_ditolak,
                'Status pengajuan' => $data->formatted_status,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'Pengajuan kelompok',
            'Link file proposal',
            'Nominal pinjaman',
            'Tenor pinjaman',
            'Catatan Kepala Upk',
            'Tanggal pengajuan',
            'Tanggal disetujui',
            'Tanggal ditolak',
            'Status pengajuan',
        ];
    }
}
