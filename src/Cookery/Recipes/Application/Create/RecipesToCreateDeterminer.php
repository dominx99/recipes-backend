<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Create;

use App\Recipes\Domain\RecipeCollection;

final class RecipesToCreateDeterminer
{
    public function __invoke(): RecipeCollection
    {
        return new RecipeCollection();
    }
}
