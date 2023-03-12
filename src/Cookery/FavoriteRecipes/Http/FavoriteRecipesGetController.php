<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Http;

use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipeRepository;
use App\Shared\Http\Symfony\ApiController;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class FavoriteRecipesGetController extends ApiController
{
    public function __construct(
        private readonly FavoriteRecipeRepository $repository,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    #[Route('/api/v1/favorite-recipes', name: 'api_v1_favorite_recipes', methods: ['GET'])]
    public function __invoke(): Response
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('userId', $user));

        return $this->respond($this->repository->matching($criteria)->toArray());
    }
}
