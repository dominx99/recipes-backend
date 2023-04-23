<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Cookery\Products\Domain\ProductRepository;
use App\Cookery\Recipes\Domain\MatchingRecipeCollection;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\Collection\ArrayCollection;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RecipesMatchByProductsGetController extends ApiController
{
    public function __construct(
        private RecipeRepository $recipeRepository,
        private ProductRepository $productRepository,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[Route('api/v1/recipes/match-by-products', name: 'api_v1_recipes_match_by_products', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $products = new ArrayCollection($request->get('products') ?? []);
        $page = (int) $request->query->get('page', 1) ?? 1;

        $offset = ($page * 24) - 24;
        $limit = 24;

        $matchingRecipes = !$products->isEmpty()
            ? $this->recipeRepository->matchByIngredients($products->toArray(), $offset, $limit + 1)
            : new MatchingRecipeCollection();

        return $this->respond([
            'data' => $matchingRecipes->pop()->toArray(),
            'meta' => [
                'page' => $page,
                'has_next_page' => $matchingRecipes->count() > $limit,
            ],
        ]);
    }
}
