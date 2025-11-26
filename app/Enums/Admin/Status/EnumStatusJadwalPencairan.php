<?php
namespace App\Enums\Admin\Status;

enum EnumStatusJadwalPencairan: string {
    case BELUM_TERJADWAL = 'belum_terjadwal';
    case TERJADWAL       = 'terjadwal';
    case TELAH_DICAIRKAN = 'telah_dicairkan';
    case DIBATALKAN      = 'dibatalkan';

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
}
