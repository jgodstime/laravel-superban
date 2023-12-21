<?php
namespace LaravelSuperBan\SuperBan\Contracts;

interface CacheStore
{
    public function has(string $key): bool;

    public function get(string $key): mixed;

    public function put(string $key, mixed $value, int $ttl = null): void;

    public function increment(string $key): int;

    public function forget(string $key): void;
}
