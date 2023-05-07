<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Application\Update;

use Ramsey\Uuid\UuidInterface;

final class RejectPublishRecipeRequestCommand
{
    public function __construct(public readonly UuidInterface $id)
    {
    }
}
