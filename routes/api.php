<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DeliveryController;

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

######### API Routes Admin  #########
Route::middleware("auth:admin")->group(function () {
    //* Delivery Man stuff
    Route::post('/approved/delivery-man', [AdminController::class, 'approvedDeliveryMan']); //approuve delivery man
    Route::delete('/delete/delivery-man', [AdminController::class, 'deleteDeliveryMan']); //delete delivery man
    Route::patch('/block/delivery-man', [AdminController::class, 'blockDeliveryMan']); //block delivery man
    Route::get('/fetch/deliveries', [AdminController::class, 'fetchDeliveries']); //  Fetch delivery men no blocked.
    Route::get('/fetch/deliveries/blocked', [AdminController::class, 'fetchDeliveriesBlocked']); //  Fetch delivery men blocked.
    //* Restaurant stuff
    Route::get('/fetch/restaurants', [AdminController::class, 'fetchRestaurants']); // Fetch Restaurants
    Route::post('/add/restaurant', [AdminController::class, 'addRestaurant']); //Add restaurant
    Route::delete('/delete/restaurant', [RestaurantController::class, 'deleteRestaurant']); // Delete Restaurant
    Route::patch('/block/restaurant', [RestaurantController::class, 'blockRestaurant']); // block Restaurant
    Route::patch('/unblock/restaurant', [RestaurantController::class, 'unblockRestaurant']); // Unblock Restaurant
    Route::put('/edit/restaurant', [RestaurantController::class, 'editRestaurant']);
});

######### API Routes Delivery  #########
Route::middleware("auth:delivery")->group(
    function () {
        Route::put('/edit/delivery/password', [DeliveryController::class, "updatePassword"]);
    }
);

########### CommonController #########
Route::middleware("auth:admin,delivery")->group(function () {
    Route::get('/fetch/authenticated/guard', [CommonController::class, 'fetchAuthenticatedGuard']);
});
