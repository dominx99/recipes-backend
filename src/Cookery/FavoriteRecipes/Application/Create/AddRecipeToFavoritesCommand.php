<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Application\Create;

use Ramsey\Uuid\UuidInterface;

final class AddRecipeToFavoritesCommand
{
    public function __construct(
        public readonly UuidInterface $id,
        public readonly UuidInterface $recipeId,
        public readonly UuidInterface $userId,
    ) {
    }
}
