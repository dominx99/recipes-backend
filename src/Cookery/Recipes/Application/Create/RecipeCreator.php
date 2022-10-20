<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Create;

use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeRepository;

final class RecipeCreator
{
    public function __construct(private RecipeRepository $repository)
    {
    }

    public function __invoke(Recipe $recipe): void
    {
        $this->repository->save($recipe);
    }
}
