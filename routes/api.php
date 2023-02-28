<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TopRatingController;
use App\Http\Controllers\BestSellerController;
use App\Http\Controllers\NewProductController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\BestSellerReviewController;
use App\Http\Controllers\NewProductReviewController;
use App\Http\Controllers\RelatedProductController;
use App\Http\Controllers\RelatedProductReviewController;
use App\Http\Controllers\TopRatingReviewController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Admins only routes
// Route::group([
//     'prefix' => 'admin',
//     'middleware' => ['api', 'auth', 'is.admin'],
// ], function () {
//     Route::get('posts', [ProductController::class, 'index']);
// });
###########################################################################################

Route::post('/shopStore', [ShopController::class, 'store']);
Route::get('/shopproducts', [ShopController::class, 'index']);
Route::get('/shopShow/{id}', [ShopController::class, 'show']);

###########################################################################################

Route::post('/topratingStore', [TopRatingController::class, 'store']);
Route::get('/topratingproducts', [TopRatingController::class, 'index']);
Route::get('/topratingShow/{id}', [TopRatingController::class, 'show']);

###########################################################################################

Route::post('/newproductStore', [NewProductController::class, 'store']);
Route::get('/newproductproducts', [NewProductController::class, 'index']);
Route::get('/newproductShow/{id}', [NewProductController::class, 'show']);

###########################################################################################

Route::post('/bestsellerStore', [BestSellerController::class, 'store']);
Route::get('/bestsellerproducts', [BestSellerController::class, 'index']);
Route::get('/bestsellerShow/{id}', [BestSellerController::class, 'show']);

##########################################################################################

Route::post('/relatedproductStore', [RelatedProductController::class, 'store']);
Route::get('/relatedproducts', [RelatedProductController::class, 'index']);
Route::get('/relatedproductShow/{id}', [RelatedProductController::class, 'show']);

##########################################################################################

Route::post('/review', [ReviewController::class, 'store']);
Route::post('/bestsellerreview', [BestSellerReviewController::class, 'store']);
Route::post('/newproductreview', [NewProductReviewController::class, 'store']);
Route::post('/topratingreview', [TopRatingReviewController::class, 'store']);
Route::post('/relatedproductreview', [RelatedProductReviewController::class, 'store']);
