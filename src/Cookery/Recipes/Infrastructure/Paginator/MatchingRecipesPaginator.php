<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Infrastructure\Paginator;

use App\Cookery\Recipes\Domain\MatchingRecipeCollection;

final class MatchingRecipesPaginator
{
    private const ITEMS_PER_PAGE = 10;

    public function __construct(
        private MatchingRecipeCollection $collection,
    ) {
    }

    public function itemsForPage(int $page): MatchingRecipeCollection
    {
        return new MatchingRecipeCollection(
            $this->collection->slice(($page - 1) * self::ITEMS_PER_PAGE, self::ITEMS_PER_PAGE)
        );
    }
}
