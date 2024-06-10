<?php

use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
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

Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('menus', MenuController::class);
    Route::apiResource('orders', OrderController::class)->except(['create', 'edit']);
    Route::post('payments', [PaymentController::class, 'store']);
    Route::get('users', [UserController::class,'getAllUsers']);
    
    Route::post('orders', [OrderController::class, 'placeOrder']);
    Route::get('orders', [OrderController::class, 'getAllOrders']);
    Route::get('orders/{id}', [OrderController::class, 'getOrder']);
    Route::put('orders/{id}', [OrderController::class, 'updateOrder']);
    Route::delete('orders/{id}', [OrderController::class, 'deleteOrder']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
