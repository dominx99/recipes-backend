<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Auth\Domain\User;
use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipe;
use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipeRepository;
use App\Cookery\Recipes\Domain\MatchingRecipeCollection;
use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Cookery\Recipes\Domain\ValueObject\MatchingRecipe;
use App\Shared\Http\Symfony\ApiController;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class RecipesMatchByFavoritesGetController extends ApiController
{
    public function __construct(
        private readonly FavoriteRecipeRepository $favoriteRecipeRepository,
        private readonly RecipeRepository $recipeRepository,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[Route('/api/v1/recipes/match-by-favorites', name: 'api_v1_recipes_match_by_favorites', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $page = (int) $request->query->get('page', 1) ?? 1;
        $offset = ($page * 24) - 24;
        $limit = 24;

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('userId', $user->getId()))
            ->setFirstResult($offset)
            ->setMaxResults($limit + 1)
        ;

        $favoriteRecipes = $this->favoriteRecipeRepository->matching($criteria);

        $recipeIds = $favoriteRecipes->map(
            fn (FavoriteRecipe $favoriteRecipe) => $favoriteRecipe->recipeId(),
        )->toArray();

        $recipes = $this->recipeRepository->findMany($recipeIds);

        $matchingRecipes = new MatchingRecipeCollection($recipes->map(
            fn (Recipe $recipe) => new MatchingRecipe($recipe, $recipe->componentsCount())
        )->toArray());

        return $this->respond([
            'data' => $matchingRecipes->pop()->toArray(),
            'meta' => [
                'page' => $page,
                'has_next_page' => $matchingRecipes->count() > $limit,
            ],
        ]);
    }
}
