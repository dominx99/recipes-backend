<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Domain;

use App\Shared\Domain\AggregateRoot;
use Doctrine\Common\Collections\Criteria;
use Ramsey\Uuid\UuidInterface;

interface FavoriteRecipeRepository
{
    public function find(UuidInterface $id): ?FavoriteRecipe;

    public function all(): FavoriteRecipeCollection;

    public function matching(Criteria $criteria): FavoriteRecipeCollection;

    public function save(AggregateRoot $favoriteRecipe): void;

    public function remove(AggregateRoot $favoriteRecipe): void;
}
