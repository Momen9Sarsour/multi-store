<?php

use App\Http\Controllers\api\ApiHomeController;
use App\Http\Controllers\api\ApiHomesController;
use App\Http\Controllers\Api\ProductsController;
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
Route::apiResource('api-products', ProductsController::class);
Route::prefix('api')->group(function () {
    Route::get('products', [ApiHomesController::class, 'index']); // جلب المنتجات
    Route::get('categories/{slug}', [ApiHomesController::class, 'show']); // جلب تفاصيل الفئة بناءً على الاسم الفرعي
    Route::get('homee', [ApiHomeController::class, 'index']);
});
