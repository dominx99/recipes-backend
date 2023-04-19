<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Type;

use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

final class UuidType extends Type
{
    public function getName(): string
    {
        return 'uuid_string';
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL([
            'length' => '36',
            'fixed' => true,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UuidInterface
    {
        if ($value instanceof Uuid || null === $value) {
            return $value;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException(sprintf('Expected string, got %s', gettype($value)));
        }

        try {
            return $this->fromString($value);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(sprintf('Invalid UUID string: %s', $e->getMessage()), 0, $e);
        }
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof UuidInterface) {
            return $value->toString();
        }

        if (null === $value || '' === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException(sprintf('Expected string, got %s', gettype($value)));
        }

        try {
            return $this->fromString($value)->toString();
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(sprintf('Invalid UUID string: %s', $e->getMessage()), 0, $e);
        }
    }

    public function fromString(string $value): UuidInterface
    {
        return Uuid::fromString($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
