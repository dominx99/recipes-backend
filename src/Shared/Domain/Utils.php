<?php

declare(strict_types=1);

namespace App\Shared\Domain;

final class Utils
{
    public static function jsonDecode(string $content, bool $associative = true): ?array
    {
        return json_decode($content, $associative);
    }
}
