<?php

use App\Http\Controllers\api\ApiHomeController;
use App\Http\Controllers\api\ApiHomesController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccessTokenController;

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

Route::apiResource('api-products', ProductsController::class);
// Route::prefix('api')->group(function () {
Route::get('first-screen', [ApiHomesController::class, 'index']);
Route::get('categories/{slug}', [ApiHomesController::class, 'show']);
Route::get('homee', [ApiHomeController::class, 'index']);

//API route for register new user
Route::post('/register', [AuthController::class, 'register']);
//API route for login user
Route::post('/login', [AuthController::class, 'login']);
//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });
    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);
});

// });

Route::apiResource('products', ProductsController::class);

Route::post('auth/access-tokens',[AccessTokenController::class,'store'])
->middleware('guest:sanctum');

Route::delete('auth/access-tokens/{token?}', [AccessTokenController::class, 'destroy'])->middleware('auth:sanctum');

