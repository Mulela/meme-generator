<?php

use App\Http\Controllers\MemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MemeController::class, 'editor'])->name('editor');
Route::get('/galerie', [MemeController::class, 'gallery'])->name('gallery');

Route::post('/memes', [MemeController::class, 'store'])->name('memes.store');
Route::get('/memes/{meme}/download', [MemeController::class, 'download'])->name('memes.download');
Route::delete('/memes/{meme}', [MemeController::class, 'destroy'])->name('memes.destroy');
