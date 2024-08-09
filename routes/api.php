<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::post('/news/search-by-symbol', [NewsController::class, 'searchBySymbol']);
