<?php

use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(UserController::class)->group(function () {
    // Register
    Route::post('/register', 'register');

    // Login
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(MessageController::class)->group(function () {
        Route::get('/users', 'getUsers');

        Route::post('/send-message', 'sendMessage');

        Route::get('/messages/{id}', 'getMessages');
    });

});
