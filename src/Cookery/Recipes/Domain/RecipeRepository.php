<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain;

use App\Recipes\Domain\RecipeCollection;
use App\Shared\Domain\AggregateRoot;

interface RecipeRepository
{
    public function all(): RecipeCollection;

    public function save(AggregateRoot $recipe): void;
}
