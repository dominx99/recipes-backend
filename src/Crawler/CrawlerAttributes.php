<?php

declare(strict_types=1);

namespace App\Crawler;

use DOMAttr;
use DOMNamedNodeMap;

final class CrawlerAttributes
{
    /** @var iterable<int, DOMAttr> */
    private DOMNamedNodeMap $attributes;

    private function __construct(DOMNamedNodeMap $attributes)
    {
        $this->attributes = $attributes;
    }

    public static function create(DOMNamedNodeMap $attributes): CrawlerAttributes
    {
        return new CrawlerAttributes($attributes);
    }

    /**
     * @return array<?string,?string>
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->attributes as $attribute) {
            $result[$attribute->nodeName] = $attribute->nodeValue;
        }

        return $result;
    }
}
