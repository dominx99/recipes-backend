<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Update;

use Ramsey\Uuid\UuidInterface;

final class UpdateRecipeComponentsCountCommand
{
    public function __construct(
        public readonly UuidInterface $recipeId,
        public readonly int $componentsCount,
    ) {
    }
}
