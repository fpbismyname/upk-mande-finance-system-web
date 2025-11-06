<?php

namespace App\Console\Commands;

use App\Enum\Admin\PengajuanPinjaman\EnumTenor;
use App\Enum\Admin\Status\EnumStatusCicilanKelompok;
use App\Enum\Admin\Status\EnumStatusJadwalPencairan;
use App\Enum\Admin\Status\EnumStatusPinjaman;
use App\Models\CicilanKelompok;
use App\Models\JadwalPencairan;
use App\Models\Pendanaan;
use App\Models\PinjamanKelompok;
use App\Models\SukuBungaFlat;
use App\Services\Admin\Pendanaan\PendanaanService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\progress;

class CheckJadwalPencairan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jadwal-pencairan:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek jadwal pencairan apakah sudah jatuh tempo';

    /**
     * Execute the console command.
     */
    public function handle(PendanaanService $pendanaan_service)
    {
        $this->info('Melakukan cek jadwal pencairan...');

        $today = now();
        $suku_bunga = SukuBungaFlat::first();
        $data_jadwal_pencairan = JadwalPencairan::where('status', EnumStatusJadwalPencairan::TERJADWAL)->get();

        $this->withProgressBar($data_jadwal_pencairan, function ($jadwal) use ($today, $suku_bunga, $pendanaan_service) {
            // Check jadwal pencairan is overdue 
            if ($jadwal->where('tanggal_pencairan', '>=', $today->clone())) {
                // Update status
                $jadwal->update([
                    'status' => EnumStatusJadwalPencairan::TELAH_DICAIRKAN
                ]);
                // Tambahkan data pinjaman
                $bunga = $suku_bunga->jumlah / 100;
                $nominal_pinjaman_final = $jadwal->pengajuan_nominal * (1 + $bunga);
                $tanggal_pencairan = $jadwal->tanggal_pencairan;
                $data_pinjaman = [
                    'status' => EnumStatusPinjaman::BERLANGSUNG,
                    'tenor' => $jadwal->pengajuan_tenor,
                    'bunga' => $suku_bunga->jumlah,
                    'nominal_pinjaman' => $jadwal->pengajuan_nominal,
                    'nominal_pinjaman_final' => $nominal_pinjaman_final,
                    'tanggal_mulai' => $tanggal_pencairan,
                    'tanggal_jatuh_tempo' => $tanggal_pencairan->copy()->addMonths($jadwal->pengajuan_tenor->value),
                    'kelompok_id' => $jadwal->kelompok_id
                ];
                $added_pinjaman = PinjamanKelompok::create($data_pinjaman);
                // Potong saldo pendanaan
                $pendanaan_service->kurangi_saldo($data_pinjaman['nominal_pinjaman'], "Pencairan saldo untuk pinjaman {$added_pinjaman->kelompok_name}");
                // Buat data cicilan
                for ($i = 1; $i <= $jadwal->pengajuan_tenor->value; $i++) {
                    CicilanKelompok::create([
                        'status' => EnumStatusCicilanKelompok::BELUM_BAYAR->value,
                        'nominal_cicilan' => $nominal_pinjaman_final / $jadwal->pengajuan_tenor->value,
                        'bukti_pembayaran' => null,
                        'tanggal_tanggal_bayar' => null,
                        'tanggal_jatuh_tempo' => $today->clone()->addMonths($i),
                        'pinjaman_kelompok_id' => $added_pinjaman->id
                    ]);
                }
            }
        });

        $this->newLine();
        $this->info('Cek jadwal pencairan selesai.');
    }
}
