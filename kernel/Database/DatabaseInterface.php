<?php

namespace App\Kernel\Database;

interface DatabaseInterface
{
    public function all(string $table): array;
    public function insert(string $table, array $data): int;
    public function first(string $table, array $condition = []): array;

    public function update(string $table, array $data, array $condition = []): void;

    public function delete(string $table, array $condition = []): void;
}