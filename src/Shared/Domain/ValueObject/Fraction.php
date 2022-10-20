<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Doctrine\Common\Cache\Psr6\InvalidArgument;
use InvalidArgumentException;

final class Fraction
{
    public function __construct(private string $fraction)
    {
    }

    public function toNumber(): string
    {
        $values = explode(' ', $this->fraction);

        if (count($values) === 1) {
            return $this->fractionToNumber($this->fraction);
        }

        $total = 0;

        foreach ($values as $value) {
            $total += $this->fractionToNumber($value);
        }

        return (string) $total;
    }

    private function fractionToNumber(string $fraction): string
    {
        $parts = explode('/', $fraction);

        if (count($parts) > 2) {
            throw new InvalidArgumentException('It is not a fraction');
        }

        if (count($parts) == 1) {
            return $fraction;
        }

        [$meter, $denominator] = $parts;

        return (string) round($meter / $denominator, 2);
    }
}
