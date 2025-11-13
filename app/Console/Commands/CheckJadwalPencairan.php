<?php

namespace App\Console\Commands;

use App\Enums\Admin\CatatanPendanaan\EnumCatatanPendanaan;
use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Enums\Admin\Status\EnumStatusJadwalPencairan;
use App\Enums\Admin\Status\EnumStatusPinjaman;
use App\Models\CatatanPendanaan;
use App\Models\CicilanKelompok;
use App\Models\JadwalPencairan;
use App\Models\Pendanaan;
use App\Models\PinjamanKelompok;
use App\Models\Settings;
use Illuminate\Console\Command;

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
    public function handle(
        Pendanaan $pendanaan_model,
        Settings $settings_model,
        PinjamanKelompok $pinjaman_kelompok_model,
        CicilanKelompok $cicilan_kelompok_model,
        CatatanPendanaan $catatan_pendanaan_model
    ) {
        $this->info('Melakukan cek jadwal pencairan...');

        $today = now();
        $bunga_pinjaman = $settings_model->where('key', EnumSettingKeys::BUNGA_PINJAMAN)->first()->value('value');
        $data_jadwal_pencairan = JadwalPencairan::where('status', EnumStatusJadwalPencairan::TERJADWAL)
            ->with('pengajuan_pinjaman.kelompok')
            ->get();

        $this->withProgressBar($data_jadwal_pencairan, function ($jadwal) use ($today, $bunga_pinjaman, $pendanaan_model, $pinjaman_kelompok_model, $cicilan_kelompok_model, $catatan_pendanaan_model) {
            // Check jadwal pencairan is overdue 
            if ($jadwal->tanggal_pencairan <= $today->clone() && $jadwal->filterStatusJadwal(EnumStatusJadwalPencairan::TERJADWAL)) {
                // Update status
                $jadwal->update([
                    'status' => EnumStatusJadwalPencairan::TELAH_DICAIRKAN
                ]);
                // data pengajuan
                $pengajuan_pinjaman = $jadwal->pengajuan_pinjaman;
                // Tambahkan data pinjaman
                $bunga = $bunga_pinjaman / 100;
                $nominal_pinjaman_final = $jadwal->pengajuan_nominal * (1 + $bunga);
                $tanggal_pencairan = $jadwal->tanggal_pencairan;
                $data_pinjaman = [
                    'status' => EnumStatusPinjaman::BERLANGSUNG,
                    'tenor' => $jadwal->pengajuan_tenor,
                    'bunga' => $bunga_pinjaman,
                    'nominal_pinjaman' => $jadwal->pengajuan_nominal,
                    'nominal_pinjaman_final' => $nominal_pinjaman_final,
                    'tanggal_mulai' => $tanggal_pencairan,
                    'tanggal_jatuh_tempo' => $tanggal_pencairan->copy()->addMonths($jadwal->pengajuan_tenor->value),
                    'kelompok_id' => $pengajuan_pinjaman->kelompok_id,
                    'pengajuan_pinjaman_id' => $jadwal->pengajuan_pinjaman_id,
                    'jadwal_pencairan_id' => $jadwal->id,
                ];
                $added_pinjaman = $pinjaman_kelompok_model->create($data_pinjaman);
                // Potong saldo pendanaan
                $pendanaan = $pendanaan_model->first();
                $pendanaan->decrement('saldo', $data_pinjaman['nominal_pinjaman']);
                // Catatan transaksi pendanaan
                $nama_kelompok = $jadwal->kelompok_name;
                $catatan_pendanaan_model->create([
                    'tipe_catatan' => EnumCatatanPendanaan::OUTFLOW,
                    'jumlah_saldo' => $data_pinjaman['nominal_pinjaman'],
                    'catatan' => "Pencairan pinjaman untuk kelompok {$nama_kelompok}"
                ]);
                // Buat data cicilan
                for ($i = 1; $i <= $jadwal->pengajuan_tenor->value; $i++) {
                    $cicilan_kelompok_model->create([
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
