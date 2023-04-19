<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Application\Remove;

use Ramsey\Uuid\UuidInterface;

final class RemoveRecipeFromFavoritesCommand
{
    public function __construct(public readonly UuidInterface $favoriteRecipeId)
    {
    }
}
