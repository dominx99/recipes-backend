<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Match;

use App\Cookery\Ingredients\Domain\IngredientCollection;
use App\Cookery\Ingredients\Domain\IngredientComparator;
use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Recipes\Domain\MatchingRecipeCollection;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipeComponent;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Cookery\Recipes\Domain\RecipesMatcher;
use App\Cookery\Recipes\Domain\ValueObject\MatchingRecipe;

use function Lambdish\Phunctional\apply;

final class IncompleteRecipesMatcher implements RecipesMatcher
{
    public function __construct(private int $minimumComponentsCount)
    {
    }

    public function __invoke(RecipeCollection $recipes, IngredientCollection $ingredients): MatchingRecipeCollection
    {
        return new MatchingRecipeCollection($recipes->map(function (RecipeInterface $recipe) use ($ingredients) {
            $matchingIngredientsCount = 0;

            $recipe->components()->forAll(function ($key, RecipeComponent $component) use ($ingredients, &$matchingIngredientsCount) {
                $exists = $ingredients->exists(
                    fn ($key, IngredientInterface $ingredient) => apply(
                        new IngredientComparator($ingredient, $component->ingredient()), []
                    )
                );

                if ($exists) {
                    ++$matchingIngredientsCount;
                }

                return true;
            });

            return new MatchingRecipe($recipe, $matchingIngredientsCount);
        })
            ->filter(fn (MatchingRecipe $matchingRecipe) => $matchingRecipe->matchingIngredientsCount() >= $this->minimumComponentsCount)
            ->toArray()
        );
    }
}
