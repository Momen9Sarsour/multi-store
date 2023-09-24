<?php

namespace App\Http\Controllers\Front;

use App\Models\Product;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HomesController extends Controller
{
    //

        //$categories = Category::all();
        //return view('front.homes', compact('categories'));
        public function index() {
            $products = Product::with('category')->active()
            //->latest()
            ->limit(6)
            ->get();
            $productBest = Product::with('category')->active()
            ->quantity()
            ->featured()
            ->limit(8)
            ->get();
            $productFeatured = Product::with('category')->active()
            ->quantity()
            ->featured()
            ->get();
            $highPricedProducts = Product::with('category')
            ->orderByDesc('price')->limit(1)->active()->quantity()->get();
            $storeRated =  Store::leftJoin('orders', 'stores.id', '=', 'orders.store_id')
            ->select(
                'stores.id',
                'stores.name',
                'stores.image',
                DB::raw('COUNT(orders.id) as order_count')
            )
            ->groupBy('stores.id', 'stores.name', 'stores.image')
            ->orderByDesc('order_count')
            ->get();

           // return    $productRated ;
           return view('front.homes', compact('products','productBest','productFeatured','storeRated','highPricedProducts'));
    }
    public function show($slug, Request $request) {
        $category = Category::with('store')->where('slug', $slug)->firstOrFail();

        $query = Category::where('name', $category->name)->with('store');

        if ($request->has('searchName')) {
            $query->whereHas('store', function ($subQuery) use ($request) {
                $subQuery->where('name', 'like', '%' . $request->input('searchName') . '%');
            });
        }

        $categories = $query->paginate(6);
        $searchName = $request->searchName;

        return view('front.categories.show', compact('category', 'categories', 'searchName'));
    }

}
