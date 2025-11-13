<?php
namespace App\Enums\Admin\Settings;

enum EnumSettingKeys: string
{
    case TOLERANSI_TELAT_BAYAR = 'toleransi_telat_bayar';
    case DENDA_TELAT_BAYAR = 'denda_telat_bayar';
    case BUNGA_PINJAMAN = 'bunga_pinjaman';
    case LIMIT_PINJAMAN_MAKSIMAL = 'limit_pinjaman_maksimal';
    case KENAIKAN_LIMIT_PER_JUMLAH_PINJAMAN = 'kenaikan_limit_per_jumlah_pinjaman';
    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
}
