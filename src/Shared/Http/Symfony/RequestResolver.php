<?php

declare(strict_types=1);

namespace App\Shared\Http\Symfony;

use App\Shared\Domain\RequestInterface;
use App\Shared\Domain\Utils;
use App\Shared\Domain\ValidationFailedError;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RequestResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $reflection = new ReflectionClass($argument->getType());

        return $reflection->implementsInterface(RequestInterface::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var RequestInterface $customRequest */
        $customRequest = new ($argument->getType());

        $customRequest->populate($request);

        $violations = $this->validator->validate(
            $customRequest->params(),
            $customRequest->rules(),
        );

        if ($violations->count() > 0) {
            throw new ValidationFailedError(Utils::formatViolations($violations));
        }

        yield $customRequest;
    }
}
