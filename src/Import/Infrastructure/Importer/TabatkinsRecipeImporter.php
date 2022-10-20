<?php

declare(strict_types=1);

namespace App\Import\Infrastructure\Importer;

use App\Cookery\Recipes\Application\Create\RecipeCreator;
use App\Cookery\Recipes\Domain\Recipe;
use App\Import\Domain\RecipeImporter;
use App\Import\Infrastructure\Adapter\TabatkinsRecipeAdapter;
use App\Shared\Domain\Utils;

use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\each;

final class TabatkinsRecipeImporter implements RecipeImporter
{
    public function __construct(private RecipeCreator $recipeCreator)
    {
    }

    public function __invoke(string $content): void
    {
        $rawRecipes = Utils::jsonDecode($content);

        each(function (array $rawRecipe) {
            $recipe = Recipe::fromRecipe(new TabatkinsRecipeAdapter($rawRecipe));

            apply($this->recipeCreator, [$recipe]);
        }, $rawRecipes);
    }
}
