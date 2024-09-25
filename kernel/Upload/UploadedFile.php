<?php

namespace App\Kernel\Upload;

class UploadedFile implements UploadedFileInterface
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly string $tmpName,
        public readonly int $error,
        public readonly int $size
    ) {
    }

    public function moveTo(string $path, string $fileName = null): string|false
    {
        $storagePath = APP_PATH . "/storage/$path";

        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        $fileName = $fileName ?? $this->randomFilename();

        $filePath = "$storagePath/$fileName";

        if (move_uploaded_file($this->tmpName, $filePath)) {
            return "$path/$fileName";
        }

        return false;
    }

    private function randomFilename(): string
    {
        return md5(uniqid(rand(), true)) . '.' . $this->getExtensionFile();
    }

    public function getExtensionFile(): string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }
}