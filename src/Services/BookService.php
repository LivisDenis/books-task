<?php

namespace App\Services;

use App\Kernel\Database\DatabaseInterface;
use App\Kernel\Upload\UploadedFileInterface;
use App\Models\Book;

class BookService
{
    public function __construct(private DatabaseInterface $db)
    {}

    public function all(): array
    {
        $booksData = $this->db->all('books');

        $books = [];

        foreach ($booksData as $data) {
            $books[] = Book::fromArray($data);
        }

        return $books;
    }

    public function createBook(string $title, string $author, UploadedFileInterface $image): int
    {
        $filePath = $image->moveTo('books');

        return $this->db->insert('books', [
            'title' => $title,
            'author' => $author,
            'image' => $filePath,
        ]);
    }

    public function findFirst(int $id): Book
    {
        $book = $this->db->first('books', [
            'id' => $id,
        ]);

        return Book::fromArray($book);
    }

    public function updateBook(int $id, string $title, string $author, ?UploadedFileInterface $image): void
    {
        $filePath = $image?->moveTo('books');

        $dataToUpdate = [
            'title' => $title,
            'author' => $author,
        ];

        if ($filePath) {
            $dataToUpdate['image'] = $filePath;
        }

        $this->db->update('books', $dataToUpdate, ['id' => $id]);
    }

    public function deleteBook(string $id): void
    {
        $this->db->delete('books', ['id' => $id]);
    }
}