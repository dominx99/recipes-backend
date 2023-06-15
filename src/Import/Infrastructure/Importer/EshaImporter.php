<?php

declare(strict_types=1);

namespace App\Import\Infrastructure\Importer;

use App\Cookery\Ingredients\Domain\IngredientCollection;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipeComponentInterface;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Enum\RootNodeEnum;
use App\Import\Application\IngredientsImporter;
use App\Import\Application\RecipesImporter;
use App\Import\Domain\EshaRecipeAdapter;
use App\Import\Domain\RecipeImporter;

use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\each;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;

final class EshaImporter implements RecipeImporter
{
    public function __construct(
        private RecipesImporter $recipesImporter,
        private IngredientsImporter $ingredientsImporter,
    ) {
    }

    public function __invoke(string $content): void
    {
        $elements = new Crawler($content);

        $recipes = new RecipeCollection();

        foreach ($elements as $rootElement) {
            foreach ($rootElement->childNodes as $secondaryElement) {
                if ($secondaryElement->nodeName !== RootNodeEnum::RECIPE->value) {
                    continue;
                }

                $recipe = new EshaRecipeAdapter($secondaryElement);

                $recipes->add($recipe);
            }
        }

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

        $style->info('[Esha] Importing ingredients...');
        apply($this->ingredientsImporter, [
            $ingredients->unique(),
            fn () => $ingredientsProgressBar->advance(),
            fn () => $ingredientsProgressBar->finish(),
        ]);
        $style->info('[Esha] Importing recipes...');
        apply($this->recipesImporter, [
            $recipes,
            fn () => $recipesProgressBar->advance(),
            fn () => $ingredientsProgressBar->finish(),
        ]);
    }
}
