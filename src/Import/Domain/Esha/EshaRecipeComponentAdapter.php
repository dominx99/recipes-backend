<?php

declare(strict_types=1);

namespace App\Import\Domain\Esha;

use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Measures\Domain\Measure;
use App\Cookery\Recipes\Domain\RecipeComponentInterface;
use App\Shared\Domain\Enum\Unit;
use App\Shared\Domain\ValueObject\Fraction;
use DOMElement;

final class EshaRecipeComponentAdapter implements RecipeComponentInterface
{
    private IngredientInterface $ingredient;
    private Measure $measure;

    public function __construct(private DOMElement $element)
    {
        $this->ingredient = new EshaIngredientAdapter($this->element);

        $quantity = $this->element->attributes->getNamedItem('itemQuantity')->nodeValue;

        $formattedQuantity = trim($quantity);
        $numericQuantity = (new Fraction($formattedQuantity))->toNumber();

        $this->measure = new Measure(
            Unit::NONE,
            $numericQuantity,
            (string) round((float) $formattedQuantity, 2),
        );
    }

    public function ingredient(): IngredientInterface
    {
        return $this->ingredient;
    }

    public function measure(): ?Measure
    {
        return $this->measure;
    }
}
