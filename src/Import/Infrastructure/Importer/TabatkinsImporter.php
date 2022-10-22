<?php

declare(strict_types=1);

namespace App\Import\Infrastructure\Importer;

use App\Cookery\Recipes\Domain\RecipeComponentInterface;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Import\Application\IngredientsImporter;
use App\Import\Application\RecipesImporter;
use App\Import\Domain\RecipeImporter;
use App\Import\Domain\TabatkinsRecipeAdapter;
use App\Ingredients\IngredientCollection;
use App\Recipes\Domain\RecipeCollection;
use App\Shared\Domain\Utils;

use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\map;

final class TabatkinsImporter implements RecipeImporter
{
    public function __construct(
        private RecipesImporter $recipesImporter,
        private IngredientsImporter $ingredientsImporter,
    ) {
    }

    public function __invoke(string $content): void
    {
        $rawRecipes = Utils::jsonDecode($content);

        $recipes = new RecipeCollection(map(
            fn (array $rawRecipe) => new TabatkinsRecipeAdapter($rawRecipe),
            $rawRecipes
        ));

        $ingredients = new IngredientCollection();

        each(
            fn (RecipeInterface $recipe) => each(
                fn (RecipeComponentInterface $component) => $ingredients->add($component->ingredient()),
                $recipe->components()
            ),
            $recipes
        );

        apply($this->ingredientsImporter, [$ingredients->unique()]);
        apply($this->recipesImporter, [$recipes]);
    }
}
