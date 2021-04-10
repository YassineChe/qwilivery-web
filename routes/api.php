<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\MenuController;

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
    Route::patch('/unblock/delivery-man', [AdminController::class, 'unblockDeliveryMan']); // Unblock DeliveryMan
    Route::post('/add/delivery-man', [AdminController::class, 'addDeliveryMan']); //Add DeliveryMan
    Route::post('/edit/delivery-man', [AdminController::class, 'editDeliveryMan']); //Edit DeliveryMan

    // Route::get('/fetch/deliveries/blocked', [AdminController::class, 'fetchDeliveriesBlocked']); //  Fetch delivery men blocked.
    //* Restaurant stuff
    Route::get('/fetch/restaurants', [RestaurantController::class, 'fetchRestaurants']); // Fetch Restaurants
    Route::post('/add/restaurant', [RestaurantController::class, 'addRestaurant']); //Add restaurant
    Route::delete('/delete/restaurant', [RestaurantController::class, 'deleteRestaurant']); // Delete Restaurant
    Route::patch('/block/restaurant', [RestaurantController::class, 'blockRestaurant']); // block Restaurant
    Route::patch('/unblock/restaurant', [RestaurantController::class, 'unblockRestaurant']); // Unblock Restaurant
    Route::put('/edit/restaurant', [RestaurantController::class, 'editRestaurant']);
});

######### API Routes Delivery  #########
Route::middleware("auth:delivery")->group(
    function () {
        Route::put('/edit/delivery/password', [DeliveryController::class, "updatePassword"]); // Update password
        Route::get('/download/file', [DeliveryController::class, "downloadFile"]); // Download permit
        Route::post('/update/delivery', [DeliveryController::class, "updateDelivery"]); // update info of delivery man
    }
);
######### API Routes Restaurant  #########
Route::middleware("auth:restaurant")->group(
    function () {
        Route::get('/fetch/restaurant/categories',         [MenuController::class, 'fetchCategories']);      // Fetch menu
        Route::post('/add/restaurant/category',      [MenuController::class, 'createCategory']); // Create category
        Route::post('/add/restaurant/variant',       [MenuController::class, 'createVariant']);  // Create variant
        Route::get('/fetch/restaurant/variants',         [MenuController::class, 'fetchVariants']);      // Fetch menu
      
        Route::put('/update/restaurant/category',    [MenuController::class, 'updateCategory']); // Update  category
        Route::put('/update/restaurant/variant',     [MenuController::class, 'updateVariant']);  // Update variant
        Route::delete('/delete/restaurant/variant',  [MenuController::class, 'deleteVariant']);  // Delete variant
        Route::delete('/delete/restaurant/category', [MenuController::class, 'deleteCategory']); // Delete category

    }
);

########### CommonController #########
Route::middleware("auth:admin,delivery,restaurant")->group(function () {
    Route::get('/fetch/authenticated/guard', [CommonController::class, 'fetchAuthenticatedGuard']);
});
