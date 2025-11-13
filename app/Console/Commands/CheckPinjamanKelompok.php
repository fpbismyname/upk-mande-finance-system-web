<?php

namespace App\Console\Commands;

use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Enums\Admin\Status\EnumStatusPinjaman;
use App\Models\CicilanKelompok;
use App\Models\PinjamanKelompok;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class CheckPinjamanKelompok extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinjaman-kelompok:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek jatuh tempo pinjaman kelompok';

    /**
     * Execute the console command.
     */
    public function handle(PinjamanKelompok $pinjaman_kelompok_model)
    {
        $this->info('Memulai cek pinjaman kelompok.');
        $this->newLine();

        $pinjaman_kelompok = $pinjaman_kelompok_model
            ->with('cicilan_kelompok')
            ->get();

        $this->withProgressBar($pinjaman_kelompok, function (PinjamanKelompok $pinjaman) {
            // Kondisi pinjaman
            $cicilan_lunas = false;
            $pinjaman_lunas = false;
            $pinjaman_cicilan_sudah_lunas = $pinjaman->filterCicilanSudahBayarCount()->get()->filter(fn($pinjaman) => $pinjaman->cicilan_sudah_bayar_count == $pinjaman->tenor->value);
            // Cek cicilan pinjaman jatuh tempo
            $cicilan_jatuh_tempo = $pinjaman->cicilan_kelompok()->filterCicilanJatuhTempo()->latest('tanggal_jatuh_tempo')->get();
            foreach ($cicilan_jatuh_tempo as $cicilan) {
                if ($cicilan->status === EnumStatusCicilanKelompok::BELUM_BAYAR) {
                    $cicilan->status = EnumStatusCicilanKelompok::TELAT_BAYAR;
                    $cicilan->save();
                    $cicilan_lunas = false;
                } else {
                    $cicilan_lunas = true;
                }
            }
            // Check pinjaman jatuh tempo
            $pinjaman_jatuh_tempo = $pinjaman->filterPinjamanJatuhTempo()->latest('tanggal_jatuh_tempo')->get();
            foreach ($pinjaman_jatuh_tempo as $pinjaman) {
                if ($pinjaman->status === EnumStatusPinjaman::BERLANGSUNG) {
                    $pinjaman->status = EnumStatusPinjaman::MENUNGGAK;
                    $pinjaman->save();
                    $pinjaman_lunas = false;
                } else {
                    $pinjaman_lunas = true;
                }
            }
            // Check semua cicilan pinjaman
            foreach ($pinjaman_cicilan_sudah_lunas as $pinjaman_lunas) {
                $pinjaman_lunas->status = EnumStatusPinjaman::SELESAI;
                $pinjaman_lunas->save();
            }
            // Set status
            if ($cicilan_lunas && $pinjaman_lunas) {
                $pinjaman->status = EnumStatusPinjaman::SELESAI;
                $pinjaman->save();
            }
        });

        $this->newLine();
        $this->info('Cek pinjaman kelompok selesai.');
    }
}
