<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SentimentalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomLoginController;


Route::get('/', function () {
    return view('auth.custom-login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/analyse', [SentimentalController::class,'show'])->name('analyse.show');
    Route::post('/analyse/submit', [SentimentalController::class,'analyse'])->name('analyse.submit');
    Route::post('/analyse/file', [SentimentalController::class,'uploadFile'])->name('analyse.file');
    Route::get('/history', [SentimentalController::class, 'history'])->name('history');
    Route::get('/download-file', [SentimentalController::class, 'downloadFile'])->name('download.file');
});

require __DIR__.'/auth.php';
