<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Cookery\Products\Domain\Product;
use App\Cookery\Products\Domain\ProductRepository;
use App\Cookery\Recipes\Application\Match\IncompleteRecipesMatcher;
use App\Cookery\Recipes\Application\Match\RecipesMatcherComposite;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\Collection\ArrayCollection;
use App\Shared\Http\Symfony\ApiController;
use Doctrine\Common\Collections\Criteria;

use function Lambdish\Phunctional\apply;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RecipesMatchByProductsGetController extends ApiController
{
    public function __construct(
        private RecipeRepository $recipeRepository,
        private ProductRepository $productRepository
    ) {
    }

    #[Route('api/v1/recipes/match-by-products', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $products = $request->get('products') ?? [];

        $matcher = new RecipesMatcherComposite(
            new IncompleteRecipesMatcher(1),
        );

        $products = $this->productRepository->matching(
            Criteria::create()->where(
                Criteria::expr()->in('name', $products)
            )
        );

        $productNames = new ArrayCollection($products->map(fn (Product $product) => $product->name())->toArray());

        $recipes = $this->recipeRepository->all();
        $collection = apply($matcher, [$recipes, $productNames]);

        return $this->respond($collection->toArray());
    }
}
