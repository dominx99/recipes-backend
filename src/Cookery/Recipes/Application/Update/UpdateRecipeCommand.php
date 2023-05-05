<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Update;

use App\Cookery\Recipes\Http\Request\RecipePostRequest;
use Ramsey\Uuid\UuidInterface;

final class UpdateRecipeCommand
{
    public function __construct(
        public readonly UuidInterface $id,
        public readonly RecipePostRequest $request
    ) {
    }
}
