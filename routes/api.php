<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

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


// Listing Routes
Route::get('/productList/{id}',[ProductController::class,'List']);
Route::get('/allproductlist',[ProductController::class,'AllList']);
Route::get('/productdetail/{id}',[ProductController::class,'detail']);
Route::get('/categoryList',[ProductController::class,'categoryList']);
Route::get('/subcatList/{id}',[ProductController::class,'subCatList']);
Route::get('/subcatListItem/{id}',[ProductController::class,'subCatListItems']);
Route::post('/serchproduct',[ProductController::class,'SearchList']);
Route::get('/list',[UserController::class,'list']);







Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    // Login,Registration,Logout
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'registration']);
    Route::post('/verifyotp',[UserController::class,'verifyOtp']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/update',[UserController::class,'update']);
    Route::get('/addresslist/{id}',[UserController::class,'AddressList']);
    // Add Routes
    Route::post('/addcat', [ProductController::class, 'addCategory']);
    Route::post('/addsubcat', [ProductController::class, 'addSubCategory']);
    Route::post('/addproduct', [ProductController::class, 'addProduct']);

    Route::post('/requestforupdate',[UserController::class,'requestForUpdate']);
    Route::get('/requestlist',[UserController::class,'requestList']);

    // Seller's Api
    Route::get('/editproduct/{id}',[ProductController::class,'editProduct']);
    Route::post('/updateproduct',[ProductController::class,'updateProduct']);
    Route::get('/sellerprodlist/{id}',[ProductController::class,'sellerProdList']);
    Route::post('/deleteproduct',[ProductController::class,'deleteProduct']);
    // End of seller's Api

    // Cart Api's
    Route::post('/addtocart',[CartController::class,'addToCart']);
    Route::get('/getcartitems/{id}',[CartController::class,'getCartItems']);
    Route::post('/updatecartitems',[CartController::class,'updateCartItems']);
    Route::post('/deletecartitems',[CartController::class,'deleteCartItems']);
    // End Of cart api's

    // Order Api's
    Route::post('/rozerpayorder',[OrderController::class,'placeOrder']);
    Route::post('/confirm',[OrderController::class,'confirmOrder']);
    Route::get('/orderlist/{id}',[OrderController::class,'orderList']);
    // End Of Order Api's
    // Route::post('login', 'AuthController@login');
    // Route::post('logout', 'AuthController@logout');
    // Route::post('refresh', 'AuthController@refresh');
    // Route::post('me', 'AuthController@me');

});
