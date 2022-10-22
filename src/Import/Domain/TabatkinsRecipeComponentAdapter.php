<?php

declare(strict_types=1);

namespace App\Import\Domain;

use App\Cookery\Ingredients\Domain\Ingredient;
use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Measures\Domain\Measure;
use App\Cookery\Recipes\Domain\RecipeComponentInterface;
use App\Shared\Domain\Enum\Unit;
use App\Shared\Domain\ValueObject\Fraction;
use App\Shared\Domain\ValueObject\Uuid;

final class TabatkinsRecipeComponentAdapter implements RecipeComponentInterface
{
    private Ingredient $ingredient;
    private ?Measure $measure;

    public function __construct(string $rawIngredient)
    {
        $units = sprintf('%s|', implode('|', Unit::values()));
        preg_match(
            sprintf('/^((([0-9]+\/[0-9]+)|([0-9]+ ([0-9]+\/[0-9]+))|([0-9]+)|([0-9]+\.[0-9]+)) )?(%s)?(.*)/i', $units),
            $rawIngredient,
            $matches
        );

        $this->ingredient = $this->evaluateIngredient(isset($matches[9]) ? trim($matches[9]) : '');
        $this->measure = $this->evaluateMeasure($matches[2] ?? null, $matches[8] ?? null);
    }

    public function ingredient(): IngredientInterface
    {
        return $this->ingredient;
    }

    public function measure(): ?Measure
    {
        return $this->measure;
    }

    private function evaluateIngredient(string $name): IngredientInterface
    {
        return Ingredient::new((string) Uuid::random(), $name);
    }

    private function evaluateMeasure(?string $fraction, ?string $unit): ?Measure
    {
        $unit = strtolower($unit ?? '');

        if (!$fraction) {
            return null;
        }

        $unit = Unit::tryFrom($unit) ?? Unit::NONE;

        $formattedQuantity = trim($fraction);
        $numericQuantity = (new Fraction($formattedQuantity))->toNumber();

        return new Measure(
            $unit,
            $numericQuantity,
            $formattedQuantity,
        );
    }
}
