<?php

namespace App\Kernel\Storage;

use App\Kernel\Config\ConfigInterface;

class Storage implements StorageInterface
{
    public function __construct(private ConfigInterface $config)
    {
    }

    public function get(string $path): string
    {
        return file_get_contents( APP_PATH . "/storage/$path");
    }

    public function url(string $path): string
    {
        $url = $this->config->get('app.url');

        return "$url/books/storage/$path";
    }
}