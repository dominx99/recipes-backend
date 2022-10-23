<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Match;

use App\Cookery\Ingredients\Domain\IngredientCollection;
use App\Cookery\Ingredients\Domain\IngredientComparator;
use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipeComponent;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Cookery\Recipes\Domain\RecipesMatcher;
use App\Cookery\Recipes\Domain\ValueObject\RecipesBook;

use function Lambdish\Phunctional\apply;

final class CompleteRecipesMatcher implements RecipesMatcher
{
    public function __invoke(RecipeCollection $recipes, IngredientCollection $ingredients): RecipesBook
    {
        $book = new RecipesBook();

        $book->add('complete', $recipes->filter(function (RecipeInterface $recipe) use ($ingredients) {
            $expression = true;

            $recipe->components()->forAll(function ($key, RecipeComponent $component) use ($ingredients, &$expression) {
                $exists = $ingredients->exists(
                    fn ($key, IngredientInterface $ingredient) => apply(new IngredientComparator($ingredient, $component->ingredient()), [])
                );

                if (!$exists) {
                    $expression = false;
                }

                return true;
            });

            return $expression;
        }));

        return $book;
    }
}
