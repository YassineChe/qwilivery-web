<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\AdminController;
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

############ Auth Route #############
Route::POST("/login", [AuthController::class, "login"]);
//* Reset password
Route::POST('/reset/password', [AuthController::class, 'createRestLink']);
Route::POST('/reset-password', [AuthController::class, 'restPassword']);
//* register new user
Route::POST('/register/delivery', [AuthController::class, 'create']); //create new Delivery

########### Admin Controller #########
Route::middleware("auth:admin")->group(function () {
    //* Delivery Man stuff
    Route::post('/approved/delivery-man', [AdminController::class, 'approvedDeliveryMan']); //approuve delivery man
    Route::delete('/delete/delivery-man', [AdminController::class, 'deleteDeliveryMan']); //delete delivery man
    Route::patch('/block/delivery-man', [AdminController::class, 'blockDeliveryMan']); //block delivery man
    Route::get('/fetch/deliveries', [AdminController::class, 'fetchDeliveries']); //  Fetch delivery men no blocked.
    Route::get('/fetch/deliveries/blocked', [AdminController::class, 'fetchDeliveriesBlocked']); //  Fetch delivery men blocked.
    //* Restaurant stuff
    Route::get('/fetch/restaurants', [AdminController::class, 'fetchRestaurants']);
    Route::post('/add/restaurant', [AdminController::class, 'addRestaurant']); //Add restaurant
});

########### Admin Controller #########

########### CommonController #########
Route::middleware("auth:admin")->group(function () {
    Route::get('/fetch/authenticated/guard', [CommonController::class, 'fetchAuthenticatedGuard']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
