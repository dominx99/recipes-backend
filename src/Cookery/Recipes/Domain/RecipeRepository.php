<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain;

use App\Shared\Domain\AggregateRoot;
use Doctrine\Common\Collections\Criteria;

interface RecipeRepository
{
    public function all(): RecipeCollection;

    public function save(AggregateRoot $recipe): void;

    public function matching(Criteria $criteria): RecipeCollection;

    /**
     * @param array<int,string> $ingredients
     */
    public function matchByIngredients(array $ingredients): MatchingRecipeCollection;

    /** @param array<int,string> $ids */
    public function findMany(array $ids): RecipeCollection;
}
