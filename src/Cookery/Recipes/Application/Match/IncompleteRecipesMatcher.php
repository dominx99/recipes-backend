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

final class IncompleteRecipesMatcher implements RecipesMatcher
{
    public function __construct(private int $minimumComponentsCount)
    {
    }

    public function __invoke(RecipeCollection $recipes, Collection $elements): MatchingRecipeCollection
    {
        return new MatchingRecipeCollection($recipes->map(function (RecipeInterface $recipe) use ($elements) {
            $matchingElementsCount = 0;

            $recipe->components()->forAll(function ($key, RecipeComponent $component) use ($elements, &$matchingElementsCount) {
                $exists = $elements->exists(
                    fn ($key, string $element) => apply(
                        new StringContains($component->ingredient()->name(), $element), []
                    )
                );

                if ($exists) {
                    ++$matchingElementsCount;
                }

                return true;
            });

            return new MatchingRecipe($recipe, $matchingElementsCount);
        })
            ->filter(fn (MatchingRecipe $matchingRecipe) => $matchingRecipe->matchingElementsCount() >= $this->minimumComponentsCount)
            ->toArray()
        );
    }
}
