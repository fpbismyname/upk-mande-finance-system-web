<?php
namespace App\Enums\Admin\Status;

enum EnumStatusKelompok: string {
    case NON_AKTIF = 'non-aktif';
    case AKTIF     = 'aktif';

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
}
