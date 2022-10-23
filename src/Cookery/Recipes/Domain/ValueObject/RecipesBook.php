<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain\ValueObject;

use App\Cookery\Recipes\Domain\RecipeCollection;

final class RecipesBook
{
    /** @var array<string, RecipeCollection> */
    public function __construct(private array $elements = [])
    {
    }

    public function add(string $name, RecipeCollection $collection): void
    {
        $this->elements[$name] = $collection;
    }

    public function merge(RecipesBook $book): RecipesBook
    {
        return new RecipesBook(array_merge($this->elements, $book->all()));
    }

    /**
     * @return array<string,RecipeCollection>
     */
    public function all(): array
    {
        return $this->elements;
    }

    public function keys(): array
    {
        return array_keys($this->elements);
    }

    public function values(): array
    {
        return array_values($this->elements);
    }
}
