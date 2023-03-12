<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Http;

use App\Cookery\FavoriteRecipes\Application\Create\AddRecipeToFavoritesCommand;
use App\Shared\Domain\Utils;
use App\Shared\Http\Symfony\ApiController;
use App\Shared\Http\Symfony\SuccessResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

final class FavoriteRecipePostController extends ApiController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly TokenStorageInterface $token,
    ) {
    }

    #[Route('/api/v1/favorite-recipes', name: 'api_v1_favorite_recipe_post', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        /** @var \App\Auth\Domain\User $user */
        $user = $this->token->getToken()->getUser();

        $body = Utils::jsonDecode($request->getContent());

        $this->validateRequest($body);

        $this->messageBus->dispatch(new AddRecipeToFavoritesCommand(
            $body['id'],
            $body['recipe_id'],
            $user->getId(),
        ));

        return new SuccessResponse();
    }

    protected function constraints(): Constraint
    {
        return new Assert\Collection([
            'id' => new Assert\Uuid(),
            'recipe_id' => new Assert\Uuid(),
        ]);
    }
}
