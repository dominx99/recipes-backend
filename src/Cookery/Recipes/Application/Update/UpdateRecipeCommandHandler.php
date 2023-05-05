<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Update;

use App\Cookery\RecipeComponents\Application\Create\CreateRecipeComponentCommand;
use App\Cookery\RecipeComponents\Application\Delete\DeleteRecipeComponentCommand;
use App\Cookery\Recipes\Domain\RecipeComponentCollection;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class UpdateRecipeCommandHandler
{
    public function __construct(
        private readonly RecipeRepository $repository,
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(UpdateRecipeCommand $command): void
    {
        $recipe = $this->repository->findOne($command->id);

        foreach ($recipe->components() as $component) {
            $this->messageBus->dispatch(new DeleteRecipeComponentCommand($component->id()));
        }

        $recipe->update(
            $command->request->name,
            new RecipeComponentCollection(),
        );

        $this->repository->save($recipe);

        foreach ($command->request->components as $component) {
            $this->messageBus->dispatch(new CreateRecipeComponentCommand(
                Uuid::random(),
                $command->id,
                $component['name'],
                $component['quantity'],
                $component['unit'],
            ));
        }
    }
}
