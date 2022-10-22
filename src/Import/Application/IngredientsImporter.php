<?php

declare(strict_types=1);

namespace App\Import\Application;

use App\Cookery\Ingredients\Application\Create\IngredientCreator;
use App\Cookery\Ingredients\Application\Create\IngredientsToCreateDeterminer;
use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Ingredients\Domain\IngredientRepository;
use App\Cookery\Ingredients\Domain\IngredientCollection;

use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\each;

final class IngredientsImporter
{
    public function __construct(
        private IngredientRepository $repository,
        private IngredientCreator $creator,
        private IngredientsToCreateDeterminer $ingredientsToCreateDeterminer,
    ) {
    }

    public function __invoke(IngredientCollection $ingredients)
    {
        $existingIngredients = $this->repository->all();

        $ingredientsToCreate = apply($this->ingredientsToCreateDeterminer, [$existingIngredients, $ingredients]);

        each(fn (IngredientInterface $ingredient) => apply($this->creator, [$ingredient]), $ingredientsToCreate);
    }
}
