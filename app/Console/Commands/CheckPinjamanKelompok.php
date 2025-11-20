<?php

namespace App\Console\Commands;

use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Enums\Admin\Status\EnumStatusPinjaman;
use App\Models\PinjamanKelompok;
use Illuminate\Console\Command;

/**
 * Class CheckPinjamanKelompok
 *
 * Perintah konsol untuk memeriksa status pinjaman kelompok secara berkala.
 * Command ini akan mengubah status pinjaman dan cicilan berdasarkan tanggal jatuh tempo.
 * Misalnya, mengubah status menjadi 'menunggak' atau 'telat bayar' jika sudah melewati
 * tanggal jatuh tempo, dan menjadi 'selesai' jika semua cicilan telah lunas.
 */
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
     * Menjalankan perintah konsol.
     *
     * Method ini berisi logika untuk memeriksa setiap pinjaman kelompok dan
     * memperbarui statusnya serta status cicilan terkait.
     *
     * @param PinjamanKelompok $pinjaman_kelompok_model Model untuk berinteraksi dengan data pinjaman kelompok.
     * @return void
     */
    public function handle(PinjamanKelompok $pinjaman_kelompok_model)
    {
        $this->info('Memulai cek pinjaman kelompok.');
        $this->newLine();

        // Mengambil semua data pinjaman kelompok beserta relasi cicilannya (eager loading).
        $pinjaman_kelompok = $pinjaman_kelompok_model->with('cicilan_kelompok')->get();

        // Cek pinjaman kelompok
        $this->info('Cek pinjaman yang jatuh tempo.');
        // Menampilkan progress bar untuk memvisualisasikan proses pemeriksaan.
        $this->withProgressBar($pinjaman_kelompok, function (PinjamanKelompok $pinjaman) {
            // Memeriksa apakah semua cicilan untuk pinjaman ini sudah lunas.
            // @note Query ini dieksekusi lagi padahal data cicilan sudah di-load sebelumnya.
            $status_cicilan_pinjaman_lunas = $pinjaman->cicilan_kelompok()->get()->every(function ($cicilan) {
                return $cicilan->status === EnumStatusCicilanKelompok::SUDAH_BAYAR;
            });

            // Mengambil data pinjaman jika sudah melewati tanggal jatuh tempo.
            $pinjaman_jatuh_tempo = $pinjaman->pinjaman_jatuh_tempo()->get();
            // Mengambil data cicilan yang sudah melewati tanggal jatuh tempo.
            $cicilan_pinjaman_jatuh_tempo = $pinjaman->cicilan_kelompok()->cicilan_jatuh_tempo()->get();

            // Jika ada cicilan yang jatuh tempo, ubah statusnya menjadi 'TELAT_BAYAR'.
            if ($cicilan_pinjaman_jatuh_tempo->isNotEmpty()) {
                foreach ($cicilan_pinjaman_jatuh_tempo as $cicilan) {
                    $cicilan->status = EnumStatusCicilanKelompok::TELAT_BAYAR;
                    $cicilan->save();
                }
            }

            // Ubah status pinjaman yang jatuh tempo
            // @note Variabel $pinjaman di dalam loop ini menimpa variabel $pinjaman dari closure,
            // yang berpotensi menyebabkan perilaku tak terduga.
            if ($pinjaman_jatuh_tempo->isNotEmpty()) {
                foreach ($pinjaman_jatuh_tempo as $pinjaman) {
                    $pinjaman->status = EnumStatusPinjaman::MENUNGGAK;
                    $pinjaman->save();
                }
            }

            // Memperbarui status pinjaman utama berdasarkan status cicilan.
            // @note Logika ini dapat menimpa status 'MENUNGGAK' yang diatur di blok sebelumnya.
            // Jika pinjaman belum lunas, statusnya akan diubah menjadi 'BERLANGSUNG'
            // meskipun seharusnya bisa jadi 'MENUNGGAK'.
            if ($status_cicilan_pinjaman_lunas) {
                // Jika semua cicilan sudah lunas, tandai pinjaman sebagai 'SELESAI'.
                $pinjaman->status = EnumStatusPinjaman::SELESAI;
                $pinjaman->save();
            } else {
                // Jika masih ada cicilan yang belum lunas, status pinjaman adalah 'BERLANGSUNG'.
                $pinjaman->status = EnumStatusPinjaman::BERLANGSUNG;
                $pinjaman->save();
            }
        });

        $this->newLine();
        $this->info('Cek pinjaman kelompok selesai.');
    }
}
