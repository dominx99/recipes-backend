<?php

declare(strict_types=1);

namespace App\Import\Infrastructure\Importer;

use App\Cookery\Ingredients\Domain\IngredientCollection;
use App\Cookery\Recipes\Domain\RecipeComponentInterface;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Crawler\DOMElementToRecipeAdapter;
use App\Enum\RootNodeEnum;
use App\Import\Application\IngredientsImporter;
use App\Import\Application\RecipesImporter;
use App\Import\Domain\EshaRecipeAdapter;
use App\Import\Domain\RecipeImporter;
use Symfony\Component\DomCrawler\Crawler;

use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\each;

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

        apply($this->ingredientsImporter, [$ingredients->unique()]);
        apply($this->recipesImporter, [$recipes]);
    }
}
