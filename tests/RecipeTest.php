<?php

declare(strict_types=1);

namespace App\Tests;

final class RecipeTest
{
    public function __invoke()
    {
        $storage = new Storage();

        $storage->add([
            new Item('milk'),
            new Item('bread'),
        ]);

        $ingredients = $ingredientsRepository->findByStorage($storage);
        $recipes = $recipesRepository->findByIngredients($ingredients);

        $completeMatcher = new CompleteRecipesMatcher($recipes, $ingredients);
        $incompleteMatcher = new InCompleteRecipesMatcher($recipes, $ingredients, 3);

        $matchingRecipes = apply($completeMatcher, []);
        $almostMatchingRecipes = apply($incompleteMatcher, []);
    }
}
