<?php

namespace App\Console\Commands;

use App\Enums\Admin\CatatanPendanaan\EnumCatatanPendanaan;
use App\Enums\Admin\Rekening\EnumTipeTransaksi;
use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Enums\Admin\Status\EnumStatusJadwalPencairan;
use App\Enums\Admin\Status\EnumStatusPinjaman;
use App\Models\CatatanPendanaan;
use App\Models\CicilanKelompok;
use App\Models\JadwalPencairan;
use App\Models\Pendanaan;
use App\Models\PinjamanKelompok;
use App\Models\PengajuanPinjaman;
use App\Models\Rekening;
use App\Models\Settings;
use App\Models\TransaksiRekening;
use Illuminate\Console\Command;

class CheckJadwalPencairan extends Command
{
    /**
     * Nama dan tanda tangan perintah konsol.
     *
     * @var string
     */
    protected $signature = 'jadwal-pencairan:check';

    /**
     * The console command description.
     * Deskripsi perintah konsol.
     * @var string
     */
    protected $description = 'Cek jadwal pencairan apakah sudah jatuh tempo';

    /**
     * Execute the console command.
     */
    public function handle(
        Rekening $rekening_model,
        Settings $settings_model,
        PinjamanKelompok $pinjaman_kelompok_model,
        CicilanKelompok $cicilan_kelompok_model,
        JadwalPencairan $jadwal_pencairan_model
    ) { // Jalankan perintah konsol.
        $this->info('Melakukan cek jadwal pencairan...');

        $today = now();
        $bunga_pinjaman = (float) $settings_model->getKeySetting(EnumSettingKeys::BUNGA_PINJAMAN)->value('value');

        // Eager load relationships to reduce N+1 queries
        $data_jadwal_pencairan = $jadwal_pencairan_model->jadwal_pencairan_jatuh_tempo()->get();

        if ($data_jadwal_pencairan->isNotEmpty()) {
            $pendanaan = $rekening_model->get_rekening_pendanaan()->first();

            if (!$pendanaan) {
                $this->error('Rekening pendanaan tidak ditemukan.');
            }

            $this->withProgressBar($data_jadwal_pencairan, function ($jadwal) use ($today, $bunga_pinjaman, $pendanaan, $pinjaman_kelompok_model, $cicilan_kelompok_model) {
                // data pengajuan
                $pengajuan_pinjaman = $jadwal->pengajuan_pinjaman; // Ambil data pengajuan pinjaman

                if (!$pengajuan_pinjaman) {
                    $this->warn("Pengajuan pinjaman untuk jadwal ID {$jadwal->id} tidak ditemukan. Melewati."); // Peringatan jika pengajuan pinjaman tidak ditemukan
                    return;
                }

                // Hitung jumlah pinjaman akhir
                $tanggal_pencairan = $jadwal->tanggal_pencairan; // Ambil tanggal pencairan

                // Buat entri PinjamanKelompok
                $added_pinjaman = $pinjaman_kelompok_model->create([
                    'status' => EnumStatusPinjaman::BERLANGSUNG,
                    'tenor' => $pengajuan_pinjaman->tenor,
                    'bunga' => $bunga_pinjaman,
                    'nominal_pinjaman' => $pengajuan_pinjaman->nominal_pinjaman,
                    'tanggal_mulai' => $tanggal_pencairan,
                    'tanggal_jatuh_tempo' => $tanggal_pencairan->copy()->addMonths($pengajuan_pinjaman->tenor->value),
                    'kelompok_id' => $pengajuan_pinjaman->kelompok_id,
                    'pengajuan_pinjaman_id' => $jadwal->pengajuan_pinjaman_id,
                    'jadwal_pencairan_id' => $jadwal->id,
                ]);

                // Record funding transaction
                $pendanaan->transaksi_rekening()->create([
                    'tipe_transaksi' => EnumTipeTransaksi::KELUAR,
                    'nominal' => $pengajuan_pinjaman->nominal_pinjaman,
                    'keterangan' => "Pencairan pinjaman untuk kelompok {$jadwal->kelompok_name}"
                ]);

                // Deduct from funding account
                $pendanaan->decrement('saldo', $pengajuan_pinjaman->nominal_pinjaman);

                // Create CicilanKelompok entries
                $cicilan_data = [];
                $nominal_cicilan_per_bulan = $added_pinjaman->total_nominal_pinjaman / $pengajuan_pinjaman->tenor->value; // Hitung nominal cicilan per bulan
                for ($i = 1; $i <= $pengajuan_pinjaman->tenor->value; $i++) {
                    $cicilan_data[] = [
                        'status' => EnumStatusCicilanKelompok::BELUM_BAYAR->value,
                        'nominal_cicilan' => $nominal_cicilan_per_bulan,
                        'bukti_pembayaran' => null,
                        'tanggal_dibayar' => null, // Tanggal bayar awal null
                        'tanggal_jatuh_tempo' => $tanggal_pencairan->clone()->addMonths($i), // Gunakan tanggal_pencairan sebagai dasar
                        'pinjaman_kelompok_id' => $added_pinjaman->id, // ID pinjaman kelompok
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $cicilan_kelompok_model->insert($cicilan_data); // Use insert for bulk creation

                // Update JadwalPencairan status
                $jadwal->update([
                    'status' => EnumStatusJadwalPencairan::TELAH_DICAIRKAN
                ]);
            });
        } else {
            $this->info('Tidak ada jadwal pencairan yang jatuh tempo.');
        }

        $this->newLine();
        $this->info('Cek jadwal pencairan selesai.');
    }
}
