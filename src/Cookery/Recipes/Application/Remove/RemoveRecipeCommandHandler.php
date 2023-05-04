<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Remove;

use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\DomainError;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RemoveRecipeCommandHandler implements MessageHandlerInterface
{
    public function __construct(private readonly RecipeRepository $repository)
    {
    }

    public function __invoke(RemoveRecipeCommand $command): void
    {
        $recipe = $this->repository->findOne($command->id);

        if (!$recipe instanceof Recipe) {
            throw new DomainError(sprintf('Recipe %s not found', $command->id->toString()));
        }

        $this->repository->remove($recipe);
    }
}
