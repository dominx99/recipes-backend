<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Http;

use App\Cookery\Publishing\Application\Update\AcceptPublishRecipeRequestCommand;
use App\Shared\Http\Symfony\ApiController;
use App\Shared\Http\Symfony\SuccessResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_BACKOFFICE')]
final class AcceptPublishRecipeRequestPostController extends ApiController
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    #[Route('/api/v1/publish-recipe-request/{recipeId}/accept', name: 'api_v1_publish_recipe_request_accept', methods: ['POST'])]
    public function __invoke(string $recipeId): JsonResponse
    {
        $this->messageBus->dispatch(new AcceptPublishRecipeRequestCommand($recipeId));

        return new SuccessResponse();
    }
}
