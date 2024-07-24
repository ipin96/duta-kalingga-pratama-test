<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\RegisterController;

Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', LogoutController::class)->name('logout');
    Route::get('/material', [MaterialController::class, 'index']);
    Route::post('material-upload', [MaterialController::class, 'store']);
    Route::post('material-verified', [MaterialController::class, 'verify']);
});

