<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
   return file_get_contents(public_path('index.html'));
});

Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
