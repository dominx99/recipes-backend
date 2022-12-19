<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use DateTimeInterface;
use ReflectionClass;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class Utils
{
    public static function jsonDecode(string $content, bool $associative = true): ?array
    {
        return json_decode($content, $associative);
    }

    public static function dateToString(DateTimeInterface $date): string
    {
        return $date->format(DateTimeInterface::ATOM);
    }

    public static function extractClassName(object $object): string
    {
        $reflect = new ReflectionClass($object);

        return $reflect->getShortName();
    }

    public static function toSnakeCase(string $text): string
    {
        return ctype_lower($text) ? $text : strtolower(preg_replace('/([^A-Z\s])([A-Z])/', '$1_$2', $text));
    }

    public static function endsWith(string $needle, string $haystack): bool
    {
        $length = strlen($needle);
        if (0 === $length) {
            return true;
        }

        return substr($haystack, -$length) === $needle;
    }

    public static function toCamelCase(string $text): string
    {
        return lcfirst(str_replace('_', '', ucwords($text, '_')));
    }

    public static function formatViolations(ConstraintViolationListInterface $violations): array
    {
        $errors = [];

        foreach ($violations as $violation) {
            $errors[str_replace(['][', ']', '['], ['.', '', ''], $violation->getPropertyPath())] = $violation->getMessage();
        }

        return $errors;
    }
}
