<?php

namespace App\Console\Commands;

use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Enums\Admin\Status\EnumStatusPinjaman;
use App\Models\Kelompok;
use App\Models\Settings;
use Illuminate\Console\Command;

class CheckLimitPinjamanKelompok extends Command
{
    /**
     * Nama dan tanda tangan perintah konsol.
     *
     * @var string
     */
    protected $signature = 'limit-pinjaman-kelompok:check';

    /**
     * Deskripsi perintah konsol.
     *
     * @var string
     */
    protected $description = 'Cek update limit pinjaman kelompok';

    /**
     * Jalankan perintah konsol.
     */
    public function handle(Kelompok $kelompok_model)
    {
        $this->info('Memulai cek limit pinjaman kelompok.');
        $this->newLine();

        $kenaikan_limit_per_jumlah_pinjaman = intval(Settings::getKeySetting(EnumSettingKeys::KENAIKAN_LIMIT_PER_JUMLAH_PINJAMAN)->value('value'));
        $limit_pinjaman_maksimal = floatval(Settings::getKeySetting(EnumSettingKeys::MAKSIMAL_LIMIT_PINJAMAN)->value('value'));

        // Query yang dioptimalkan untuk langsung mengambil dan memperbarui kelompok yang relevan
        $kelompok_to_update = $kelompok_model->pinjaman_kelompok_selesai_count()->get()->filter(function ($kelompok) use ($kenaikan_limit_per_jumlah_pinjaman) {
            return $kelompok->pinjaman_kelompok_selesai_count >= $kenaikan_limit_per_jumlah_pinjaman;
        });

        $this->withProgressBar($kelompok_to_update, function (Kelompok $kelompok) use ($limit_pinjaman_maksimal) {
            // Update the limit_per_anggota to the maximum allowed limit
            $kelompok->update([
                'limit_per_anggota' => $limit_pinjaman_maksimal
            ]);
        });

        $this->newLine();
        $this->info('Cek limit pinjaman kelompok selesai.');
    }
}
