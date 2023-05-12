<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Http;

use App\Cookery\Publishing\Domain\PublishRecipeRequest;
use App\Cookery\Publishing\Domain\PublishRecipeRequestRepository;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RequestedRecipesGetController extends ApiController
{
    public function __construct(
        private readonly PublishRecipeRequestRepository $requestsRepository,
        private readonly RecipeRepository $recipeRepository
    ) {
    }

    #[Route('/api/v1/requested-recipes', name: 'requested_recipes', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $page = (int) $request->query->get('page', 1) ?? 1;
        $offset = ($page * 24) - 24;
        $limit = 24;

        $requests = $this->requestsRepository->allPaginated($offset, $limit + 1);
        $recipes = $this->recipeRepository->findMany(
            $requests->map(fn (PublishRecipeRequest $request) => $request->recipeId)->toArray()
        );

        $hasNextPage = false;

        if ($recipes->count() > $limit) {
            $recipes = $recipes->pop();
            $hasNextPage = true;
        }

        return $this->respond([
            'data' => $recipes->toArray(),
            'meta' => [
                'page' => $page,
                'has_next_page' => $hasNextPage,
            ],
        ]);
    }
}
