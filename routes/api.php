<?php


use App\Http\Controllers\Api\HelloController;
use Illuminate\Support\Facades\Route;


Route::get('hello', [HelloController::class, 'index']);
