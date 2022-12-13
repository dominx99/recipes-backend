<?php

declare(strict_types=1);

namespace App\Import\Domain\Esha;

use App\Import\Domain\RecipeSource;
use App\Import\Infrastructure\Importer\EshaImporter;

final class EshaCommonRecipesSource implements RecipeSource
{
    public function content(): string
    {
        return file_get_contents('/application/assets/recipe-sources/CommonRecipes.exl');
    }

    public function importer(): string
    {
        return EshaImporter::class;
    }
}
