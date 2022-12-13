<?php

declare(strict_types=1);

namespace App\Import\Domain;

use App\Import\Infrastructure\Importer\EshaImporter;

final class EshaRecipeSource implements RecipeSource
{
    public function content(): string
    {
        return file_get_contents('/application/assets/recipe-sources/ArmedForcesRecipes.exl');
    }

    public function importer(): string
    {
        return EshaImporter::class;
    }
}
