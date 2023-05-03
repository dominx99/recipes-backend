<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use Symfony\Component\HttpFoundation\Request;

interface RequestInterface
{
    public function rules(): array;

    public function params(): array;

    public function populate(Request $request): void;
}
