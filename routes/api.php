<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SentimentalController;

Route::post('/delete-history', [SentimentalController::class, 'deleteOldHistory']);