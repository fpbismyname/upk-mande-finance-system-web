<?php

namespace App\Console\Commands;

use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Enums\Admin\Status\EnumStatusPinjaman;
use App\Models\CicilanKelompok;
use App\Models\PinjamanKelompok;
use Illuminate\Console\Command;

class CheckPinjamanKelompok extends Command
{
    /**
     * Nama dan tanda tangan perintah konsol.
     *
     * @var string
     */
    protected $signature = 'pinjaman-kelompok:check';

    /**
     * Deskripsi perintah konsol.
     *
     * @var string
     */
    protected $description = 'Cek jatuh tempo pinjaman kelompok';

    /**
     * Jalankan perintah konsol.
     */
    public function handle(PinjamanKelompok $pinjaman_kelompok_model)
    {
        $this->info('Memulai cek pinjaman kelompok.');
        $this->newLine();

        // Memuat cicilan_kelompok
        $pinjaman_kelompok = $pinjaman_kelompok_model->with('cicilan_kelompok')->get();

        // Cek pinjaman kelompok
        $this->info('Cek pinjaman yang jatuh tempo.');
        $this->withProgressBar($pinjaman_kelompok, function (PinjamanKelompok $pinjaman) {
            // Cek cicilan pinjaman
            $status_cicilan_pinjaman_lunas = $pinjaman->cicilan_kelompok()->get()->every(function ($cicilan) {
                return $cicilan->status === EnumStatusCicilanKelompok::SUDAH_BAYAR;
            });

            // Cek pinjaman dan cicilan
            $pinjaman_jatuh_tempo = $pinjaman->pinjaman_jatuh_tempo()->get();
            $cicilan_pinjaman_jatuh_tempo = $pinjaman->cicilan_kelompok()->cicilan_jatuh_tempo()->get();

            // Ubah status semua cicilan yang jatuh tempo
            if ($cicilan_pinjaman_jatuh_tempo->isNotEmpty()) {
                foreach ($cicilan_pinjaman_jatuh_tempo as $cicilan) {
                    $cicilan->status = EnumStatusCicilanKelompok::TELAT_BAYAR;
                    $cicilan->save();
                }
            }

            // Ubah status pinjaman yang jatuh tempo
            if ($pinjaman_jatuh_tempo->isNotEmpty()) {
                foreach ($pinjaman_jatuh_tempo as $pinjaman) {
                    $pinjaman->status = EnumStatusPinjaman::MENUNGGAK;
                    $pinjaman->save();
                }
            }

            // Ubah status pinjaman yang sudah menyelesaikan cicilan
            if ($status_cicilan_pinjaman_lunas) {
                $pinjaman->status = EnumStatusPinjaman::SELESAI;
                $pinjaman->save();
            } else {
                $pinjaman->status = EnumStatusPinjaman::BERLANGSUNG;
                $pinjaman->save();
            }
        });

        $this->newLine();
        $this->info('Cek pinjaman kelompok selesai.');
    }
}
