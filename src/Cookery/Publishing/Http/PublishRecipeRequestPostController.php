<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Http;

use App\Auth\Domain\User;
use App\Cookery\Publishing\Application\Create\CreatePublishRecipeRequestCommand;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Http\Symfony\ApiController;
use App\Shared\Http\Symfony\SuccessResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class PublishRecipeRequestPostController extends ApiController
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    #[Route('/api/v1/recipes/{recipeId}/publish', methods: ['POST'])]
    public function __invoke(string $recipeId): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->messageBus->dispatch(new CreatePublishRecipeRequestCommand(
            Uuid::random(),
            $user->getId(),
            Uuid::fromString($recipeId),
        ));

        return new SuccessResponse();
    }
}
