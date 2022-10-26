<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Match;

use App\Cookery\Ingredients\Domain\IngredientCollection;
use App\Cookery\Recipes\Domain\MatchingRecipeCollection;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipesMatcher;

use function Lambdish\Phunctional\apply;

final class RecipesMatcherComposite implements RecipesMatcher
{
    /**
     * @var RecipesMatcher[]
     */
    public array $children;

    public function __construct(RecipesMatcher ...$children)
    {
        $this->children = $children;
    }

    public function __invoke(RecipeCollection $recipes, IngredientCollection $ingredients): MatchingRecipeCollection
    {
        $collection = new MatchingRecipeCollection();

        foreach ($this->children as $matcher) {
            $collection = $collection->merge(apply($matcher, [$recipes, $ingredients]));
        }

        return $collection;
    }
}
