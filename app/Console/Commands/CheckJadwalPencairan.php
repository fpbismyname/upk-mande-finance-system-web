<?php

namespace App\Console\Commands;

use App\Enum\Admin\Status\EnumStatusJadwalPencairan;
use App\Models\JadwalPencairan;
use App\Models\PinjamanKelompok;
use Illuminate\Console\Command;

class CheckJadwalPencairan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pencairan:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek jadwal pencairan apakah sudah jatuh tempo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now();

        $data_jadwal_pencairan = JadwalPencairan::query()->whereDate('tanggal_pencairan', '<=', $today)->where('status', EnumStatusJadwalPencairan::TERJADWAL)->get();
        foreach ($data_jadwal_pencairan as $jadwal) {
            // Update status
            $jadwal->update([
                'status' => EnumStatusJadwalPencairan::TELAH_DICAIRKAN
            ]);
            // Tambahkan data pinjaman
            $data_pinjaman = [

            ];
            $add_pinjaman = PinjamanKelompok::create();
        }
    }
}
