<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Http;

use App\Cookery\Publishing\Domain\PublishRecipeRequestRepository;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PublishRecipeRequestsGetController extends ApiController
{
    public function __construct(private readonly PublishRecipeRequestRepository $repository)
    {
    }

    #[Route('/api/v1/publish-recipe-requests', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->respond($this->repository->all()->toArray());
    }
}
