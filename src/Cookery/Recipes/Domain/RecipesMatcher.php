<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain;

use App\Cookery\Ingredients\Domain\IngredientCollection;

interface RecipesMatcher
{
    public function __invoke(RecipeCollection $recipes, IngredientCollection $ingredients): MatchingRecipeCollection;
}
