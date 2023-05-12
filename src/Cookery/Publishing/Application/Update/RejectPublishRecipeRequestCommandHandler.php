<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Application\Update;

use App\Cookery\Publishing\Domain\PublishRecipeRequestRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class RejectPublishRecipeRequestCommandHandler
{
    public function __construct(private readonly PublishRecipeRequestRepository $repository)
    {
    }

    public function __invoke(RejectPublishRecipeRequestCommand $command): void
    {
        $request = $this->repository->findOneBy(['recipeId' => $command->recipeId]);

        $this->repository->remove($request);
    }
}
