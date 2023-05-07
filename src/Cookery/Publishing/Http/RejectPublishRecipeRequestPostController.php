<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Http;

use App\Cookery\Publishing\Application\Update\RejectPublishRecipeRequestCommand;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Http\Symfony\ApiController;
use App\Shared\Http\Symfony\SuccessResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_BACKOFFICE')]
final class RejectPublishRecipeRequestPostController extends ApiController
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    #[Route('/api/v1/publish-recipe-requests/{id}/reject', methods: ['POST'])]
    public function __invoke(string $id): JsonResponse
    {
        $this->messageBus->dispatch(new RejectPublishRecipeRequestCommand(Uuid::fromString($id)));

        return new SuccessResponse();
    }
}
