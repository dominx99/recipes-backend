<?php

declare(strict_types=1);

namespace App\Cookery\RecipeComponents\Domain;

use App\Cookery\Recipes\Domain\RecipeComponent;

interface RecipeComponentRepository
{
    public function save(RecipeComponent $component): void;
}
