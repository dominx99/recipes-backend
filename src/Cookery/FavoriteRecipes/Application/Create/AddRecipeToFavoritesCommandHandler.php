<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Application\Create;

use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipe;
use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipeRepository;
use App\Shared\Domain\Utils;
use App\Shared\Domain\ValidationFailedError;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AddRecipeToFavoritesCommandHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly FavoriteRecipeRepository $favoriteRecipeRepository,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function __invoke(AddRecipeToFavoritesCommand $command): void
    {
        $favoriteRecipe = new FavoriteRecipe(
            $command->id,
            $command->userId,
            $command->recipeId,
        );

        $violations = $this->validator->validate($favoriteRecipe);

        if ($violations->count() > 0) {
            throw new ValidationFailedError(Utils::formatViolations($violations));
        }

        $this->favoriteRecipeRepository->save($favoriteRecipe);
    }
}
