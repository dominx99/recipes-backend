<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain;

interface RecipeInterface
{
    public function name(): string;

    public function components(): RecipeComponentCollection;
}
