<?php

use App\Controllers\BooksController;
use App\Controllers\MovieController;
use App\Kernel\Router\Route;

return [
    Route::get('/books/home', [BooksController::class, 'index']),
    Route::post('/books/home', [BooksController::class, 'store']),
    Route::get('/books/home/update', [BooksController::class, 'edit']),
    Route::post('/books/home/update', [BooksController::class, 'update']),
    Route::post('/books/home/destroy', [BooksController::class, 'destroy']),
];
