<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\MarketController;
use App\Http\Controllers\Api\MeetupController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// Public marketplace
Route::get('market', [MarketController::class, 'index']);
Route::get('market/{listing}', [MarketController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('listings', ListingController::class);

    Route::apiResource('conversations', ConversationController::class)
        ->only(['index', 'store', 'show', 'destroy']);

    Route::get('conversations/{conversation}/messages', [MessageController::class, 'index']);
    Route::post('conversations/{conversation}/messages', [MessageController::class, 'store']);

    Route::get('conversations/{conversation}/meetups', [MeetupController::class, 'index']);
    Route::post('conversations/{conversation}/meetups', [MeetupController::class, 'store']);
    Route::patch('meetups/{meetup}', [MeetupController::class, 'update']);

    Route::get('profile', [ProfileController::class, 'show']);
    Route::match(['put', 'patch'], 'profile', [ProfileController::class, 'update']);
    Route::post('profile/password', [ProfileController::class, 'changePassword']);
    Route::delete('profile', [ProfileController::class, 'destroy']);
});
