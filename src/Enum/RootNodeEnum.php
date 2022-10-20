<?php

declare(strict_types=1);

namespace App\Enum;

enum RootNodeEnum: string
{
    case RECIPE = 'recipe';
    case INGREDIENT = 'ingredient';
}
