<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\Utils;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RecipesByIdsGetController extends ApiController
{
    public function __construct(private RecipeRepository $recipeRepository)
    {
    }

    #[Route('/api/v1/recipes/by-ids', name: 'api_v1_recipes_by_ids', methods: ['GET'], priority: 100)]
    public function __invoke(Request $request): Response
    {
        $page = (int) $request->query->get('page', 1) ?? 1;
        $offset = ($page * 24) - 24;
        $limit = 24;

        $params = Utils::jsonDecode($request->getContent());

        $recipeIds = $params['recipeIds'];

        $recipes = $this->recipeRepository->matchByIds($recipeIds, $offset, $limit);

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
