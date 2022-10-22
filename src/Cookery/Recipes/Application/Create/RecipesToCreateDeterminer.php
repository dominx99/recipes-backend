<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Create;

use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Ingredients\Domain\IngredientRepository;
use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeComponent;
use App\Cookery\Recipes\Domain\RecipeComponentInterface;
use App\Cookery\Recipes\Domain\RecipeInterface;
use App\Recipes\Domain\RecipeCollection;
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

        return $newRecipes->filter(fn (RecipeInterface $newRecipe) => !$existingRecipes->exists(
            fn ($key, RecipeInterface $existingRecipe) => $newRecipe->externalIdentifier() === $existingRecipe->externalIdentifier()
        ))
            ->map(fn (RecipeInterface $recipe) => $this->replaceComponentsIngredients($recipe, $ingredientEntitiesMap))
        ;
    }

    private function replaceComponentsIngredients(
        RecipeInterface $recipe,
        array $ingredientEntitiesMap
    ): RecipeInterface {
        $components = $recipe->components()->map(function (RecipeComponentInterface $component) use ($ingredientEntitiesMap) {
            if (!array_key_exists($component->ingredient()->name(), $ingredientEntitiesMap)) {
                throw new InvalidArgumentException('Ingredients should be in database before recipes');
            }

            return RecipeComponent::new(
                (string) Uuid::random(),
                $ingredientEntitiesMap[$component->ingredient()->name()],
                $component->measure(),
            );
        });

        return Recipe::new((string) Uuid::random(), $recipe->externalIdentifier(), $recipe->name(), $components);
    }
}
