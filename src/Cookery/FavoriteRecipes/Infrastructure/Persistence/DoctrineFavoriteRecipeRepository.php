<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Infrastructure\Persistence;

use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipe;
use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipeCollection;
use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipeRepository;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;
use Doctrine\Common\Collections\Criteria;
use Ramsey\Uuid\UuidInterface;

final class DoctrineFavoriteRecipeRepository extends DoctrineRepository implements FavoriteRecipeRepository
{
    public function find(UuidInterface $id): ?FavoriteRecipe
    {
        return $this->repository(FavoriteRecipe::class)->find($id);
    }

    public function all(): FavoriteRecipeCollection
    {
        return new FavoriteRecipeCollection($this->repository(FavoriteRecipe::class)->findAll());
    }

    public function matching(Criteria $criteria): FavoriteRecipeCollection
    {
        return new FavoriteRecipeCollection($this->repository(FavoriteRecipe::class)->matching($criteria)->toArray());
    }

    public function save(AggregateRoot $favoriteRecipe): void
    {
        $this->persist($favoriteRecipe);
    }
}
