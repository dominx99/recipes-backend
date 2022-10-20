<?php

namespace App\Command;

use App\Collection\RecipeCollection;
use App\Cookery\Recipes\Domain\Recipe;
use App\Crawler\CrawlerAttributes;
use App\Crawler\DOMElementToRecipeAdapter;
use App\Enum\RootNodeEnum;
use App\Shared\Domain\ValueObject\Uuid;
use DOMAttr;
use DOMElement;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

#[AsCommand(
    name: 'exl:import:recipes',
    description: 'Add a short description for your command',
)]
class ExlImportRecipesCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $content = file_get_contents('./app/CommonRecipes.exl');

        $crawler = new Crawler($content);

        $data = [];

        $recipes = new RecipeCollection();
        // $ingredients = new IngredientCollection();

        /* @var DOMElement $secondaryElement */
        /* @var DOMAttr $attribute */
        foreach ($crawler as $rootElement) {
            foreach ($rootElement->childNodes as $secondaryElement) {
                if ($secondaryElement->nodeName === RootNodeEnum::RECIPE->value) {
                    $adapter = new DOMElementToRecipeAdapter($secondaryElement);

                    $recipe = Recipe::new(
                        (string) Uuid::random(),
                        $adapter->name(),
                        $adapter->ingredients(),
                    );

                    $recipes->add($recipe);
                    // $recipes->add(Recipe::create(
                    //     CrawlerAttributes::create($secondaryElement->attributes)->toArray(),
                    // ));
                }
                // $recipes->add(Recipe::fromArray());
                // $ingredients->add(Ingredient::fromArray());
            }
        }

        dd($recipes);

        return Command::SUCCESS;
    }
}
