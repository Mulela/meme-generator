<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'editor')->name('editor');
Route::view('/gallery', 'gallery')->name('gallery');
