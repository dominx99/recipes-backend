<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Cookery\Products\Domain\ProductRepository;
use App\Cookery\Recipes\Application\Match\IncompleteRecipesMatcher;
use App\Cookery\Recipes\Application\Match\RecipesMatcherComposite;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Cookery\Recipes\Infrastructure\Paginator\MatchingRecipesPaginator;
use App\Shared\Domain\Collection\ArrayCollection;
use App\Shared\Http\Symfony\ApiController;

use function Lambdish\Phunctional\apply;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RecipesMatchByProductsGetController extends ApiController
{
    public function __construct(
        private RecipeRepository $recipeRepository,
        private ProductRepository $productRepository,
    ) {
    }

    #[Route('api/v1/recipes/match-by-products', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $products = new ArrayCollection($request->get('products') ?? []);
        $page = (int) $request->query->get('page', 1);
        $perPage = (int) $request->query->get('per_page', 12);

        $matcher = new RecipesMatcherComposite(
            new IncompleteRecipesMatcher(3),
        );

        // TODO: Below has to be cached
        $recipes = $this->recipeRepository->all();

        $collection = apply($matcher, [$recipes, $products]);
        $paginator = new MatchingRecipesPaginator($collection, $perPage);

        return $this->respond($paginator->paginate($page));
    }
}
