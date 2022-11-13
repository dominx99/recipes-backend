<?php

declare(strict_types=1);

namespace App\Cookery\Products\Http;

use App\Cookery\ProductCategories\Domain\ProductCategoryRepository;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CategoriesWithProductsGetController extends ApiController
{
    public function __construct(private ProductCategoryRepository $repository)
    {
    }

    #[Route('/api/v1/categories-with-products', name: 'api.v1.categories_with_products', methods: ['GET'])]
    public function __invoke(): Response
    {
        $categories = $this->repository->all();

        return $this->respond($categories->toArray());
    }
}
