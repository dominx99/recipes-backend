<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain;

use App\Ingredients\IngredientCollection;

interface RecipeInterface
{
    public function name(): string;

    public function ingredients(): IngredientCollection;
}
