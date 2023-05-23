<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UploadController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users/register', [UserController::class, 'register']);
Route::post('/users/signin', [UserController::class, 'signin']);
Route::post('/users/signout', [UserController::class, 'signout'])->middleware('auth:sanctum');
Route::get('/users/top-sellers', [UserController::class, 'topSeller']);
Route::get('/users/user/{id}', [UserController::class, 'getUser'])->middleware('auth:sanctum');
Route::get('/users', [UserController::class, 'getUsers'])->middleware('auth:sanctum');
Route::get('/users/getuserById/{id}', [UserController::class, 'getUserById'])->middleware('auth:sanctum');
Route::put('/users/update', [UserController::class, 'updateUser'])->middleware('auth:sanctum');
Route::delete('/users/delete/{id}', [UserController::class, 'deleteUser'])->middleware('auth:sanctum');
Route::put('/users/update-profile', [UserController::class, 'updateProfile'])->middleware('auth:sanctum');
Route::get('/seller/{id}', [SellerController::class, 'getSeller']);
Route::get('/seller/data/{id}', [SellerController::class, 'getSellerData']);
Route::put('/seller/update', [SellerController::class, 'updateSeller'])->middleware('auth:sanctum');
Route::put('/users/user/update/{id}', [UserController::class, 'updateUserByAdmin'])->middleware('auth:sanctum');

Route::post('/products', [ProductController::class, 'getProducts']);
Route::get('/products/categories', [ProductController::class, 'getCategories']);
Route::get('/products/product/{id}', [ProductController::class, 'getProduct']);
Route::post('/products/product/review', [ReviewController::class, 'createReview'])->middleware('auth:sanctum');
Route::get('/products/product/reviews/{id}', [ReviewController::class, 'getReviews']);
Route::post('/products/create', [ProductController::class, 'createProduct'])->middleware('auth:sanctum');
Route::put('/products/update', [ProductController::class, 'updateProduct'])->middleware('auth:sanctum');
Route::delete('/products/delete/{id}', [ProductController::class, 'deleteProduct'])->middleware('auth:sanctum');
Route::post('/products/getProducts', [ProductController::class, 'getProductList'])->middleware('auth:sanctum');

Route::post('/orders/create', [OrderController::class, 'createOrder'])->middleware('auth:sanctum');
Route::get('/orders/order/{id}', [OrderController::class, 'getOrder'])->middleware('auth:sanctum');
Route::get('/orders/orderItems/{id}', [OrderController::class, 'getOrderItems'])->middleware('auth:sanctum');
Route::post('/orders/getOrders', [OrderController::class, 'getOrderList'])->middleware('auth:sanctum');
Route::get('/orders/mine/{id}', [OrderController::class, 'getOrderHistory'])->middleware('auth:sanctum');
Route::delete('/orders/delete/{id}', [OrderController::class, 'deleteOrder'])->middleware('auth:sanctum');
Route::put('/orders/pay/{id}', [OrderController::class, 'payOrder'])->middleware('auth:sanctum');
Route::put('/orders/deliver/{id}', [OrderController::class, 'deliverOrder'])->middleware('auth:sanctum');

Route::post('/uploads', [UploadController::class, 'upload'])->middleware('auth:sanctum');