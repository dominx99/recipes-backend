<?php

declare(strict_types=1);

namespace App\Tests;

use App\Cookery\Ingredients\Domain\IngredientCollection;
use App\Cookery\Ingredients\Domain\IngredientComparator;
use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipeComponent;
use App\Cookery\Recipes\Domain\RecipeInterface;

final class CompleteRecipesMatcher
{
    public function __invoke(RecipeCollection $recipes, IngredientCollection $ingredients)
    {
        $recipes->filter(function (RecipeInterface $recipe) use ($ingredients) {
            $expression = true;

            $recipe->components()->forAll(function ($key, RecipeComponent $component) use ($ingredients, &$expression) {
                $exists = $ingredients->exists(
                    fn ($key, IngredientInterface $ingredient) =>
                        new IngredientComparator($ingredient, $component->ingredient())
                );

                if (!$exists) {
                    $expression = false;
                }

                return true;
            });

            return $expression;
        });
    }
}
