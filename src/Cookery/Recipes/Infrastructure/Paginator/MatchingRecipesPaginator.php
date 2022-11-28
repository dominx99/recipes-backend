<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Infrastructure\Paginator;

use App\Cookery\Recipes\Domain\MatchingRecipeCollection;

final class MatchingRecipesPaginator
{
    private const ITEMS_PER_PAGE = 10;

    public function __construct(
        private MatchingRecipeCollection $collection,
        private int $perPage = self::ITEMS_PER_PAGE,
    ) {
    }

    // TODO: Change return to value object
    public function paginate(int $page): array
    {
        $data = new MatchingRecipeCollection(
            $this->collection->slice(($page - 1) * $this->perPage, $this->perPage)
        );

        $totalPages = (int) ceil($this->collection->count() / $this->perPage);

        return [
            'data' => $data->toArray(),
            'meta' => [
                'currentPage' => $page,
                'perPage' => $this->perPage,
                'total' => $this->collection->count(),
                'totalPages' => $totalPages,
                'hasNextPage' => $page < $totalPages
            ],
        ];
    }
}
