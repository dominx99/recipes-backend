<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use App\Shared\Domain\ValidationFailedError;
use InvalidArgumentException;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use function Lambdish\Phunctional\get;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class ApiExceptionsHttpStatusCodeMapping
{
    private const DEFAULT_STATUS_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;

    private array $exceptions = [
        ValidationFailedError::class => Response::HTTP_BAD_REQUEST,
        InvalidArgumentException::class => Response::HTTP_BAD_REQUEST,
        NotFoundHttpException::class => Response::HTTP_NOT_FOUND,
    ];

    public function register(string $exceptionClass, int $statusCode): void
    {
        $this->exceptions[$exceptionClass] = $statusCode;
    }

    public function statusCodeFor(Throwable $exception): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        $exceptionClass = get_class($exception);
        $statusCode = get($exception, $this->exceptions, self::DEFAULT_STATUS_CODE);

        if (null === $statusCode) {
            throw new InvalidArgumentException("There are no status code mapping for <$exceptionClass>");
        }

        return $statusCode;
    }
}
