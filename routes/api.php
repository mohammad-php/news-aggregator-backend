<?php

use App\Http\V1\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('v1/articles', ArticleController::class);
