<?php

namespace App\Kernel\Storage;

interface StorageInterface
{
    public function get(string $path): string;

    public function url(string $path): string;
}