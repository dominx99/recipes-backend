<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Domain;

final class IngredientComparator
{
    public function __construct(private IngredientInterface $ingredientA, private IngredientInterface $ingredientB)
    {
    }

    public function __invoke(): bool
    {
        return $this->ingredientA->name() === $this->ingredientB->name();
    }
}
