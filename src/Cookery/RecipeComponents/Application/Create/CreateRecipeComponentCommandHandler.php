<?php

declare(strict_types=1);

namespace App\Cookery\RecipeComponents\Application\Create;

use App\Cookery\Ingredients\Application\Create\CreateIngredientCommand;
use App\Cookery\Ingredients\Domain\Ingredient;
use App\Cookery\Ingredients\Domain\IngredientRepository;
use App\Cookery\Measures\Domain\Measure;
use App\Cookery\RecipeComponents\Domain\RecipeComponentRepository;
use App\Cookery\Recipes\Domain\RecipeComponent;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\Enum\Unit;
use App\Shared\Domain\ValueObject\Fraction;
use App\Shared\Domain\ValueObject\Uuid;
use DomainException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class CreateRecipeComponentCommandHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly IngredientRepository $ingredientRepository,
        private readonly RecipeRepository $recipeRepository,
        private readonly RecipeComponentRepository $recipeComponentRepository,
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(CreateRecipeComponentCommand $command): void
    {
        $ingredient = $this->getIngredient($command->name);

        $recipe = $this->recipeRepository->findOne($command->recipeId);
        $measure = new Measure(
            Unit::from($command->unit),
            (new Fraction((string) $command->quantity))->toNumber(),
            (string) round((float) $command->quantity, 2),
        );

        $component = RecipeComponent::new(
            $command->id,
            $ingredient,
            $measure,
        );

        $component->setRecipe($recipe);

        $this->recipeComponentRepository->save($component);
    }

    private function getIngredient(string $ingredientName): Ingredient
    {
        $ingredient = $this->ingredientRepository->matchingOne($ingredientName);

        if (!is_null($ingredient)) {
            return $ingredient;
        }

        $ingredientId = Uuid::random();

        if (!$ingredient) {
            $this->messageBus->dispatch(
                new CreateIngredientCommand(
                    $ingredientId,
                    $ingredientName
                )
            );
        }

        $ingredient = $this->ingredientRepository->findOne($ingredientId);

        if (is_null($ingredient)) {
            throw new DomainException('Something went wrong while creating ingredient');
        }

        return $ingredient;
    }
}
