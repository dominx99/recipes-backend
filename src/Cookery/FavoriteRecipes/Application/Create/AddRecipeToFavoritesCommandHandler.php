<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Application\Create;

use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipeRepository;

final class AddRecipeToFavoritesCommandHandler
{
    public function __construct(
        private readonly FavoriteRecipeRepository $favoriteRecipeRepository,
    ) {
    }

    public function __invoke(AddRecipeToFavoritesCommand $command): void
    {
        $this->favoriteRecipeRepository->save($command->favoriteRecipe);
    }
}
