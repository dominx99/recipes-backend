<?php

declare(strict_types=1);

namespace App\Crawler;

use App\Cookery\Recipes\Domain\RecipeComponentCollection;
use App\Cookery\Recipes\Domain\RecipeInterface;
use DOMElement;

final class DOMElementToRecipeAdapter implements RecipeInterface
{
    private DOMElement $element;
    // private array $nodes;

    public function __construct(DOMElement $element)
    {
        $this->element = $element;
        // $raw = [];
        //
        // if (!$element->hasChildNodes()) {
        //     throw new InvalidArgumentException();
        // }

        /* @var DOMElement $secondaryElement */
        // foreach ($element->childNodes as $secondaryElement) {
        //     $raw[$secondaryElement->nodeName] = RecursiveDOMElementExtractor::extract($secondaryElement);
        // }
    }

    public function name(): string
    {
        return $this->element->attributes->getNamedItem('description')->nodeValue;
    }

    public function components(): RecipeComponentCollection
    {
        return new RecipeComponentCollection();
    }

    public function externalIdentifier(): string
    {
        return '1';
    }

    // public function ingredients(): IngredientCollection
    // {
    //     $ingredientElements = array_values(filter(
    //         fn (DOMElement $secondaryElement) => 'RecipeItem' === $secondaryElement->nodeName,
    //         $this->element->childNodes,
    //     ));
    //
    //     $ingredients = map(
    //         fn (DOMElement $ingredientElement) => Ingredient::new(
    //             $ingredientElement->attributes->getNamedItem('ItemName')->nodeValue
    //         ),
    //         $ingredientElements
    //     );
    //
    //     return new IngredientCollection($ingredients);
    // }
}
