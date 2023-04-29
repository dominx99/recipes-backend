<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Domain;

use App\Shared\Domain\AggregateRoot;
use Ramsey\Uuid\UuidInterface;

interface IngredientRepository
{
    public function findOne(UuidInterface $id): ?Ingredient;

    public function all(): IngredientCollection;

    /**
     * @param array<int,string> $ingredients
     */
    public function matching(array $ingredients): IngredientCollection;

    public function matchingOne(string $ingredient): ?Ingredient;

    public function save(AggregateRoot $ingredient): void;
}
