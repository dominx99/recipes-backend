<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Infrastructure\Persistence;

use App\Cookery\Ingredients\Domain\Ingredient;
use App\Cookery\Ingredients\Domain\IngredientCollection;
use App\Cookery\Ingredients\Domain\IngredientRepository;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;
use Doctrine\Common\Collections\Criteria;
use Ramsey\Uuid\UuidInterface;

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

    public function matching(array $ingredients): IngredientCollection
    {
        return new IngredientCollection($this->repository(Ingredient::class)->matching(
            Criteria::create()->andWhere(
                Criteria::expr()->in('name', $ingredients)
            ),
        )->toArray());
    }

    public function matchingOne(string $ingredient): ?Ingredient
    {
        return $this->repository(Ingredient::class)->matching(
            Criteria::create()->andWhere(
                Criteria::expr()->eq('name', $ingredient)
            ),
        )->first() ?: null;
    }

    public function findOne(UuidInterface $id): ?Ingredient
    {
        return $this->repository(Ingredient::class)->find($id);
    }
}
