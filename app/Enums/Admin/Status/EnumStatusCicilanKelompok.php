<?php
namespace App\Enums\Admin\Status;

use Illuminate\Support\Str;

enum EnumStatusCicilanKelompok: string
{
    case SUDAH_BAYAR = 'sudah_bayar';
    case BELUM_BAYAR = 'belum_bayar';
    case TELAT_BAYAR = 'telat_bayar';
    case DIBATALKAN = 'dibatalkan';
    public static function options()
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

}
