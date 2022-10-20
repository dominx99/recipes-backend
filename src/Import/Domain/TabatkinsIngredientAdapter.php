<?php

declare(strict_types=1);

namespace App\Import\Domain;

use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Ingredients\Domain\IngredientMeasure;
use App\Shared\Domain\Enum\Unit;
use App\Shared\Domain\ValueObject\Fraction;

final class TabatkinsIngredientAdapter implements IngredientInterface
{
    private string $name;
    private ?IngredientMeasure $measure;

    public function __construct(private string $ingredient)
    {
        $units = sprintf('%s|', implode('|', Unit::values()));
        preg_match(
            sprintf('/^(([0-9]\/[0-9])|([0-9] ([0-9]\/[0-9]))|([0-9])) (%s)(.*)/i', $units),
            $ingredient,
            $matches
        );

        $this->name = isset($matches[7]) ? trim($matches[7]) : '';
        $this->evaluateMeasure($matches[1] ?? null, $matches[6] ?? null);
    }

    public function measure(): ?IngredientMeasure
    {
        return $this->measure;
    }

    public function name(): string
    {
        return $this->name;
    }

    private function evaluateMeasure(?string $fraction, ?string $unit): void
    {
        $unit = strtolower($unit ?? '');

        if (!$fraction) {
            $this->measure = null;

            return;
        }

        $unit = Unit::tryFrom($unit) ?? Unit::NONE;

        $formattedQuantity = trim($fraction);
        $numericQuantity = (new Fraction($formattedQuantity))->toNumber();

        $this->measure = new IngredientMeasure(
            $unit,
            $numericQuantity,
            $formattedQuantity,
        );
    }
}
