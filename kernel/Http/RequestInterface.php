<?php

namespace App\Kernel\Http;

use App\Kernel\Upload\UploadedFileInterface;
use App\Kernel\Validator\Validator;

interface RequestInterface
{
    public function getUri(): string;
    public function getMethod(): string;
    public function input(string $key, $default = null);
    public function file(string $key): ?UploadedFileInterface;
    public function setValidator(Validator $validator);
    public function validate(array $rules);
    public function errors();
}