<?php


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HelloController;
use App\Http\Controllers\Api\OrganisationController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;



Route::get('hello', [HelloController::class, 'index']);
Route::post('auth/register',[AuthController::class, 'register']);
Route::post('auth/login',[AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::get('organisations', [OrganisationController::class, 'index']);
    Route::get('organisations/{orgId}', [OrganisationController::class, 'show']);
    Route::post('organisations', [OrganisationController::class, 'store']);
    Route::post('organisations/{orgId}/users', [OrganisationController::class, 'addUser']);
});
