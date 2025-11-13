<?php
namespace App\Enums\Admin\CatatanPendanaan;

enum EnumCatatanPendanaan: string {
    case INFLOW  = 'pemasukan';
    case OUTFLOW = 'pengeluaran';

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
}
