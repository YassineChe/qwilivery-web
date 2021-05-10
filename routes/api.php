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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;

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

//? Auth api routes
Route::post('/login/delivery', [AuthController::class, 'deliveryLogin']);
Route::post("/login", [AuthController::class, "login"]);
Route::post('/reset/password', [AuthController::class, 'createRestLink']);
Route::post('/reset-password', [AuthController::class, 'restPassword']);
Route::post('/register/delivery', [AuthController::class, 'regiterDelivery']);

//? Admin api routes
Route::middleware("auth:admin")->group(function () {
    //* Delivery Man stuff
    Route::post('/approved/delivery-man', [DeliveryController::class, 'approvedDeliveryMan']); //approuve delivery man
    Route::delete('/delete/delivery-man', [DeliveryController::class, 'deleteDeliveryMan']); //delete delivery man
    Route::patch('/block/delivery-man', [DeliveryController::class, 'blockDeliveryMan']); //block delivery man
    Route::get('/fetch/deliveries', [DeliveryController::class, 'fetchDeliveries']); //  Fetch delivery men no blocked.
    Route::get('/fetch/deliveries/restricted', [DeliveryController::class, 'fetchRestrictedDeliveries']); // Deliveries with more info (whos out service..)
    Route::patch('/unblock/delivery-man', [DeliveryController::class, 'unblockDeliveryMan']); // Unblock DeliveryMan
    Route::post('/add/delivery-man', [DeliveryController::class, 'addDeliveryMan']); //Add DeliveryMan
    Route::post('/edit/delivery-man', [DeliveryController::class, 'editDeliveryMan']); //Edit DeliveryMan
    Route::put('/add/delivery/to/order', [DeliveryController::class, 'addDeliveryToOrder']);

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
    Route::get('/fetch/count/reports/unseen', [StatisticController::class, 'countReportsUnseen']); // How much delivered menu

    //! Delete itttttttt
    Route::get('/fetch/restaurant/and/deliveries', [AdminController::class, 'fetchRestaurantsAndDeliveries']);
    
    //* Historic stuff
    Route::get('/fetch/historic', [OrderController::class, 'fetchHistoric']); //List of Historic orders
    
    //* Porfile stuff
    Route::post('/edit/admin/profile', [AdminController::class, 'editProfile']); // Edit profile
    Route::put('/edit/admin/security', [AdminController::class, 'editPassword']); // Edit password 

    //* Reports stuff
    Route::get('/fetch/reports', [ReportController::class, 'fetchReports']); // Fetch report list
    Route::delete('/delete/report/{report_id}', [ReportController::class, 'deleteReport']); // Delete report

    //* Chat stuff
    Route::post('/admin/send/message', [ChatController::class, 'sendMsgFromMsger']); //Message sent by admin from messenger
    Route::post('/admin/send/msg/out/msger', [ChatController::class, 'sendMsgOutMsger']); // Messsage sent by admin out messenger
    Route::get('/fetch/chatflow/delivery/{delivery_id}', [ChatController::class, 'fetchChatFlowByDeliveryID']); // Fetching chatflow by delivery id
});

//? Delivery api routes
Route::middleware("auth:delivery")->group(function () {
    Route::put('/edit/delivery/security', [DeliveryController::class, "editPassword"]); // Update password
    Route::post('/edit/delivery/profile', [DeliveryController::class, "editProfile"]); // update info of delivery man
    Route::get('/fetch/last/five/missions', [OrderController::class, 'fetchLastFiveMissions']); // Delivery last five missions
    Route::get('/fetch/delivery/historic', [OrderController::class, 'fetchDeliveryHistory']); // Fetch delivery historic
    Route::get('/fetch/delivered/by/delivery', [StatisticController::class, 'countDelivedForDelivery']); // Statistic for delivery boy
    Route::get('/download/file', [DeliveryController::class, "downloadFile"]); // Download permit
    //Used in mobile and web
    Route::get('/fetch/orders/to/deliver', [OrderController::class, 'orderToDeliver']); // Fetch order to deliver
    Route::post('/apply/order', [OrderController::class, 'applyOrder']);
    Route::get('/fetch/inprogress/orders', [OrderController::class, 'fetchInprogressOrders']);
    Route::get('/fetch/delivered/orders', [OrderController::class, 'fetchDeliveredOrder']);
    Route::post('/delivered/order', [OrderController::class, 'deliveredOrder']); // This will set order as delivred
    //* Chat stuff
    Route::post('/delivery/send/message', [ChatController::class, 'sendMsgFromMsgerDelivery']);

});

//? Restaurant api routes
Route::middleware("auth:restaurant")->group(function () {
    //* Category & Variant Stuff
    Route::get('/fetch/categories', [MenuController::class, 'fetchCategories']);      // Fetch menu
    Route::post('/add/category', [MenuController::class, 'addCategory']); // Add category
    Route::post('/add/variant', [MenuController::class, 'addVariant']);  // Add variant
    Route::get('/fetch/variants', [MenuController::class, 'fetchVariants']); // Fetch menu
    Route::put('/edit/category', [MenuController::class, 'editCategory']); // Update  category
    Route::post('/edit/variant', [MenuController::class, 'editVariant']);  // Update variant
    Route::delete('/delete/variant/{variant_id}', [MenuController::class, 'deleteVariant']);  // Delete variant
    Route::delete('/delete/category/{category_id}', [MenuController::class, 'deleteCategory']); // Delete category
    Route::get('/fetch/last/teen/missions', [OrderController::class, 'fetchLastTeenMissions']); // Delivery last five missions
    //* Order Stuff
    Route::get('/fetch/preorders', [OrderController::class, 'fetchPreOrders']);
    Route::post('/add/order', [OrderController::class, 'addOrder']);
    //* Porfile stuff
    Route::post('/edit/restaurant/profile', [RestaurantController::class, 'editProfile']); // Edit profile
    Route::put('/edit/restaurant/security', [RestaurantController::class, 'editPassword']); // Edit password 
    //Statistics
    Route::get('/fetch/count/restaurant/categories', [StatisticController::class, 'countCategoriesByRestaurant']);
    Route::get('/fetch/count/restaurant/preorder', [StatisticController::class, 'countPreOrdersByRestaurant']);
    Route::get('/fetch/count/restaurant/delivered', [StatisticController::class, 'countDelivredByRestaurant']);
});

//? Common api routes between (Restaurant, Admin)
Route::middleware('auth:restaurant,admin')->group(function(){
    Route::delete('/delete/order/{order_id}', [OrderController::class, 'deleteOrder']); // Delete order by restraunt will leave tracebility for admin
});

//? Common api routes between all
Route::middleware("auth:admin,delivery,restaurant")->group(function () {
    Route::get('/fetch/authenticated/guard', [CommonController::class, 'fetchAuthenticatedGuard']); // Fetch authenticated guard
    Route::get('/fetch/orders/{pre_order_id}', [OrderController::class, 'fetchOrderByPreOrderID']); // Fetch Order by PreOrder ID
    Route::post('/add/report', [ReportController::class, 'addReport']); // Add new report

    //* Chat stuff
    Route::get('/fetch/conversations', [ConversationController::class, 'fetchConversations']);
    Route::get('/fetch/chatflow/{conversation_id}', [ConversationController::class, 'fetchChatflowAdminCnvId']);
    Route::patch('/mark/as/read', [ChatController::class, 'markAllAsRead']);

    Route::post('/broadcasting/auth', function(Request $request){
        return \Broadcast::auth($request);
    });

});