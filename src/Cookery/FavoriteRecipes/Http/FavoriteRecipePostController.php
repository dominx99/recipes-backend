<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Http;

use App\Cookery\FavoriteRecipes\Application\Create\AddRecipeToFavoritesCommand;
use App\Cookery\FavoriteRecipes\Domain\FavoriteRecipe;
use App\Shared\Http\Symfony\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class FavoriteRecipePostController extends ApiController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/api/v1/favorite-recipes', name: 'api_v1_favorite_recipe_post', methods: ['POST'])]
    #[ParamConverter('favorite_recipe', converter: 'fos_rest.request_body', options: [
        'validator' => [
            'groups' => ['POST'],
        ],
    ])]
    public function __invoke(FavoriteRecipe $favoriteRecipe): void
    {
        $violations = $this->validator->validate($favoriteRecipe);

        $violations->count() > 0
            ? $this->throwValidationFailedError($violations)
            : $this->messageBus->dispatch(new AddRecipeToFavoritesCommand($favoriteRecipe))
        ;
    }
}
