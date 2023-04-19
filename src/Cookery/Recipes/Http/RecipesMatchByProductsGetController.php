<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Cookery\Products\Domain\ProductRepository;
use App\Cookery\Recipes\Application\Match\CompleteRecipesMatcher;
use App\Cookery\Recipes\Application\Match\RecipesMatcherComposite;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Cookery\Recipes\Infrastructure\Paginator\MatchingRecipesPaginator;
use App\Shared\Domain\Collection\ArrayCollection;
use App\Shared\Http\Symfony\ApiController;
use Closure;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\CompositeExpression;

use function Lambdish\Phunctional\apply;

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
        $perPage = (int) $request->query->get('perPage', 12);
        $lastId = $request->query->get('lastId', null);

        $matcher = new RecipesMatcherComposite(
            new CompleteRecipesMatcher(),
        );

        $expressions = [];

        foreach ($products as $product) {
            $expressions[] = Criteria::expr()->contains('i.name', $product);
        }

        $compositeExpression = new CompositeExpression(
            CompositeExpression::TYPE_OR,
            $expressions
        );

        $criteria = new Criteria($compositeExpression);

        $recipes = $this->recipeRepository->matchByIngredients($criteria);

        $collection = apply($matcher, [$recipes, $products]);

        $nextPageUrlCallback = fn (string $nextId) => $this->urlGenerator->generate('api_v1_recipes_match_by_products', [
            'products' => $products->toArray(),
            'perPage' => $perPage,
            'lastId' => $nextId,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $nextPageUrlCallback = Closure::fromCallable($nextPageUrlCallback);

        $paginator = new MatchingRecipesPaginator($collection, $nextPageUrlCallback, $perPage, $lastId);

        return $this->respond($paginator->paginate());
    }
}
