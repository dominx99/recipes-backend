<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Infrastructure\Persistence;

use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;
use Doctrine\Common\Collections\Criteria;

final class DoctrineRecipeRepository extends DoctrineRepository implements RecipeRepository
{
    public function all(): RecipeCollection
    {
        return new RecipeCollection($this->repository(Recipe::class)->findAll());
    }

    public function save(AggregateRoot $recipe): void
    {
        $this->persist($recipe);
    }

    public function matching(Criteria $criteria): RecipeCollection
    {
        return new RecipeCollection($this->repository(Recipe::class)->matching($criteria)->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function findMany(array $ids): RecipeCollection
    {
        return new RecipeCollection($this->repository(Recipe::class)->findBy(['id' => $ids]));
    }
}
