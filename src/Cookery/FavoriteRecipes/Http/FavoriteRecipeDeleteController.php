<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Http;

use App\Cookery\FavoriteRecipes\Application\Remove\RemoveRecipeFromFavoritesCommand;
use App\Shared\Http\Symfony\ApiController;
use App\Shared\Http\Symfony\SuccessResponse;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class FavoriteRecipeDeleteController extends ApiController
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
    }

    #[Route('/api/v1/favorite-recipes/{id}', name: 'api_v1_delete_favorite_recipe', methods: ['DELETE'])]
    public function __invoke(UuidInterface $id): JsonResponse
    {
        $this->bus->dispatch(new RemoveRecipeFromFavoritesCommand($id));

        return new SuccessResponse(SuccessResponse::HTTP_NO_CONTENT);
    }
}
