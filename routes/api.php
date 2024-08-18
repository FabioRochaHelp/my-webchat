<?php

use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('guest')->get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['token' => csrf_token()]);
});

Route::group([], function () {
    Route::get('/users', [UserController::class, 'index'])->name('api.users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('api.users.show');

    Route::get('/messages/{user}', [MessageController::class, 'listMessages'])->name('api.message.list');
    Route::post('/messages/store', [MessageController::class, 'store'])->name('api.message.store');
});
