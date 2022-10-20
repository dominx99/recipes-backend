<?php

declare(strict_types=1);

namespace App\Import\Domain;

interface RecipeSource
{
    public function content(): mixed;

    public function importer(): string;
}
