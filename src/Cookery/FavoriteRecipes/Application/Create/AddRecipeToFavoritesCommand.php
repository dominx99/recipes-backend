<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Application\Create;

final class AddRecipeToFavoritesCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $recipeId,
        public readonly string $userId,
    ) {
    }
}
