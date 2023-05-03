<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Auth\Domain\User;
use App\Cookery\RecipeComponents\Application\Create\CreateRecipeComponentCommand;
use App\Cookery\Recipes\Application\Create\CreateRecipeCommand;
use App\Cookery\Recipes\Application\Update\UpdateRecipeComponentsCountCommand;
use App\Cookery\Recipes\Http\Request\RecipePostRequest;
use App\Shared\Domain\Utils;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Http\Symfony\ApiController;
use DomainException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class RecipePostController extends ApiController
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly MessageBusInterface $messageBus
    ) {
    }

    #[Route('/api/v1/recipes', methods: ['POST'])]
    public function __invoke(RecipePostRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();

        if (!$user) {
            throw new DomainException('User not found');
        }

        $id = Uuid::random();

        $this->messageBus->dispatch(
            new CreateRecipeCommand(
                $id,
                $request->name,
                $user->getId(),
            )
        );

        foreach ($request->components as $component) {
            $this->messageBus->dispatch(
                new CreateRecipeComponentCommand(
                    Uuid::random(),
                    $id,
                    $component['name'],
                    $component['quantity'],
                    $component['unit'],
                )
            );
        }

        $this->messageBus->dispatch(new UpdateRecipeComponentsCountCommand($id, count($body['components'] ?? [])));

        return new JsonResponse([
            'id' => $id->toString(),
        ]);
    }
}
