<?php

namespace App\Controllers;

use App\Kernel\Controller\Controller;
use App\Kernel\Database\DatabaseInterface;
use App\Services\BookService;

class BooksController extends Controller
{

    protected BookService $bookService;

    public function __construct(DatabaseInterface $database)
    {
        parent::__construct($database);
        $this->bookService = new BookService($this->db());
    }

    public function index()
    {
        $books = $this->bookService->all();

//        header('Content-Type: application/json');
//        echo json_encode($books);

        $this->view('home', [
            'books' => $books
        ]);
    }

    public function store(): void
    {
        $validation = $this->request()->validate([
            'title' => ['required', 'min:3'],
            'author' => ['required', 'min:3'],
        ]);

        if (!$validation) {
            foreach ($this->request()->errors() as $field => $error) {
                $this->session()->set($field, $error);
            }

            $this->redirect('/books/home');
        }

        $this->bookService->createBook(
            $this->request()->input('title'),
            $this->request()->input('author'),
            $this->request()->file('image'),
        );

        $this->redirect('/books/home');
    }

    public function edit(): void
    {
        $id = $this->request()->input('id');
        $book = $this->bookService->findFirst($id);

        $this->view('edit', [
            'book' => $book
        ]);
    }

    public function update(): void
    {
        $validation = $this->request()->validate([
            'title' => ['required', 'min:3'],
            'author' => ['required', 'min:3'],
        ]);

        $id = $this->request()->input('id');

        if (!$validation) {
            foreach ($this->request()->errors() as $field => $error) {
                $this->session()->set($field, $error);
            }
            $this->redirect("/books/home/update?id=$id");
        }

        $this->bookService->updateBook(
            $id,
            $this->request()->input('title'),
            $this->request()->input('author'),
            $this->request()->file('image'),
        );

        $this->redirect('/books/home');
    }

    public function destroy(): void
    {
        $id = $this->request()->input('id');
        $this->bookService->deleteBook($id);

        $this->redirect('/books/home');
    }
}