<?php
namespace App\Enums\Admin\Status;

use Hamcrest\SelfDescribing;
use Illuminate\Support\Str;

enum EnumStatusPengajuanKeanggotaan: string
{
    case DITOLAK = 'ditolak';
    case PROSES_PENGAJUAN = 'proses_pengajuan';
    case DISETUJUI = 'disetujui';
    case DIBATALKAN = 'dibatalkan';

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

    public static function cases_review()
    {
        return collect(self::cases())->filter(fn($case) => in_array($case, [self::DISETUJUI, self::DITOLAK]));
    }
}
