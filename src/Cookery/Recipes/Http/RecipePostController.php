<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Cookery\Recipes\Application\Create\CreateRecipeCommand;
use App\Shared\Domain\Utils;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class RecipePostController extends ApiController
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    #[Route('/api/v1/recipes', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $id = Uuid::random();

        $body = Utils::jsonDecode($request->getContent(), true);

        $this->messageBus->dispatch(
            new CreateRecipeCommand(
                $id,
                $body['name'],
            )
        );

        return new JsonResponse([
            'id' => $id->toString(),
        ]);
    }
}
