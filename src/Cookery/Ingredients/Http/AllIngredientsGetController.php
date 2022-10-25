<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Http;

use App\Cookery\Ingredients\Domain\IngredientRepository;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AllIngredientsGetController extends ApiController
{
    public function __construct(private IngredientRepository $repository)
    {
    }

    #[Route('api/v1/ingredients', name: 'ingredients.all', methods: ['GET'])]
    public function __invoke(): Response
    {
        $ingredients = $this->repository->all();

        return $this->respond($ingredients->toArray());
    }
}
