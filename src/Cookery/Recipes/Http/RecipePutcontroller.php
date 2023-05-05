<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Cookery\Recipes\Application\Update\UpdateRecipeCommand;
use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Cookery\Recipes\Http\Request\RecipePostRequest;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class RecipePutcontroller extends ApiController
{
    public function __construct(
        private readonly RecipeRepository $repository,
        private MessageBusInterface $messageBus
    ) {
    }

    #[Route('/api/v1/recipes/{id}', methods: ['PUT'])]
    public function __invoke(RecipePostRequest $request, string $id): JsonResponse
    {
        $id = Uuid::fromString($id);
        $recipe = $this->repository->findOne($id);

        if (!$recipe instanceof Recipe) {
            $this->throwNotFound('Recipe not found');
        }

        $this->messageBus->dispatch(new UpdateRecipeCommand($id, $request));

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
