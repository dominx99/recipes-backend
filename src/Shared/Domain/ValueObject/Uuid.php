<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Ramsey\Uuid\UuidInterface;
use Stringable;

class Uuid extends RamseyUuid implements Stringable
{
    public static function fromString(string $id): UuidInterface
    {
        self::ensureIsValidUuid($id);

        return parent::fromString($id);
    }

    public static function random(): UuidInterface
    {
        return parent::uuid4();
    }

    private static function ensureIsValidUuid(string $id): void
    {
        if (!parent::isValid($id)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $id));
        }
    }
}
