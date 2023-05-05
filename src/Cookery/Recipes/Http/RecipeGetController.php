<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http;

use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class RecipeGetController extends ApiController
{
    public function __construct(private readonly RecipeRepository $repository)
    {
    }

    #[Route('/api/v1/recipes/{id}', priority: 1, methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $recipe = $this->repository->findOne(Uuid::fromString($id));

        if (!$recipe instanceof Recipe) {
            throw new NotFoundHttpException('Recipe not found');
        }

        return $this->respond($recipe);
    }
}
