<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain\ValueObject;

use App\Cookery\Recipes\Domain\RecipeInterface;
use Assert\Assertion;

final class MatchingRecipe
{
    public function __construct(private RecipeInterface $recipe, private int $matchingElementsCount)
    {
        Assertion::between($matchingElementsCount, 0, $recipe->components()->count());
    }

    public function recipe(): RecipeInterface
    {
        return $this->recipe;
    }

    public function matchingElementsCount(): int
    {
        return $this->matchingElementsCount;
    }
}
