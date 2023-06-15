<?php

declare(strict_types=1);

namespace App\Import\Infrastructure\Importer;

use App\Cookery\Ingredients\Domain\IngredientCollection;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipeComponentInterface;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Import\Application\IngredientsImporter;
use App\Import\Application\RecipesImporter;
use App\Import\Domain\RecipeImporter;
use App\Import\Domain\TabatkinsRecipeAdapter;
use App\Shared\Domain\Utils;

use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\map;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

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

        $style = new SymfonyStyle(new ArgvInput(), new ConsoleOutput());

        $ingredientsProgressBar = new ProgressBar($style, $ingredients->unique()->count());
        $recipesProgressBar = new ProgressBar($style, $recipes->count());

        $style->info('[Tabatkins] Importing ingredients...');
        apply($this->ingredientsImporter, [
            $ingredients->unique(),
            fn () => $ingredientsProgressBar->advance(),
            fn () => $ingredientsProgressBar->finish(),
        ]);
        $style->info('[Tabatkins] Importing recipes...');
        apply($this->recipesImporter, [
            $recipes,
            fn () => $recipesProgressBar->advance(),
            fn () => $recipesProgressBar->finish(),
        ]);
    }
}
