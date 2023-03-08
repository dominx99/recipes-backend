<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Application\Create;

use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipe;

final class AddRecipeToFavoritesCommand
{
    public function __construct(
        public readonly FavoriteRecipe $favoriteRecipe,
    ) {
    }
}
