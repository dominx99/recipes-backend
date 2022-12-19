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
                $this->exceptionHandler->statusCodeFor(get_class($exception))
            )
        );
    }

    private function exceptionMessageFor(Throwable $error): array
    {
        $validationErrorClass = ValidationFailedError::class;

        return $error instanceof $validationErrorClass
            ? ['errors' => $error->errors()]
            : ['message' => $error->getMessage()];
    }

    private function exceptionCodeFor(Throwable $error): string
    {
        $domainErrorClass = DomainError::class;

        return $error instanceof $domainErrorClass
            ? $error->errorCode()
            : Utils::toSnakeCase(Utils::extractClassName($error));
    }
}
