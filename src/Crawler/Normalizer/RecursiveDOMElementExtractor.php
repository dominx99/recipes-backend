<?php

declare(strict_types=1);

namespace App\Crawler\Normalizer;

use DOMElement;

use function Lambdish\Phunctional\map;

final class RecursiveDOMElementExtractor
{
    public static function extract(DOMElement $element)
    {
        // $element->attributes->getNamedItem();
        //
        // return [
        //     'attributes' => DOMAttributesExtractor::extract($element->attributes),
        //     'childNodes' => $element->hasChildNodes()
        //         ? map(
        //             fn (DOMElement $secondaryElement)
        //                 => RecursiveDOMElementExtractor::extract($secondaryElement),
        //             $element->childNodes->getIterator()
        //         )
        //         : [],
        // ];
    }
}
