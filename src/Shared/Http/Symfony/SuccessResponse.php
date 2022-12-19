<?php

declare(strict_types=1);

namespace App\Shared\Http\Symfony;

use Symfony\Component\HttpFoundation\JsonResponse;

final class SuccessResponse extends JsonResponse
{
    public function __construct(int $statusCode = JsonResponse::HTTP_OK)
    {
        parent::__construct(['status' => 'OK'], $statusCode);
    }
}
