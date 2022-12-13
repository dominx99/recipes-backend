<?php

declare(strict_types=1);

namespace App\Import\Application;

use App\Import\Domain\EshaRecipeSource;
use App\Import\Domain\RecipeSource;
use App\Import\Domain\TabatkinsRecipeSource;

use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\each;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ImportAllSourcesCommandHandler implements MessageHandlerInterface
{
    public function __construct(
        private RecipesImporter $recipesImporter,
        private IngredientsImporter $ingredientsImporter
    ) {
    }

    public function __invoke(ImportAllSourcesCommand $command): void
    {
        $sources = [
            new TabatkinsRecipeSource(),
            new EshaRecipeSource(),
        ];

        each(function (RecipeSource $source) {
            $importerClass = $source->importer();
            $importer = new $importerClass($this->recipesImporter, $this->ingredientsImporter);

            apply($importer, [$source->content()]);
        }, $sources);
    }
}
