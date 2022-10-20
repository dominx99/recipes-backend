<?php

declare(strict_types=1);

namespace App\Tests;

final class RecipeTest
{
    public function __invoke()
    {
        $storage = new Storage();

        $storage->add([
            new Item('milk'),
            new Item('bread'),
        ]);

        $recipeMatcher = new RecipeMatcher();

        $recipeMatcher->addIngredients(map(
            fn (Item $item) => new StorageItemToIngredientAdapter($item),
            $storage
        ));
        $recipeMatcher->addBook(new RecipeBook($recipes));

        $recipes = $cook->matchRecipes();
    }
}
