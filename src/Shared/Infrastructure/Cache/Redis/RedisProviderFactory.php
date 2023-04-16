<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Cache\Redis;

use Redis;

final class RedisProviderFactory
{
    private const CONNECTION_TIMEOUT = 1;

    public static function create(
        string $host,
        int $port,
        int $databaseNumber,
        string $prefix
    ): Redis {
        $redis = new Redis();
        $redis->connect($host, $port, self::CONNECTION_TIMEOUT);
        $redis->select($databaseNumber);

        return $redis;
    }
}
