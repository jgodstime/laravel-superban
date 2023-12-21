<?php
namespace LaravelSuperBan\SuperBan\CacheDrivers;

use Illuminate\Database\Capsule\Manager;
use LaravelSuperBan\SuperBan\Contracts\CacheStore;

class DatabaseCacheStore implements CacheStore
{
    protected $db;
    protected $table;

    public function __construct(Manager $db, string $table = 'superban_cache')
    {
        $this->db = $db;
        $this->table = $table;
    }

    public function has(string $key): bool
    {
        return $this->db->table($this->table)->where('key', $key)->exists();
    }

    public function get(string $key): mixed
    {
        return $this->db->table($this->table)->where('key', $key)->first()->value;
    }

    public function put(string $key, mixed $value, int $ttl = null): void
    {
        if ($ttl) {
            $this->db->table($this->table)->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'expires_at' => now()->addMinutes($ttl)]
            );
        } else {
            $this->db->table($this->table)->updateOrInsert(['key' => $key], ['value' => $value]);
        }
    }

    public function increment(string $key): int
    {
        return $this->db->table($this->table)->where('key', $key)->increment('value');
    }

    public function forget(string $key): void
    {
        $this->db->table($this->table)->where('key', $key)->delete();
    }
}
