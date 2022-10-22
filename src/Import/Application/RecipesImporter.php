<?php

declare(strict_types=1);

namespace App\Import\Application;

use App\Cookery\Recipes\Application\Create\RecipeCreator;
use App\Cookery\Recipes\Application\Create\RecipesToCreateDeterminer;
use App\Cookery\Recipes\Application\Updator\RecipesToUpdateDeterminer;
use App\Cookery\Recipes\Application\Updator\RecipeUpdator;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Cookery\Recipes\Domain\RecipeCollection;

use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\each;

final class RecipesImporter
{
    public function __construct(
        private RecipeRepository $repository,
        private RecipeCreator $creator,
        private RecipeUpdator $updator,
        private RecipesToCreateDeterminer $recipesToCreateDeterminer,
        private RecipesToUpdateDeterminer $recipesToUpdateDeterminer,
    ) {
    }

    public function __invoke(RecipeCollection $recipes): void
    {
        $existingRecipes = $this->repository->all();

        $recipesToCreate = apply($this->recipesToCreateDeterminer, [$existingRecipes, $recipes]);
        $recipesToUpdate = apply($this->recipesToUpdateDeterminer, [$existingRecipes, $recipes]);

        each(fn (RecipeInterface $recipe) => apply($this->creator, [$recipe]), $recipesToCreate);
        each(fn (RecipeInterface $recipe) => apply($this->updator, [$recipe]), $recipesToUpdate);
    }
}
