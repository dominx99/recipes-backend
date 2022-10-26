<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Cookery\Ingredients\Domain\IngredientRepository;
use App\Cookery\Recipes\Application\Match\IncompleteRecipesMatcher;
use App\Cookery\Recipes\Application\Match\RecipesMatcherComposite;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Http\Symfony\ApiController;

use function Lambdish\Phunctional\apply;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RecipesMatchByIngredientsGetController extends ApiController
{
    public function __construct(
        private RecipeRepository $recipeRepository,
        private IngredientRepository $ingredientRepository
    ) {
    }

    #[Route('api/v1/recipes/match-by-ingredients', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $ingredients = $request->get('ingredients') ?? [];

        $matcher = new RecipesMatcherComposite(
            new IncompleteRecipesMatcher(1),
        );

        $ingredients = $this->ingredientRepository->matching($ingredients);
        $recipes = $this->recipeRepository->all();

        $collection = apply($matcher, [$recipes, $ingredients]);

        return $this->respond($collection->toArray());
    }
}
