<?php

namespace App\Enum\Admin\Status;

enum EnumStatusPengajuanPinjaman: string
{
    case DITOLAK = 'ditolak';
    case PROSES_PENGAJUAN = 'proses_pengajuan';
    case DISETUJUI = 'disetujui';

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
}
