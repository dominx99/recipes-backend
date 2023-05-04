<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Auth\Domain\User;
use App\Cookery\Recipes\Application\Remove\RemoveRecipeCommand;
use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class RecipeDeleteController extends ApiController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly RecipeRepository $repository
    ) {
    }

    #[Route('/api/v1/recipes/{id}', name: 'api_v1_recipe_delete', methods: ['DELETE'])]
    public function __invoke(string $id): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var ?Recipe $recipe */
        $recipe = $this->repository->findOne(Uuid::fromString($id));

        if (
            !$user instanceof User
            || !$recipe instanceof Recipe
            || !$recipe->ownerId()->equals($user->getId())
        ) {
            throw new AccessDeniedHttpException();
        }

        $this->messageBus->dispatch(new RemoveRecipeCommand(Uuid::fromString($id)));

        return new JsonResponse([
            'message' => 'No content',
        ], JsonResponse::HTTP_NO_CONTENT);
    }
}
