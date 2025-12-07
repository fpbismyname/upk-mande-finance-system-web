<?php
namespace App\Enums\Admin\Settings;

enum EnumSettingGroup: string
{
    case PINJAMAN = 'pinjaman';
    case KELOMPOK = 'kelompok';
    case INFORMASI_WEBSITE = 'informasi_website';
    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
}
