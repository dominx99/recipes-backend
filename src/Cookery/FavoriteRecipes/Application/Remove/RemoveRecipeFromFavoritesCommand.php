<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Application\Remove;

final class RemoveRecipeFromFavoritesCommand
{
    public function __construct(public readonly string $favoriteRecipeId)
    {
    }
}
