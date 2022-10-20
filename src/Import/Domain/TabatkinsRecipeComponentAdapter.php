<?php

declare(strict_types=1);

namespace App\Import\Domain;

use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Measures\Domain\Measure;
use App\Cookery\Measures\Domain\Unit;
use App\Shared\Domain\ValueObject\Fraction;
use App\Cookery\Recipes\Domain\RecipeComponentInterface;

final class TabatkinsRecipeComponentAdapter implements RecipeComponentInterface
{
    private array $rawIngredient;

    public function __construct(array $rawIngredient)
    {
        $units = sprintf('%s|', implode('|', Unit::values()));
        preg_match(
            sprintf('/^(([0-9]\/[0-9])|([0-9] ([0-9]\/[0-9]))|([0-9])) (%s)(.*)/i', $units),
            $ingredient,
            $matches
        );

        $this->name = isset($matches[7]) ? trim($matches[7]) : '';
        $this->evaluateMeasure($matches[1] ?? null, $matches[6] ?? null);
        $this->rawIngredient = $rawIngredient;
    }

    public function ingredient(): IngredientInterface
    {
    }

    public function measure(): Measure
    {
    }

    private function evaluateMeasure(): void
    {
        $unit = strtolower($unit ?? '');

        if (!$fraction) {
            $this->measure = null;

            return;
        }

        $unit = Unit::tryFrom($unit) ?? Unit::NONE;

        $formattedQuantity = trim($fraction);
        $numericQuantity = (new Fraction($formattedQuantity))->toNumber();

        $this->measure = new Measure(
            $unit,
            $numericQuantity,
            $formattedQuantity,
        );
    }
}
