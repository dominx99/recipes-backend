<?php

declare(strict_types=1);

namespace App\Cookery\MyRecipes\Http;

use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Http\Symfony\ApiController;
use DomainException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class MyRecipesGetController extends ApiController
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly RecipeRepository $recipeRepository
    ) {
    }

    #[Route('/api/v1/my-recipes', name: 'api_v1_my_recipes')]
    public function __invoke(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $offset = $page > 1 ? ($page - 1) * 24 : 0;
        $limit = 24;
        $user = $this->tokenStorage->getToken()?->getUser();
        $hasNextPage = false;

        if (!$user) {
            throw new DomainException('User not found');
        }

        $recipes = $this->recipeRepository->matchByOwner($user, $offset, $limit + 1);

        if ($recipes->count() > $limit) {
            $hasNextPage = true;
            $recipes = $recipes->pop();
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
