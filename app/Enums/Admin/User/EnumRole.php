<?php
namespace App\Enums\Admin\User;

use Str;

enum EnumRole: string
{
    case ANGGOTA = "anggota";
    case TAMU = "tamu";
    case ADMIN = "admin";
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
    public static function list_all_role()
    {
        return collect(self::cases())
            ->values()
            ->toArray();
    }
    public function label()
    {
        return Str::of($this->value)->ucfirst()->replace("_", " ");
    }
    public static function getValues($type_values = null)
    {
        return match ($type_values) {
            'list_admin_role' => collect(self::list_admin_role())->pluck('value')->toArray(),
            'list_client_role' => collect(self::list_client_role())->pluck('value')->toArray(),
            default => collect(self::cases())->pluck('value')->toArray()
        };
    }
}
