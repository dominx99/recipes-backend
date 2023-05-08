<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain;

use App\Auth\Domain\User;
use App\Shared\Domain\AggregateRoot;
use Doctrine\Common\Collections\Criteria;
use Ramsey\Uuid\UuidInterface;

interface RecipeRepository
{
    public function all(): RecipeCollection;

    public function save(AggregateRoot $recipe): void;

    public function matching(Criteria $criteria): RecipeCollection;

    /**
     * @param array<int,string> $ingredients
     */
    public function matchByIngredients(array $ingredients, int $offset, int $limit): MatchingRecipeCollection;

    public function matchByOwner(User $user, int $offset, int $limit): RecipeCollection;

    public function matchByIds(array $ids, int $offset, int $limit): RecipeCollection;

    /** @param array<int,string> $ids */
    public function findMany(array $ids): RecipeCollection;

    public function findOne(UuidInterface $id): ?Recipe;

    public function remove(Recipe $recipe): void;
}
