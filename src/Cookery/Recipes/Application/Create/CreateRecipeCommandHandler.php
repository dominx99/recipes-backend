<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Create;

use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeComponentCollection;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateRecipeCommandHandler implements MessageHandlerInterface
{
    public function __construct(private readonly RecipeCreator $creator)
    {
    }

    public function __invoke(CreateRecipeCommand $command): void
    {
        $recipe = Recipe::new(
            id: $command->id,
            name: $command->name,
            instructions: $command->instructions,
            components: new RecipeComponentCollection(),
            ownerId: $command->ownerId,
            published: false,
        );

        $this->creator->__invoke($recipe);
    }
}
