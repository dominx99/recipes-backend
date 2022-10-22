<?php

declare(strict_types=1);

namespace App\Import\Domain;

use App\Cookery\Recipes\Domain\RecipeComponentCollection;
use App\Cookery\Recipes\Domain\RecipeInterface;

final class TabatkinsRecipeAdapter implements RecipeInterface
{
    private RecipeComponentCollection $components;

    public function __construct(private array $recipe)
    {
        $this->components = new RecipeComponentCollection(array_map(
            fn (string $ingredient) => new TabatkinsRecipeComponentAdapter($ingredient),
            $recipe['ingredients']
        ));
    }

    public function name(): string
    {
        return $this->recipe['name'];
    }

    public function components(): RecipeComponentCollection
    {
        return $this->components;
    }

    public function externalIdentifier(): string
    {
        return sprintf('%s.%s', 'tabatkins', $this->recipe['id']);
    }
}
