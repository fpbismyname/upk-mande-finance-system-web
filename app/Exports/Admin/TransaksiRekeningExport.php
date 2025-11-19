<?php

namespace App\Exports\Admin;

use App\Models\TransaksiRekening;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiRekeningExport implements FromCollection, WithHeadings
{
    public function __construct(
        public Collection $data_transaksi
    ) {
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->data_transaksi->map(function ($transaction) {
            return [
                'Rekening' => $transaction->rekening->formatted_name,
                'Nominal' => $transaction->nominal,
                'Tipe Transaksi' => $transaction->formatted_tipe_transaksi,
                'Keterangan' => $transaction->keterangan,
                'Tanggal Transaksi' => $transaction->formatted_tanggal_transaksi,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'Rekening',
            'Nominal',
            'Tipe Transaksi',
            'Keterangan',
            'Tanggal Transaksi',
        ];
    }
}
