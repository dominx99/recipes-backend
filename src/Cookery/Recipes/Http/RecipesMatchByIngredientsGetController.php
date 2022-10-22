<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\CompleteRecipesMatcher;
use App\Cookery\Ingredients\Domain\IngredientRepository;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Http\Symfony\ApiController;

use function Lambdish\Phunctional\apply;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RecipesMatchByIngredientsGetController extends ApiController
{
    public function __construct(
        private CompleteRecipesMatcher $matcher,
        private RecipeRepository $recipeRepository,
        private IngredientRepository $ingredientRepository
    ) {
    }

    #[Route('api/v1/recipes/match-by-ingredients', methods: ['GET'])]
    public function __invoke(): Response
    {
        $ingredients = $this->ingredientRepository->matching();
        $recipes = $this->recipeRepository->all();

        $recipes = apply($this->matcher, [$recipes, $ingredients]);

        return $this->respond($recipes->getValues());
    }
}
