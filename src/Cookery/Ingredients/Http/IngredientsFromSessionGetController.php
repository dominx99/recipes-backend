<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class IngredientsFromSessionGetController
{
    #[Route('/api/v1/saved/products', name: 'api_v1_saved_prodcuts', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $products = $request->getSession()->get('products', []) ?? [];

        return new JsonResponse($products);
    }
}
