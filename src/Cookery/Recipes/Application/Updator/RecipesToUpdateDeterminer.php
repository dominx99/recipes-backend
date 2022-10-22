<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Updator;

use App\Cookery\Recipes\Domain\RecipeCollection;

final class RecipesToUpdateDeterminer
{
    public function __invoke(): RecipeCollection
    {
        return new RecipeCollection();
    }
}
