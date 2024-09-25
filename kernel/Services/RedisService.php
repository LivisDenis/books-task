<?php

namespace App\Kernel\Services;

use App\Kernel\Config\ConfigInterface;
use Predis\Client;
use Predis\Connection\ConnectionException;

class RedisService
{
    protected Client $client;
    public bool $isConnected = false;

    public function __construct(
        private readonly ConfigInterface $config
    )
    {
        try {
            $this->client = new Client([
                'scheme' => 'tcp',
                'host' => $this->config->get('redis.host'),
                'port' => $this->config->get('redis.port'),
            ]);
            $this->client->ping();
            $this->isConnected = $this->client->isConnected();
        } catch (ConnectionException $e) {
            error_log("Redis connection failed: " . $e->getMessage());
        }
    }

    public function set(string $key, string $value, int $ttl = null): void
    {
        if ($this->client->isConnected()) {
            if ($ttl) {
                $this->client->setex($key, $ttl, $value);
            } else {
                $this->client->set($key, $value);
            }
        } else {
            error_log("Failed to set key in Redis: Connection is not established.");
        }
    }

    public function get(string $key): ?string
    {
        if ($this->client->isConnected()) {
            return $this->client->get($key);
        }

        error_log("Failed to get key from Redis: Connection is not established.");
        return null;
    }

    public function delete(string $key): void
    {
        if ($this->client->isConnected()) {
            $this->client->del([$key]);
        } else {
            error_log("Failed to delete key in Redis: Connection is not established.");
        }
    }
}
