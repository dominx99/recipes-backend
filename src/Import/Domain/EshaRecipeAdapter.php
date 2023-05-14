<?php

declare(strict_types=1);

namespace App\Import\Domain;

use App\Cookery\Recipes\Domain\RecipeComponentCollection;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Enum\RootNodeEnum;
use App\Import\Domain\Esha\EshaRecipeComponentAdapter;
use DOMElement;

use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\map;

final class EshaRecipeAdapter implements RecipeInterface
{
    private RecipeComponentCollection $components;

    public function __construct(private DOMElement $element)
    {
        $ingredientElements = array_values(filter(
            fn (DOMElement $secondaryElement) => 'RecipeItem' === $secondaryElement->nodeName,
            $this->element->childNodes,
        ));

        $this->components = new RecipeComponentCollection(map(
            fn (DOMElement $ingredientElement) => new EshaRecipeComponentAdapter($ingredientElement),
            $ingredientElements
        ));
    }

    public function name(): string
    {
        return $this->element->attributes->getNamedItem('description')->nodeValue;
    }

    public function components(): RecipeComponentCollection
    {
        return $this->components;
    }

    public function externalIdentifier(): string
    {
        return sprintf('%s.%s', 'esha', $this->element->attributes->getNamedItem('primaryKey')->nodeValue);
    }

    public function instructions(): ?string
    {
        foreach ($this->element->childNodes as $childNode) {
            if (RootNodeEnum::INSTRUCTIONS->value === $childNode->nodeName) {
                return $childNode->nodeValue;
            }
        }

        return null;
    }
}
