<?php

namespace App\Enums\Admin\Rekening;

enum EnumTipeTransaksi: string
{
    case MASUK = 'masuk';
    case KELUAR = 'keluar';
    case TRANSFER = 'transfer';

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
}
