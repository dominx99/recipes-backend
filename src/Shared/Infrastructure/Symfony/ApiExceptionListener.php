<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use App\Shared\Domain\DomainError;
use App\Shared\Domain\Utils;
use App\Shared\Domain\ValidationFailedError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

final class ApiExceptionListener
{
    public function __construct(private ApiExceptionsHttpStatusCodeMapping $exceptionHandler)
    {
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $event->setResponse(
            new JsonResponse(
                array_merge(
                    [
                        'code' => $this->exceptionCodeFor($exception),
                    ],
                    $this->exceptionMessageFor($exception)
                ),
                $this->exceptionHandler->statusCodeFor($exception)
            )
        );
    }

    /**
     * @return array<string,array>|array<string,string>
     */
    private function exceptionMessageFor(Throwable $error): array
    {
        return $this->isValidationError($error)
            ? ['errors' => $this->getValidationError($error)->errors()]
            : ['message' => (string) $error];
    }

    private function exceptionCodeFor(Throwable $error): string
    {
        $domainErrorClass = DomainError::class;

        return $error instanceof $domainErrorClass
            ? $error->errorCode()
            : Utils::toSnakeCase(Utils::extractClassName($error));
    }

    private function isValidationError(Throwable $error): bool
    {
        return $error instanceof ValidationFailedError
            || $error->getPrevious() instanceof ValidationFailedError;
    }

    private function getValidationError(Throwable $error): ValidationFailedError
    {
        return $error instanceof ValidationFailedError
            ? $error
            : $error->getPrevious();
    }
}
