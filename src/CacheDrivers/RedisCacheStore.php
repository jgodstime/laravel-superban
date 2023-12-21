<?php

namespace LaravelSuperBan\SuperBan\CacheDrivers;

use Illuminate\Support\Facades\Redis;
use LaravelSuperBan\SuperBan\Contracts\CacheStore;

class RedisCacheStore implements CacheStore
{
    protected $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function has(string $key): bool
    {
        return $this->redis->exists($key);
    }

    public function get(string $key): mixed
    {
        return $this->redis->get($key);
    }

    public function put(string $key, mixed $value, ?int $ttl = null): void
    {
        $this->redis->set($key, $value, $ttl ?? 0);
    }

    public function increment(string $key): int
    {
        return $this->redis->incr($key);
    }

    public function forget(string $key): void
    {
        $this->redis->del($key);
    }
}
