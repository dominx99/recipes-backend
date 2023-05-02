<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Update;

use App\Cookery\Recipes\Domain\RecipeRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateRecipeComponentsCountCommandHandler implements MessageHandlerInterface
{
    public function __construct(private readonly RecipeRepository $repository)
    {
    }

    public function __invoke(UpdateRecipeComponentsCountCommand $command): void
    {
        $recipe = $this->repository->findOne($command->recipeId);
        $recipe->setComponentsCount($command->componentsCount);

        $this->repository->save($recipe);
    }
}
