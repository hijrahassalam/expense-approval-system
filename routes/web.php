<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/api/login', [AuthController::class, 'login']);

Route::get('/{any?}', function () {
    return view('spa');
})->where('any', '^(?!api).*$');
