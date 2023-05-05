<?php

declare(strict_types=1);

namespace App\Cookery\RecipeComponents\Application\Delete;

use App\Cookery\RecipeComponents\Domain\RecipeComponentRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class DeleteRecipeComponentCommandHandler
{
    public function __construct(private readonly RecipeComponentRepository $repository)
    {
    }

    public function __invoke(DeleteRecipeComponentCommand $command): void
    {
        $component = $this->repository->findOne($command->id);

        if (!$component) {
            return;
        }

        $this->repository->remove($component);
    }
}
