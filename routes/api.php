<?php

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\CartController;

use App\Http\Controllers\ProductController;

use App\Http\Controllers\ReviewController;

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

Route::middleware('auth')->post('/reviews', [ReviewController::class, 'store']);

//Route::post('/products', [ProductController::class, 'store']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);


##########################################################################################
Route::post('/review', [ReviewController::class, 'store']);

###########################################################################################
Route::post('/add/{id}', [CartController::class, 'insert']);
Route::get('/cart', [CartController::class, 'index']);
Route::get('/delete/{id}', [CartController::class, 'delete']);
#########################################################################################
Route::group([
    'prefix' => 'seller',
         'middleware' => ['api', 'auth', 'is.seller'],
     ], function () {
        Route::post('/products', [ProductController::class, 'store']);
    });
