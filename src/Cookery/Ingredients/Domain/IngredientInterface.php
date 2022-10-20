<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Domain;

interface IngredientInterface
{
    public function measure(): ?IngredientMeasure;

    public function name(): string;
}
