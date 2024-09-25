<?php

namespace App\Kernel\Session;

interface SessionInterface
{
    public function get(string $key);
    public function getFlash(string $key);
    public function has(string $key);
    public function set(string $key, $value);
    public function remove(string $key);
    public function destroy();
}