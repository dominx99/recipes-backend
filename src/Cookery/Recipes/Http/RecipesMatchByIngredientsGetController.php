<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Cookery\Ingredients\Domain\IngredientRepository;
use App\Cookery\Recipes\Application\Match\CompleteRecipesMatcher;
use App\Cookery\Recipes\Application\Match\IncompleteRecipesMatcher;
use App\Cookery\Recipes\Application\Match\RecipesMatcherComposite;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Http\Symfony\ApiController;

use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\map;

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
    public function __invoke(): Response
    {
        $matcher = new RecipesMatcherComposite(
            new CompleteRecipesMatcher(),
            new IncompleteRecipesMatcher(3),
        );

        $ingredients = $this->ingredientRepository->matching();
        $recipes = $this->recipeRepository->all();

        $book = apply($matcher, [$recipes, $ingredients]);

        $formattedResult = array_combine(
            $book->keys(),
            map(fn (RecipeCollection $recipes) => $recipes->getValues(), $book->values()),
        );

        return $this->respond($formattedResult);
    }
}
