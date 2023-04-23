<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Infrastructure\Paginator;

use App\Cookery\Recipes\Domain\MatchingRecipeCollection;
use App\Cookery\Recipes\Domain\ValueObject\MatchingRecipe;
use App\Shared\Domain\ValueObject\PaginationResult;
use Closure;
use Ramsey\Uuid\UuidInterface;

final class MatchingRecipesPaginator
{
    private const ITEMS_PER_PAGE = 10;

    public function __construct(
        private MatchingRecipeCollection $collection,
        private ?Closure $nextPageUrlCallback,
        private int $perPage = self::ITEMS_PER_PAGE,
        private ?UuidInterface $lastId = null,
    ) {
    }

    public function paginate(): PaginationResult
    {
        if (is_null($this->lastId)) {
            return $this->paginateFromOffset(0);
        }

        $offset = $this->collection
            ->map(fn (MatchingRecipe $recipe) => $recipe->recipe()->id()->toString())
            ->indexOf($this->lastId->toString());

        if (false === $offset) {
            return PaginationResult::empty();
        }

        return $this->paginateFromOffset($offset + 1);
    }

    private function paginateFromOffset(int $offset): PaginationResult
    {
        $result = array_values($this->collection->slice($offset, $this->perPage));
        $nextId = isset($result[$this->perPage - 1]) ? $result[$this->perPage - 1]->recipe()->id() : null;

        $nextPageUrl = $nextId ? $this->nextPageUrlCallback->__invoke($nextId) : null;
        $isFirstPage = 0 === $offset;

        return PaginationResult::new(
            $result,
            $isFirstPage,
            $nextPageUrl,
        );
    }
}
