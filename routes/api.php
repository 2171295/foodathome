<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\Order_ItemsController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Login logout routes
Route::post('login', [LoginController::class, 'login']);
Route::middleware('auth:api')->post('logout', [LoginController::class, 'logout']);



Route::post('users', [UserController::class, 'store']);
Route::get('users/emailavailable', [UserController::class, 'emailAvailable']);
Route::put('users/{user}/logout', [UserController::class, 'logout']);
Route::get('users/logged_users', [UserController::class, 'usersLogged']);
Route::get('users/with_trash', [UserController::class, 'indexWithTrash']);
Route::get('users/available_cookers', [UserController::class, 'availableCookers']);
Route::get('users/available_deliveryman', [UserController::class, 'availableDeliveryman']);
Route::get('users/available_managers', [UserController::class, 'availableManagers']);


Route::middleware(['auth:api'])->group(function () {
//Rotas para os users
    Route::get('users/me', [UserController::class, 'myProfile']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);
    Route::post('users/{user}/update_photo', [UserController::class, 'updatePhoto']);
    Route::put('users/{user}/logged', [UserController::class, 'loggedAt']);
    Route::put('users/{user}/block', [UserController::class, 'block']);
    Route::put('users/{user}/unblock', [UserController::class, 'unblock']);
    Route::put('users/{user}/confirm_password', [UserController::class, 'confirmPassword']);
    Route::put('users/{user}/update_password', [UserController::class, 'updatePassword']);
    Route::put('users/{user}/not_available', [UserController::class, 'notAvailable']);
    Route::put('users/{user}/available', [UserController::class, 'available']);


});


//Rotas para os customers
Route::get('customers', [CustomerController::class, 'index']);
Route::get('customers/{customer}', [CustomerController::class, 'show']);
Route::get('customers/{customer}/orders', [CustomerController::class, 'getOrders']);
Route::post('customers', [CustomerController::class, 'store']);
Route::put('customers/{customer}', [CustomerController::class, 'update']);
Route::delete('customers/{customer}', [CustomerController::class, 'destroy']);

//Rotas para os products
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);
Route::post('products', [ProductController::class, 'store']);
Route::put('products/{product}', [ProductController::class, 'update']);
Route::delete('products/{product}', [ProductController::class, 'destroy']);
Route::post('products/{product}/update_photo', [ProductController::class, 'updatePhoto']);
Route::get('products/{product}/orders', [ProductController::class, 'getOrders']);

//Rotas para as orders
Route::get('orders', [OrderController::class, 'index']);
Route::get('orders/open',[OrderController::class,'openOrders']);
Route::get('orders/ready',[OrderController::class,'ordersReady']);
Route::get('orders/holding', [OrderController::class, 'holdingOrders']);
Route::get('orders/{order}', [OrderController::class, 'show']);
Route::get('orders/{order}/products', [OrderController::class, 'getProducts']);
Route::post('orders', [OrderController::class, 'store']);
Route::put('orders/{order}', [OrderController::class, 'update']);
Route::delete('orders/{order}', [OrderController::class, 'destroy']);
Route::put('orders/{order}/cook',[OrderController::class,'defineCooker']);
Route::put('orders/{order}/cooked',[OrderController::class,'orderCooked']);
Route::put('orders/{order}/delivery_man',[OrderController::class,'defineDeliveryMan']);
Route::put('orders/{order}/delivered',[OrderController::class,'orderDelivered']);
Route::put('orders/{order}/canceled',[OrderController::class,'cancelOrder']);
Route::get('orders/{order}/products', [OrderController::class, 'getProducts']);
Route::get('orders/preparedby/{user}', [OrderController::class, 'preparedBy']);
Route::get('orders/deliveredby/{user}', [OrderController::class, 'deliveredBy']);


//Rotas para as Order_Items
Route::get('orders_items', [Order_ItemsController::class, 'index']);
Route::get('orders_items/{orders_items}', [Order_ItemsController::class, 'show']);
Route::post('orders_items', [Order_ItemsController::class, 'store']);
Route::put('orders_items/{orders_items}', [Order_ItemsController::class, 'update']);
Route::delete('orders_items/{orders_items}', [Order_ItemsController::class, 'destroy']);
Route::get('orders_items/order/{id}', [Order_ItemsController::class, 'orderItems']);
Route::get('orders_items/product/{id}', [Order_ItemsController::class, 'itemOrders']);
