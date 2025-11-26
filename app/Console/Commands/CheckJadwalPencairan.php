<?php

namespace App\Console\Commands;

use App\Enums\Admin\Rekening\EnumTipeTransaksi;
use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Enums\Admin\Status\EnumStatusJadwalPencairan;
use App\Enums\Admin\Status\EnumStatusPinjaman;
use App\Models\CicilanKelompok;
use App\Models\JadwalPencairan;
use App\Models\PinjamanKelompok;
use App\Models\Rekening;
use App\Models\Settings;
use Illuminate\Console\Command;

/**
 * Class CheckJadwalPencairan
 *
 * Perintah konsol untuk memeriksa dan memproses jadwal pencairan pinjaman yang telah jatuh tempo.
 * Perintah ini akan secara otomatis membuat data pinjaman baru, mencatat transaksi,
 * membuat jadwal cicilan, dan memperbarui status pencairan.
 */
class CheckJadwalPencairan extends Command
{
    /**
     * Nama dan tanda tangan perintah konsol.
     * Signature digunakan untuk memanggil command dari terminal.
     *
     * @var string
     */
    protected $signature = 'jadwal-pencairan:check';

    /**
     * Deskripsi perintah konsol.
     * Deskripsi ini akan muncul saat menjalankan `php artisan list`.
     *
     * @var string
     */
    protected $description = 'Cek jadwal pencairan apakah sudah jatuh tempo';

    /**
     * Menjalankan perintah konsol.
     *
     * Method ini adalah inti dari command. Logika untuk memeriksa dan memproses
     * jadwal pencairan pinjaman yang jatuh tempo diimplementasikan di sini.
     *
     * @param Rekening $rekening_model Model untuk berinteraksi dengan data rekening.
     * @param Settings $settings_model Model untuk mengambil pengaturan aplikasi.
     * @param PinjamanKelompok $pinjaman_kelompok_model Model untuk membuat data pinjaman kelompok baru.
     * @param CicilanKelompok $cicilan_kelompok_model Model untuk membuat data cicilan kelompok.
     * @param JadwalPencairan $jadwal_pencairan_model Model untuk mengambil jadwal pencairan.
     * @return void
     */
    public function handle(
        Rekening $rekening_model,
        Settings $settings_model,
        PinjamanKelompok $pinjaman_kelompok_model,
        CicilanKelompok $cicilan_kelompok_model,
        JadwalPencairan $jadwal_pencairan_model
    ) {
        $this->info('Melakukan cek jadwal pencairan...');

        // Mengambil tanggal hari ini dan pengaturan bunga pinjaman dari database.
        $today = now();
        $bunga_pinjaman = (float) $settings_model->getKeySetting(EnumSettingKeys::BUNGA_PINJAMAN)->value('value');

        // Mengambil semua jadwal pencairan yang sudah jatuh tempo dan belum diproses.
        // Eager loading 'pengajuan_pinjaman' untuk menghindari N+1 query problem.
        $data_jadwal_pencairan = $jadwal_pencairan_model->jadwal_pencairan_jatuh_tempo()->get();

        // Memeriksa apakah ada jadwal pencairan yang perlu diproses.
        if ($data_jadwal_pencairan->isNotEmpty()) {
            // Mengambil rekening yang digunakan untuk pendanaan pinjaman.
            $pendanaan = $rekening_model->get_rekening_pendanaan()->first();

            // Jika rekening pendanaan tidak ditemukan, hentikan proses dengan pesan error.
            if (!$pendanaan) {
                $this->error('Rekening pendanaan tidak ditemukan.');
                return; // Menghentikan eksekusi command.
            }

            // Menampilkan progress bar untuk memvisualisasikan proses.
            $this->withProgressBar($data_jadwal_pencairan, function ($jadwal) use ($today, $bunga_pinjaman, $pendanaan, $pinjaman_kelompok_model, $cicilan_kelompok_model) {
                // Mengambil data pengajuan pinjaman terkait dari jadwal.
                $pengajuan_pinjaman = $jadwal->pengajuan_pinjaman; // Ambil data pengajuan pinjaman

                // Jika data pengajuan tidak ditemukan, lewati proses untuk jadwal ini.
                if (!$pengajuan_pinjaman) {
                    $this->warn("Pengajuan pinjaman untuk jadwal ID {$jadwal->id} tidak ditemukan. Melewati."); // Peringatan jika pengajuan pinjaman tidak ditemukan
                    return; // Lanjut ke iterasi berikutnya.
                }

                // Mengambil tanggal pencairan dari jadwal.
                $tanggal_pencairan = $jadwal->tanggal_pencairan; // Ambil tanggal pencairan

                // Membuat entri baru di tabel 'pinjaman_kelompoks' sebagai tanda pinjaman telah aktif.
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

                // Mencatat transaksi pengeluaran dari rekening pendanaan.
                $pendanaan->transaksi_rekening()->create([
                    'tipe_transaksi' => EnumTipeTransaksi::KELUAR,
                    'nominal' => $pengajuan_pinjaman->nominal_pinjaman,
                    'keterangan' => "Pencairan pinjaman untuk kelompok {$jadwal->kelompok_name}"
                ]);

                // Mengurangi saldo rekening pendanaan sesuai nominal pinjaman yang dicairkan.
                $pendanaan->decrement('saldo', $pengajuan_pinjaman->nominal_pinjaman);

                // Mempersiapkan data cicilan untuk pinjaman yang baru dibuat.
                $cicilan_data = [];
                // Menghitung nominal cicilan per bulan (pokok + bunga).
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
                // Menyimpan semua data cicilan sekaligus untuk efisiensi (bulk insert).
                $cicilan_kelompok_model->insert($cicilan_data); // Use insert for bulk creation

                // Memperbarui status jadwal pencairan menjadi 'TELAH_DICAIRKAN'.
                $jadwal->update([
                    'status' => EnumStatusJadwalPencairan::TELAH_DICAIRKAN
                ]);
            });
        } else {
            // Jika tidak ada jadwal yang jatuh tempo, tampilkan pesan informasi.
            $this->info('Tidak ada jadwal pencairan yang jatuh tempo.');
        }

        // Memberi baris baru di console untuk kerapian.
        $this->newLine();
        $this->info('Cek jadwal pencairan selesai.');
    }
}
