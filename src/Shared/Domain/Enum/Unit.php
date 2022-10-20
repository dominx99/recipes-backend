<?php

declare(strict_types=1);

namespace App\Shared\Domain\Enum;

enum Unit: string
{
    case NONE = 'none';
    case OZ = 'oz';
    case CUP = 'cup';
    case CLOVE = 'clove';
    case TEASPOON = 'teaspoon';
    case TBSPS = 'tbsps';
    case TBSP = 'tbsp';
    case TSP = 'tsp';
    case C = 'c';
    case TABLESPOON = 'tablespoon';
    case LB = 'lb';
    case POUND = 'pound';
    case QUART = 'quart';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
