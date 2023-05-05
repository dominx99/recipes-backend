<?php

declare(strict_types=1);

namespace App\Cookery\RecipeComponents\Application\Delete;

use Ramsey\Uuid\UuidInterface;

final class DeleteRecipeComponentCommand
{
    public function __construct(public readonly UuidInterface $id)
    {
    }
}
