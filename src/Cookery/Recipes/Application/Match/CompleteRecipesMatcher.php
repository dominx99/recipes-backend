<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Match;

use App\Cookery\Recipes\Domain\MatchingRecipeCollection;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipeComponent;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Cookery\Recipes\Domain\RecipesMatcher;
use App\Cookery\Recipes\Domain\ValueObject\MatchingRecipe;
use App\Shared\Application\Compare\StringContains;
use App\Shared\Domain\Collection\Collection;

use function Lambdish\Phunctional\apply;

final class CompleteRecipesMatcher implements RecipesMatcher
{
    public function __invoke(RecipeCollection $recipes, Collection $ingredients): MatchingRecipeCollection
    {
        return new MatchingRecipeCollection($recipes->filter(function (RecipeInterface $recipe) use ($ingredients) {
            $expression = true;

            $recipe->components()->forAll(function ($key, RecipeComponent $component) use ($ingredients, &$expression) {
                $exists = $ingredients->exists(
                    fn ($key, string $element) => apply(
                        new StringContains($component->ingredient()->name(), $element), []
                    )
                );

                if (!$exists) {
                    $expression = false;
                }

                return true;
            });

            return $expression;
        })
            ->map(fn (RecipeInterface $recipe) => new MatchingRecipe($recipe, $recipe->components()->count()))
            ->toArray()
        );
    }
}
