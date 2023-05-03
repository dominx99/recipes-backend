<?php

declare(strict_types=1);

namespace App\Cookery\RecipeComponents\Application\Create;

use Ramsey\Uuid\UuidInterface;

final class CreateRecipeComponentCommand
{
    public function __construct(
        public readonly UuidInterface $id,
        public readonly UuidInterface $recipeId,
        public readonly string $name,
        public readonly string $quantity,
        public readonly string $unit,
    ) {
    }
}
