<?php

namespace App\Console\Commands;

use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Models\Kelompok;
use App\Models\Settings;
use Illuminate\Console\Command;

/**
 * Class CheckLimitPinjamanKelompok
 *
 * Perintah konsol untuk memeriksa dan memperbarui batas (limit) pinjaman untuk setiap kelompok.
 * Command ini akan menaikkan limit pinjaman kelompok ke nilai maksimal jika telah
 * mencapai jumlah pinjaman lunas yang ditentukan dalam pengaturan.
 */
class CheckLimitPinjamanKelompok extends Command
{
    /**
     * Nama dan tanda tangan perintah konsol.
     * Signature digunakan untuk memanggil command dari terminal.
     *
     * @var string
     */
    protected $signature = 'limit-pinjaman-kelompok:check';

    /**
     * Deskripsi perintah konsol.
     * Deskripsi ini akan muncul saat menjalankan `php artisan list`.
     *
     * @var string
     */
    protected $description = 'Cek update limit pinjaman kelompok';

    public function handle(Kelompok $kelompok_model)
    {
        $this->info('Memulai cek limit pinjaman kelompok.');
        $this->newLine();

        $kenaikan_limit_per_jumlah_pinjaman = intval(Settings::getKeySetting(EnumSettingKeys::KENAIKAN_LIMIT_PER_JUMLAH_PINJAMAN)->value('value'));
        $limit_pinjaman_maksimal = floatval(Settings::getKeySetting(EnumSettingKeys::MAKSIMAL_LIMIT_PINJAMAN)->value('value'));

        $kelompok_to_update = $kelompok_model->pinjaman_kelompok_selesai_count()->get()->filter(function ($kelompok) use ($kenaikan_limit_per_jumlah_pinjaman) {
            return $kelompok->pinjaman_kelompok_selesai_count >= $kenaikan_limit_per_jumlah_pinjaman;
        });

        $this->withProgressBar($kelompok_to_update, function (Kelompok $kelompok) use ($limit_pinjaman_maksimal) {
            $kelompok->update([
                'limit_per_anggota' => $limit_pinjaman_maksimal
            ]);
        });

        $this->newLine();
        $this->info('Cek limit pinjaman kelompok selesai.');
    }
}
