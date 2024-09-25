<?php

namespace App\Kernel\Database;

use App\Kernel\Config\ConfigInterface;
use App\Kernel\Services\RedisService;

class Database implements DatabaseInterface
{
    private \PDO $pdo;
    private int $cacheTTL = 600;

    public function __construct(
        private readonly ConfigInterface $config,
        private readonly RedisService $redis,
    )
    {
        $this->connect();
    }

    private function connect(): void
    {
        $driver = $this->config->get('database.driver');
        $port = $this->config->get('database.port');
        $host = $this->config->get('database.host');
        $dbname = $this->config->get('database.dbname');
        $user = $this->config->get('database.user');
        $password = $this->config->get('database.password');

        try {
            $this->pdo = new \PDO("$driver:host=$host;port=$port;dbname=$dbname", $user, $password);
        } catch (\PDOException $exception) {
            exit("Connection failed: " . $exception->getMessage());
        }
    }
    public function insert(string $table, array $data): int
    {
        $columns = implode(',', array_keys($data));
        $binds = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($binds)";

        $statement = $this->pdo->prepare($sql);

        try {
            $statement->execute($data);
        } catch (\PDOException $exception) {
            exit("Insert failed: " . $exception->getMessage());
        }

        if($this->redis->isConnected) {
            $this->redis->delete("$table:all");
        }

        return $this->pdo->lastInsertId();
    }

    public function all(string $table): array
    {
        $cacheKey = $this->getCacheKey($table);
        if($this->redis->isConnected) {
            if ($cachedData = $this->redis->get($cacheKey)) {
                return json_decode($cachedData, true);
            }
        }

        $sql = "SELECT * FROM $table";

        $statement = $this->pdo->query($sql);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if($this->redis->isConnected) {
            $this->redis->set($cacheKey, json_encode($data), $this->cacheTTL);
        }

        return $data;
    }

    public function first(string $table, array $condition = []): array
    {
        $cacheKey = $this->getCacheKey($table, $condition);

        if ($this->redis->isConnected) {
            if ($cachedData = $this->redis->get($cacheKey)) {
                return json_decode($cachedData, true);
            }
        }

        $whereClause = [];
        foreach ($condition as $key => $value) {
            $whereClause[] = "$key = :$key";
        }

        $sql = "SELECT * FROM $table WHERE " . implode(' AND ', $whereClause);

        $statement = $this->pdo->prepare($sql);

        try {
            $statement->execute($condition);
            $data = $statement->fetch(\PDO::FETCH_ASSOC);

            if($this->redis->isConnected) {
                $this->redis->set($cacheKey, json_encode($data), $this->cacheTTL);
            }
        } catch (\PDOException $exception) {
            exit("Find failed: " . $exception->getMessage());
        }

        return $data;
    }

    public function update(string $table, array $data, array $condition = []): void
    {
        $setClause = [];
        foreach ($data as $key => $value) {
            $setClause[] = "$key = :$key";
        }

        $whereClause = [];
        foreach ($condition as $key => $value) {
            $whereClause[] = "$key = :$key";
        }


        $sql = "UPDATE $table SET " . implode(', ', $setClause) . " WHERE " . implode(' AND ', $whereClause);

        $statement = $this->pdo->prepare($sql);

        try {
            $statement->execute($data + $condition);

            if ($this->redis->isConnected) {
                $this->redis->delete($this->getCacheKey($table, $condition));
            }
        } catch (\PDOException $exception) {
            exit("Update failed: " . $exception->getMessage());
        }
    }

    public function delete(string $table, array $condition = []): void
    {
        $whereClause = [];
        foreach ($condition as $key => $value) {
            $whereClause[] = "$key = :$key";
        }

        $sql = "DELETE FROM $table WHERE " . implode(' AND ', $whereClause);

        $statement = $this->pdo->prepare($sql);

        try {
            $statement->execute($condition);

            if ($this->redis->isConnected) {
                $this->redis->delete($this->getCacheKey($table, $condition));
            }
        } catch (\PDOException $exception) {
            exit("Delete failed: " . $exception->getMessage());
        }
    }

    private function getCacheKey(string $table, array $condition = []): string
    {
        if (empty($condition)) {
            return "db_cache:$table:all";
        }
        return "db_cache:$table:" . md5(json_encode($condition));
    }
}