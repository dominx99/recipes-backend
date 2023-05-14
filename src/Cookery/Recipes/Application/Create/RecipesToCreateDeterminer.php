<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Create;

use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Ingredients\Domain\IngredientRepository;
use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipeComponent;
use App\Cookery\Recipes\Domain\RecipeComponentInterface;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Shared\Domain\ValueObject\Uuid;
use InvalidArgumentException;

final class RecipesToCreateDeterminer
{
    public function __construct(private IngredientRepository $ingredientRepository)
    {
    }

    public function __invoke(RecipeCollection $existingRecipes, RecipeCollection $newRecipes): RecipeCollection
    {
        $ingredientEntities = $this->ingredientRepository->all();
        $ingredientEntitiesMap = array_combine(
            $ingredientEntities->map(fn (IngredientInterface $ingredient) => $ingredient->name())->toArray(),
            $ingredientEntities->map(fn (IngredientInterface $ingredient) => $ingredient)->toArray(),
        );

        return $newRecipes
            ->filter(fn (RecipeInterface $newRecipe) => !$existingRecipes->exists(
                fn ($key, RecipeInterface $existingRecipe) => $newRecipe->externalIdentifier() === $existingRecipe->externalIdentifier()
            ))
            ->filter(fn (RecipeInterface $newRecipe) => $newRecipe->name() !== '')
            ->filter(fn (RecipeInterface $newRecipe) => $newRecipe->name() !== 'test')
            ->map(fn (RecipeInterface $recipe) => $this->replaceComponentsIngredients($recipe, $ingredientEntitiesMap))
        ;
    }

    private function replaceComponentsIngredients(
        RecipeInterface $recipe,
        array $ingredientEntitiesMap
    ): RecipeInterface {
        $components = $recipe->components()
            ->filter(fn (RecipeComponentInterface $component) =>
                array_key_exists($component->ingredient()->name(), $ingredientEntitiesMap)
            )
            ->map(function (RecipeComponentInterface $component) use ($ingredientEntitiesMap) {
                return RecipeComponent::new(
                    Uuid::random(),
                    $ingredientEntitiesMap[$component->ingredient()->name()],
                    $component->measure(),
                );
            })
        ;

        return Recipe::new(
            id: Uuid::random(),
            name: $recipe->name(),
            components: $components,
            externalIdentifier: $recipe->externalIdentifier(),
            published: true,
        );
    }
}
