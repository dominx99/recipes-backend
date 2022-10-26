<?php

declare(strict_types=1);

namespace App\Cookery\Tags\Http;

use App\Cookery\Tags\Domain\TagRepository;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AllMainTagsGetController extends ApiController
{
    public function __construct(private TagRepository $repository)
    {
    }

    #[Route('/api/v1/main-tags', name: 'api.v1.main-tags', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->respond(
            $this->repository->mainTags()->toArray(),
        );
    }
}
