<?php

declare(strict_types=1);

namespace App\Auth\Http;

use App\Auth\Application\Create\UserCreator;
use App\Auth\Domain\User;
use App\Shared\Http\Symfony\ApiController;
use App\Shared\Http\Symfony\SuccessResponse;

use function Lambdish\Phunctional\apply;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserPostController extends ApiController
{
    public function __construct(private UserCreator $creator, private ValidatorInterface $validator)
    {
    }

    #[Route('api/v1/register', methods: ['POST'])]
    #[ParamConverter('user', converter: 'fos_rest.request_body', options: [
        'validator' => [
            'groups' => ['POST'],
        ],
    ])]
    public function __invoke(User $user, ConstraintViolationListInterface $violations): JsonResponse
    {
        // TODO: Remove second validaiton, it's neccessary because param converter does not work for UniqueEntity
        $violations = $violations->count() <= 0
            ? $this->validator->validate($user)
            : $violations;

        $violations->count() > 0
            ? $this->throwValidationFailedError($violations)
            : apply($this->creator, [$user]);

        return new SuccessResponse();
    }
}
