<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Application\Remove;

use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipe;
use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipeRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RemoveRecipeFromFavoritesCommandHandler implements MessageHandlerInterface
{
    public function __construct(private readonly FavoriteRecipeRepository $repository)
    {
    }

    public function __invoke(RemoveRecipeFromFavoritesCommand $command): void
    {
        if (!$favoriteRecipe = $this->repository->find($command->favoriteRecipeId)) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(FavoriteRecipe::class, [$command->favoriteRecipeId]);
        }

        $this->repository->remove($favoriteRecipe);
    }
}
