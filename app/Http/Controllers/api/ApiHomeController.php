<?php
// HomeController.php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class ApiHomeController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        $products = Product::with('category')->active()->limit(8)->get();
        $categories = Category::limit(6)->get();

        // return response()->json([
        //     'products' => $products,
        //     'categories' => $categories,
        // ], 200);
        return $this->apiResponse([$products,$categories], 'ok', 200);
    }
}
