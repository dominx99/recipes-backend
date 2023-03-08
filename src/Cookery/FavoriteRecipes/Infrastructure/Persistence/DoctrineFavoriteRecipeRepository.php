<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Infrastructure\Persistence;

use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipe;
use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipeCollection;
use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipeRepository;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;

final class DoctrineFavoriteRecipeRepository extends DoctrineRepository implements FavoriteRecipeRepository
{
    public function all(): FavoriteRecipeCollection
    {
        return new FavoriteRecipeCollection($this->repository(FavoriteRecipe::class)->findAll());
    }

    public function save(AggregateRoot $favoriteRecipe): void
    {
        $this->persist($favoriteRecipe);
    }
}
