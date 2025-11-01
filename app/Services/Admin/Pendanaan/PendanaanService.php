<?php

namespace App\Services\Admin\Pendanaan;

use App\Models\Pendanaan;
use App\Services\Admin\CatatanPendanaan\CatatanPendanaanService;
use Illuminate\Support\Facades\DB;

class PendanaanService
{
    protected Pendanaan $model;
    protected CatatanPendanaanService $catatan_pendanaan;
    protected $saldo_terakhir;
    const PRECISION = 15;
    const SCALE = 2;
    public function __construct()
    {
        $this->model = Pendanaan::firstOrFail();
        $this->catatan_pendanaan = new CatatanPendanaanService();
        $this->saldo_terakhir = $this->model->firstOrFail()->saldo;
    }
    public function validasi_pendanaan($amount)
    {
        $maxValue = pow(10, (self::PRECISION - self::SCALE)) - pow(10, -self::SCALE);

        if ($amount > $maxValue) {
            return false;
        }
        return true;
    }
    public function tambah_saldo($amount_saldo, $notes)
    {
        // Validasi batas tambah saldo
        $validasi_pendanaan = $this->validasi_pendanaan($amount_saldo);

        // Ketika validasi gagal kirimkan message error
        if (!$validasi_pendanaan) {
            return [
                'message' => "Nominal tidak boleh lebih dari 15 digit (Maksimal : Rp 999,999,999,999)"
            ];
        }

        // Mulai eksekusi db
        $transaction = DB::transaction(function () use ($amount_saldo, $notes) {
            // Tambahkan jumlah saldo
            $this->model->increment('saldo', $amount_saldo);
            return $this->catatan_pendanaan->catat_pemasukan($amount_saldo, $notes);
        });
        
        if (!$transaction) {
            return false;
        }
        return true;
    }

    public function kurangi_saldo($amount_saldo, $notes)
    {
        // Validasi tarik saldo tidak melebihi saldo pendanaan
        if ($amount_saldo > $this->saldo_terakhir) {
            return [
                'message' => 'Saldo tidak cukup untuk ditarik'
            ];
        }

        // Mulai eksekusi db
        $transaction = DB::transaction(function () use ($amount_saldo, $notes) {
            // Kurangi jumlah saldo
            $this->model->decrement('saldo', $amount_saldo);
            return $this->catatan_pendanaan->catat_pengeluaran($amount_saldo, $notes);
        });

        if (!$transaction) {
            return false;
        }

        return true;
    }
}