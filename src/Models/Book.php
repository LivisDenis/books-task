<?php

namespace App\Models;

class Book
{
    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly string $author,
        private readonly string $image,
        private readonly string $createdAt,
        private readonly string $updatedAt,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function author(): string
    {
        return $this->author;
    }

    public function image(): string
    {
        return $this->image;
    }

    public function createdAt(): string
    {
        return $this->createdAt;
    }

    public function updatedAt(): string
    {
        return $this->updatedAt;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['title'],
            $data['author'],
            $data['image'],
            $data['created_at'],
            $data['updated_at']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'image' => $this->image,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}