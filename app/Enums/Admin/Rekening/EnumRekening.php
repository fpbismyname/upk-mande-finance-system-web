<?php

namespace App\Enums\Admin\Rekening;

enum EnumRekening: string
{
    case AKUNTAN = 'akuntan';
    case PENGELOLA_DANA = 'pengelola_dana';

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
}
