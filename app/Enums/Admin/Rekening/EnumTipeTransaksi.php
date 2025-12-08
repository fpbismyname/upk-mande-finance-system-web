<?php

namespace App\Enums\Admin\Rekening;

use Illuminate\Support\Str;


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
    public function label()
    {
        return Str::of($this->value)->ucfirst()->replace("_", " ");
    }
    public static function tipe_masuk_keluar()
    {
        return [self::MASUK, self::KELUAR];
    }
}
