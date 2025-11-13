<?php
namespace App\Enums\Admin\User;

enum EnumRole: string
{
    case ADMIN = "admin";
    case TAMU = "tamu";
    case ANGGOTA = "anggota";
    case KEPALA_INSTITUSI = "kepala_institusi";
    case AKUNTAN = "akuntan";
    case PENGELOLA_DANA = "pengelola_dana";

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = str_replace("_", " ", ucfirst($case->value));
            return $carry;
        }, []);
    }
    public static function list_admin_role()
    {
        return collect(self::cases())
            ->reject(fn($role) => in_array($role, [self::ANGGOTA, self::TAMU]))
            ->values()
            ->toArray();
    }
    public static function list_client_role()
    {
        return collect(self::cases())
            ->reject(fn($role) => !in_array($role, [self::ANGGOTA, self::TAMU]))
            ->values()
            ->toArray();
    }
}
