<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Application\Create;

use App\Cookery\Ingredients\Domain\Ingredient;
use App\Cookery\Ingredients\Domain\IngredientCollection;
use App\Cookery\Ingredients\Domain\IngredientInterface;

final class IngredientsToCreateDeterminer
{
    public function __invoke(
        IngredientCollection $existingIngredients,
        IngredientCollection $newIngredients
    ): IngredientCollection {
        return $newIngredients
            ->filter(fn (IngredientInterface $newIngredient) => !$existingIngredients->exists(
                fn ($key, IngredientInterface $existingIngredient) => $newIngredient->name() === $existingIngredient->name()
            ))
            ->filter(fn (IngredientInterface $newIngredient) => $newIngredient->name() !== '')
            ->filter(fn (IngredientInterface $newIngredient) => $newIngredient->name() !== '<hr>')
            ->map(fn (IngredientInterface $ingredient) => Ingredient::fromIngredient($ingredient))
        ;
    }
}
