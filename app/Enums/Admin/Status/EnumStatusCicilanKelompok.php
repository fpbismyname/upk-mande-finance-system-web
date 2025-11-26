<?php
namespace App\Enums\Admin\Status;

enum EnumStatusCicilanKelompok: string {
    case SUDAH_BAYAR = 'sudah_bayar';
    case BELUM_BAYAR = 'belum_bayar';
    case TELAT_BAYAR = 'telat_bayar';
    case DIBATALKAN  = 'dibatalkan';
    public static function options()
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
}
