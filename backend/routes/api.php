<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorSessionController;

Route::post('/visitor/session', [VisitorSessionController::class, 'handle']);

