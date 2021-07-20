<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProjectController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::delete('logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('users')->group(function () {
        Route::get('profile', [UserController::class,'index']);
    });
    Route::prefix('projects')->group(function () {
        Route::get('', [ProjectController::class,'index']);
        Route::get('{id}', [ProjectController::class,'show']);
        Route::post('', [ProjectController::class,'store']);
        Route::put('{id}', [ProjectController::class,'update']);
        Route::delete('{id}', [ProjectController::class,'destroy']);
    });
});