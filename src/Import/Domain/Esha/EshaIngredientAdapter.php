<?php

declare(strict_types=1);

namespace App\Import\Domain\Esha;

use App\Cookery\Ingredients\Domain\IngredientInterface;
use DOMElement;

final class EshaIngredientAdapter implements IngredientInterface
{
    public function __construct(private DOMElement $element)
    {
    }

    public function name(): string
    {
        return $this->element->attributes->getNamedItem('ItemName')->nodeValue;
    }
}
