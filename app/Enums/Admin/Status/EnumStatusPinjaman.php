<?php
namespace App\Enums\Admin\Status;

enum EnumStatusPinjaman: string
{
    case MENUNGGAK = 'menunggak';
    case BERLANGSUNG = 'berlangsung';
    case SELESAI = 'selesai';
    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
}
