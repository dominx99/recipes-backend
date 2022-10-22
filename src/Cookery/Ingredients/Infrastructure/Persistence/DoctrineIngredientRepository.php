<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Infrastructure\Persistence;

use App\Cookery\Ingredients\Domain\Ingredient;
use App\Cookery\Ingredients\Domain\IngredientRepository;
use App\Ingredients\IngredientCollection;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;

final class DoctrineIngredientRepository extends DoctrineRepository implements IngredientRepository
{
    public function all(): IngredientCollection
    {
        return new IngredientCollection($this->repository(Ingredient::class)->findAll());
    }

    public function save(AggregateRoot $ingredient): void
    {
        $this->persist($ingredient);
    }
}
