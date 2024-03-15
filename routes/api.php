<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('products', [ProductController::class, 'all']);
Route::get('categories', [ProductCategoryController::class, 'all']);
Route::get('transactions', [TransactionController::class, 'all']);

Route::post('registrasi', [UserController::class, 'registrasi']);
Route::post('login', [UserController::class, 'login']);

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('profile', [UserController::class, 'fetch']);
    Route::put('update-profile', [UserController::class, 'updateProfile']);
    Route::post('logout', [UserController::class, 'logout']);
});
