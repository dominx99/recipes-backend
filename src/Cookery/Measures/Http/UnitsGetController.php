<?php

declare(strict_types=1);

namespace App\Cookery\Measures\Http;

use App\Shared\Domain\Enum\Unit;
use App\Shared\Http\Symfony\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UnitsGetController extends ApiController
{
    #[Route('/api/v1/units', name: 'api_v1_units_get', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->respond(array_map(fn (Unit $unit) => $unit->value, Unit::cases()));
    }
}
