<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Application\Create;

use App\Cookery\Ingredients\Domain\Ingredient;
use App\Cookery\Ingredients\Domain\IngredientRepository;

final class IngredientCreator
{
    public function __construct(private IngredientRepository $repository)
    {
    }

    public function __invoke(Ingredient $ingredient): void
    {
        $this->repository->save($ingredient);
    }
}
