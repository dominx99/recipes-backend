<?php

declare(strict_types=1);

namespace App\Cookery\RecipeComponents\Http;

use App\Cookery\RecipeComponents\Application\Create\CreateRecipeComponentCommand;
use App\Shared\Domain\Utils;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class RecipeComponentPostController extends ApiController
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    #[Route('/api/v1/recipe-components', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $id = Uuid::random();

        $params = Utils::jsonDecode($request->getContent());

        $this->messageBus->dispatch(new CreateRecipeComponentCommand(
            $id,
            Uuid::fromString($params['recipeId']),
            $params['name'],
            $params['quantity'],
            $params['unit']
        ));

        return new JsonResponse([
            'id' => $id->toString(),
        ]);
    }
}
