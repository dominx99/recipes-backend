<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Domain;

use App\Shared\Domain\AggregateRoot;
use Doctrine\Common\Collections\Criteria;

interface FavoriteRecipeRepository
{
    public function all(): FavoriteRecipeCollection;

    public function matching(Criteria $criteria): FavoriteRecipeCollection;

    public function save(AggregateRoot $favoriteRecipe): void;
}
