<?php

namespace App\Services\Admin\CatatanPendanaan;

use App\Models\CatatanPendanaan;
use Illuminate\Support\Facades\DB;

class CatatanPendanaanService
{
    const INFLOW = 'pemasukan';
    const OUTFLOW = 'pengeluaran';
    protected CatatanPendanaan $model;
    public function __construct()
    {
        $this->model = new CatatanPendanaan();
    }
    public function catat_pemasukan($amount, string|null $notes = null): bool
    {
        // Data catatan pemasukan
        $notes_inflow = [
            'jumlah_saldo' => $amount,
            'catatan' => $notes ?? "-",
            'tipe_catatan' => self::INFLOW
        ];
        // Mulai eksekusi db
        return DB::transaction(function () use ($notes_inflow) {
            // Buat catatan pengeluaran
            return $this->model->create($notes_inflow) ? true : false;
        });
    }
    public function catat_pengeluaran($amount, string|null $notes = null): bool
    {
        // Data catatan pemasukan
        $notes_outflow = [
            'jumlah_saldo' => $amount,
            'catatan' => $notes ?? "-",
            'tipe_catatan' => self::OUTFLOW
        ];
        // Mulai eksekusi db
        return DB::transaction(function () use ($notes_outflow) {
            // Buat catatan pengeluaran
            return $this->model->create($notes_outflow) ? true : false;
        });
    }
}