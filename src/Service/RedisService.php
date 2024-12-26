<?php

namespace App\Service;

use Redis;

class RedisService
{
    private $redis;

    public function __construct()
    {
        $this->redis = new Redis();
        // $this->redis->connect('redis', 6379); // Nom du service Docker
    }

    public function set(string $key, string $value, int $ttl = 3600): void
    {
        $this->redis->set($key, $value, $ttl);
    }

    public function get(string $key): ?string
    {
        $value = $this->redis->get($key);
        return $value !== false ? $value : null;
    }

    public function delete(string $key): void
    {
        $this->redis->del($key);
    }
}
