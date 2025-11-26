<?php
namespace App\Enums\Table;

enum PaginateSize: int {
    case SMALL  = 10;
    case MEDIUM = 25;
    case LARGE  = 50;
    case XL     = 100;

    public function label(): string
    {
        return match ($this) {
            self::SMALL  => '10 per page',
            self::MEDIUM => '25 per page',
            self::LARGE  => '50 per page',
            self::XL     => '100 per page',
        };
    }
    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $key         = strtolower($case->key);
            $carry[$key] = $case->value;
            return $carry;
        }, []);
    }
}
