<?php

namespace App\Kernel\Http;

class Redirect implements RedirectInterface
{
    public function to(string $url): void
    {
        header("Location: $url");
        exit;
    }
}