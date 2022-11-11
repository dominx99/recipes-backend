<?php

declare(strict_types=1);

namespace App\Cookery\Products\Http;

use App\Cookery\Products\Domain\ProductRepository;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AllMainProductsGetController extends ApiController
{
    public function __construct(private ProductRepository $repository)
    {
    }

    #[Route('/api/v1/main-products', name: 'api.v1.main-products', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->respond(
            $this->repository->mainProducts()->toArray(),
        );
    }
}
