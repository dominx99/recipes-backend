<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain;

use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Measures\Domain\Measure;

interface RecipeComponentInterface
{
    public function ingredient(): IngredientInterface;

    public function measure(): Measure;
}
