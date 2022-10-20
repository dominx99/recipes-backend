<?php

declare(strict_types=1);

namespace App\Import\Domain;

use App\Import\Infrastructure\Importer\TabatkinsRecipeImporter;

final class TabatkinsRecipeSource implements RecipeSource
{
    public function content(): string
    {
        return file_get_contents('/application/assets/recipe-sources/tabatkins.json');
    }

    public function importer(): string
    {
        return TabatkinsRecipeImporter::class;
    }
}
