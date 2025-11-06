<?php

namespace App\Enum\Admin\Status;

enum EnumStatusCicilanKelompok: string
{
    case SUDAH_BAYAR = 'sudah_bayar';
    case BELUM_BAYAR = 'belum_bayar';
    case TELAT_BAYAR = 'terlambat';
    case DIBATALKAN = 'dibatalkan';
    public static function options()
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
}
