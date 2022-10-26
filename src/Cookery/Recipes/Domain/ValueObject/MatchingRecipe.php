<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain\ValueObject;

use App\Cookery\Recipes\Domain\RecipeInterface;
use Assert\Assertion;

final class MatchingRecipe
{
    public function __construct(private RecipeInterface $recipe, private int $matchingIngredientsCount)
    {
        Assertion::between($matchingIngredientsCount, 0, $recipe->components()->count());
    }

    public function recipe(): RecipeInterface
    {
        return $this->recipe;
    }

    public function matchingIngredientsCount(): int
    {
        return $this->matchingIngredientsCount;
    }
}
