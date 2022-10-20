<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Application\Create;

use App\Cookery\Ingredients\Domain\Ingredient;
use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Ingredients\IngredientCollection;

final class IngredientsToCreateDeterminer
{
    public function __invoke(
        IngredientCollection $existingIngredients,
        IngredientCollection $newIngredients
    ): IngredientCollection {
        return $newIngredients->filter(fn (IngredientInterface $newIngredient) =>
            !$existingIngredients->exists(
                fn (IngredientInterface $existingIngredient) => $newIngredient->name() === $existingIngredient->name()
            )
        )->map(fn (IngredientInterface $ingredient) => Ingredient::fromIngredient($ingredient));
    }
}
