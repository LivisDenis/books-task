<?php

namespace App\Kernel\Upload;

interface UploadedFileInterface
{
    public function moveTo(string $path, string $fileName = null): string|false;

    public function getExtensionFile(): string;
}