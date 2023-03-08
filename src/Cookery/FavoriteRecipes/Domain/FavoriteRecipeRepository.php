<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Domain;

use App\Shared\Domain\AggregateRoot;

interface FavoriteRecipeRepository
{
    public function all(): FavoriteRecipeCollection;

    public function save(AggregateRoot $favoriteRecipe): void;
}
