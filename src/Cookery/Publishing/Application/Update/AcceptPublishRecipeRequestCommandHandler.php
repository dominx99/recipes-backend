<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Application\Update;

use App\Cookery\Publishing\Domain\PublishRecipeRequestRepository;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class AcceptPublishRecipeRequestCommandHandler
{
    public function __construct(
        private readonly PublishRecipeRequestRepository $publishRecipeRequestRepository,
        private readonly RecipeRepository $recipeRepository,
    ) {
    }

    public function __invoke(AcceptPublishRecipeRequestCommand $command): void
    {
        $request = $this->publishRecipeRequestRepository->findOne(Uuid::fromString($command->id));

        $recipe = $this->recipeRepository->findOne($request->recipeId);
        $recipe->publish();

        $this->recipeRepository->save($recipe);
        $this->publishRecipeRequestRepository->remove($request);
    }
}
