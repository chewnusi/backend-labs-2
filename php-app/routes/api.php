<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriptionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('subscribers', [SubscriberController::class, 'index']);
Route::get('subscriptions', [SubscriptionController::class, 'index']);  

Route::group(['middleware' => ['keycloak:admin']], function () {
    Route::post('subscribers', [SubscriberController::class, 'store']);
    Route::put('subscribers/{subscriber}', [SubscriberController::class, 'update']); 
    Route::delete('subscribers/{subscriber}', [SubscriberController::class, 'destroy']); 
    Route::post('subscriptions', [SubscriptionController::class, 'store']); 
    Route::put('subscriptions/{subscription}', [SubscriptionController::class, 'update']);
    Route::delete('subscriptions/{subscription}', [SubscriptionController::class, 'destroy']); 
});

Route::group(['middleware' => ['keycloak:user']], function () {
    Route::get('subscribers/{subscriber}', [SubscriberController::class, 'show']); 
    Route::get('subscriptions/{subscription}', [SubscriptionController::class, 'show']); 
});