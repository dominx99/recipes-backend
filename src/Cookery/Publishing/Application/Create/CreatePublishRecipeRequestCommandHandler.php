<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Application\Create;

use App\Cookery\Publishing\Domain\PublishRecipeRequest;
use App\Cookery\Publishing\Domain\PublishRecipeRequestRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
final class CreatePublishRecipeRequestCommandHandler
{
    public function __construct(private readonly PublishRecipeRequestRepository $repository)
    {
    }

    public function __invoke(CreatePublishRecipeRequestCommand $command): void
    {
        $this->repository->save(new PublishRecipeRequest(
            $command->id,
            $command->ownerId,
            $command->recipeId,
        ));
    }
}
