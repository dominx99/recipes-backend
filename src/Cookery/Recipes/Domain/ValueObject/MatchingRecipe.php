<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain\ValueObject;

use App\Cookery\Recipes\Domain\Recipe;
use Assert\Assertion;

final class MatchingRecipe
{
    public function __construct(private Recipe $recipe, private int $matchingElementsCount)
    {
        Assertion::between($matchingElementsCount, 0, $recipe->components()->count());
    }

    public function recipe(): Recipe
    {
        return $this->recipe;
    }

    public function matchingElementsCount(): int
    {
        return $this->matchingElementsCount;
    }
}
