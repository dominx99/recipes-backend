<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Application\Create;

use App\Cookery\Ingredients\Domain\Ingredient;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateIngredientCommandHandler
{
    public function __construct(private readonly IngredientCreator $creator)
    {
    }

    public function __invoke(CreateIngredientCommand $command): void
    {
        $this->creator->__invoke(Ingredient::new($command->id, $command->name));
    }
}
