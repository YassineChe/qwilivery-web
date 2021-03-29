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

############ Login Route #############
Route::POST("/login", [AuthController::class, "login"]);
//* Reset password
Route::POST('/reset/password', [AuthController::class, 'createRestLink']);
Route::POST('/reset-password', [AuthController::class, 'restPassword']);

########### Admin Controller #########
Route::middleware("auth:admin")->group(function () {
    //* Delivery Man stuff
    Route::post('/approved/delivery-man', [AdminController::class, 'approvedDeliveryMan']); //approuve delivery man
    Route::delete('/delete/delivery-man', [AdminController::class, 'deleteDeliveryMan']); //delete delivery man
    Route::put('/block/delivery-man', [AdminController::class, 'blockDeliveryMan']); //block delivery man
    //* Restaurant stuff
    Route::post('/add/restaurant', [AdminController::class, 'addRestaurant']); //add restaurant

});

########### CommonController #########
Route::middleware("auth:admin")->group(function () {
    Route::get('/fetch/authenticated/guard', [CommonController::class, 'fetchAuthenticatedGuard']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
