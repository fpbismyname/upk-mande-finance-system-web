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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'limit-pinjaman-kelompok:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek update limit pinjaman kelompok';

    /**
     * Execute the console command.
     */
    public function handle(Kelompok $kelompok_model)
    {
        $this->info('Memulai cek limit pinjaman kelompok.');
        $this->newLine();

        $kenaikan_limit_per_jumlah_pinjaman = intval(Settings::getKeySetting(EnumSettingKeys::KENAIKAN_LIMIT_PER_JUMLAH_PINJAMAN)->value('value'));
        $limit_pinjaman_maksimal = floatval(Settings::getKeySetting(EnumSettingKeys::LIMIT_PINJAMAN_MAKSIMAL)->value('value'));

        $kelompok = $kelompok_model->filterPinjamanKelompokCount()->get()->filter(fn($pinjaman) => $pinjaman->pinjaman_kelompok_count >= $kenaikan_limit_per_jumlah_pinjaman);
        $this->withProgressBar($kelompok, function ($kelompok) use ($limit_pinjaman_maksimal) {
            $kelompok->limit_pinjaman = $limit_pinjaman_maksimal;
            $kelompok->save();
        });

        $this->newLine();
        $this->info('Cek limit pinjaman kelompok selesai.');
    }
}
