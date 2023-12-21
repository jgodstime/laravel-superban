<?php

namespace LaravelSuperBan\SuperBan;

use Illuminate\Contracts\Cache\Factory;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Str;

class SuperBan
{
    protected $cache;

    protected $throttlePrefix = 'superban:throttle:';

    protected $banPrefix = 'superban:ban:';

    public function __construct(Factory $cache)
    {
        $this->cache = $cache;
    }

    public function check(string $route, ?string $identifier = null, ?int $throttle = null, ?int $banDuration = null): bool
    {
        $identifier = $identifier ?? request()->ip();
        $key = $this->getThrottleKey($route, $identifier);

        if ($this->isBanned($identifier)) {
            return true;
        }

        $count = $this->getCache()->get($key);

        if (! $count) {
            $this->getCache()->put($key, 1, $this->getThrottleDuration($throttle));

            return false;
        }

        if ($count >= $this->getThrottleValue($throttle)) {
            $this->ban($identifier, $banDuration ?? config('superban.ban_duration'));

            return true;
        }

        $this->getCache()->increment($key);

        return false;
    }

    public function isBanned(string $identifier): bool
    {
        return $this->getCache()->has($this->getBanKey($identifier));
    }

    protected function ban(string $identifier, int $duration): void
    {
        $this->getCache()->put($this->getBanKey($identifier), true, $duration);
    }

    public function unban(string $identifier): void
    {
        $this->getCache()->forget($this->getBanKey($identifier));
    }

    protected function getThrottleKey(string $route, string $identifier): string
    {
        return $this->throttlePrefix.Str::slug($route).':'.$identifier;
    }

    protected function getBanKey(string $identifier): string
    {
        return $this->banPrefix.$identifier;
    }

    protected function getThrottleDuration(?int $throttle = null): int
    {
        return $throttle ?? config('superban.default_throttle.1', 60);
    }

    protected function getThrottleValue(?int $throttle = null): int
    {
        return $throttle ?? config('superban.default_throttle.0', 60);
    }

    protected function getCache(): Factory
    {
        return $this->cache;
    }
}
