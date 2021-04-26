<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StatisticController;

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
Route::post('/login/delivery', [AuthController::class, 'deliveryLogin']); // used on mobile
Route::post("/login", [AuthController::class, "login"]);
//* Reset password
Route::post('/reset/password', [AuthController::class, 'createRestLink']);
Route::post('/reset-password', [AuthController::class, 'restPassword']);
//* register new user
Route::post('/register/delivery', [AuthController::class, 'regiterDelivery']); //create new Delivery

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

    //* Restaurant stuff
    Route::get('/fetch/restaurants', [RestaurantController::class, 'fetchRestaurants']); // Fetch Restaurants
    Route::post('/add/restaurant', [RestaurantController::class, 'addRestaurant']); //Add restaurant
    Route::delete('/delete/restaurant', [RestaurantController::class, 'deleteRestaurant']); // Delete Restaurant
    Route::patch('/block/restaurant', [RestaurantController::class, 'blockRestaurant']); // block Restaurant
    Route::patch('/unblock/restaurant', [RestaurantController::class, 'unblockRestaurant']); // Unblock Restaurant
    Route::put('/edit/restaurant', [RestaurantController::class, 'editRestaurant']); // Edit Restaurant

    //* Statistics stuff
    Route::get('/fetch/count/deliveries', [StatisticController::class, 'countDeliveries']); //How much delivery men
    Route::get('/fetch/count/restaurants', [StatisticController::class, 'countRestaurants']); // How much restaurant
    Route::get('/fetch/count/delivered', [StatisticController::class, 'countDelivered']); // How much delivered menu
});

######### API Routes Delivery  #########
Route::middleware("auth:delivery")->group(
    function () {
        Route::put('/edit/delivery/password', [DeliveryController::class, "updatePassword"]); // Update password
        Route::get('/download/file', [DeliveryController::class, "downloadFile"]); // Download permit
        Route::post('/update/delivery', [DeliveryController::class, "updateDelivery"]); // update info of delivery man
        Route::get('/fetch/orders/to/deliver', [OrderController::class, 'orderToDeliver']); // Fetch order to deliver
    }
);

######### API Routes Restaurant  #########
Route::middleware("auth:restaurant")->group(
    function () {
        //Category & Variant Stuff
        Route::get('/fetch/categories', [MenuController::class, 'fetchCategories']);      // Fetch menu
        Route::post('/add/category', [MenuController::class, 'addCategory']); // Add category
        Route::post('/add/variant', [MenuController::class, 'addVariant']);  // Add variant
        Route::get('/fetch/variants', [MenuController::class, 'fetchVariants']); // Fetch menu
        Route::put('/edit/category', [MenuController::class, 'editCategory']); // Update  category
        Route::post('/edit/variant', [MenuController::class, 'editVariant']);  // Update variant
        Route::delete('/delete/variant/{variant_id}', [MenuController::class, 'deleteVariant']);  // Delete variant
        Route::delete('/delete/category/{category_id}', [MenuController::class, 'deleteCategory']); // Delete category
        // Order Stuff
        Route::get('/fetch/preorders', [OrderController::class, 'fetchPreOrders']);
        Route::get('/fetch/orders/{pre_order_id}', [OrderController::class, 'fetchOrderByPreOrderID']); // Fetch Order by PreOrder ID
        Route::post('/add/order', [OrderController::class, 'addOrder']);
        Route::delete('/delete/order/{order_id}', [OrderController::class, 'deleteOrderByRestaurant']); // Delete order by restraunt will leave tracebility for admin
    }
);

########### CommonController #########
Route::middleware("auth:admin,delivery,restaurant")->group(function () {
    Route::get('/fetch/authenticated/guard', [CommonController::class, 'fetchAuthenticatedGuard']);
});
