<?php

declare(strict_types=1);

namespace App\Import\Domain;

use App\Cookery\Recipes\Domain\RecipeComponentCollection;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Import\Domain\TabatkinsIngredientAdapter;
use App\Ingredients\IngredientCollection;

final class TabatkinsRecipeAdapter implements RecipeInterface
{
    private IngredientCollection $ingredients;

    public function __construct(private array $recipe)
    {
        // $this->ingredients = new IngredientCollection(array_map(
        //     fn (string $ingredient) => new TabatkinsIngredientAdapter($ingredient),
        //     $recipe['ingredients']
        // ));
    }

    public function name(): string
    {
        return $this->recipe['name'];
    }

    public function ingredients(): IngredientCollection
    {
        return $this->ingredients;
    }

    public function components(): RecipeComponentCollection
    {
        return new RecipeComponentCollection();
    }
}
