<?php
namespace App\Enums\Admin\PengajuanPinjaman;

enum EnumTenor: int {
    case BULAN_3  = 3;
    case BULAN_6  = 6;
    case BULAN_9  = 9;
    case BULAN_12 = 12;
    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value)) . " Bulan";
            return $carry;
        }, []);
    }
}
