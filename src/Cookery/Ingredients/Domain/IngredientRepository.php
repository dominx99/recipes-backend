<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Domain;

use App\Shared\Domain\AggregateRoot;

interface IngredientRepository
{
    public function all(): IngredientCollection;

    public function matching(array $ingredients): IngredientCollection;

    public function save(AggregateRoot $ingredient): void;
}
