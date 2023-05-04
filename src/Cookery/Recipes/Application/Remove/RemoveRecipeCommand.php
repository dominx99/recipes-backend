<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Remove;

use Ramsey\Uuid\UuidInterface;

final class RemoveRecipeCommand
{
    public function __construct(public readonly UuidInterface $id)
    {
    }
}
