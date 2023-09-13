<?php

namespace App\Http\Controllers\Front;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    //
    public function index(Request $request){
        // $products = Product::where('status', 'active')->paginate(9);
        // $query = Product::query();
        // // name filter
        // if ($request->has('search')) {
        //     $query->where('name', 'like', '%' . $request->input('search') . '%');
        // }

        // $product = $query->where('status', 'active')->paginate(9);
        // $search = $request->search;
        // return view('front.products.index', compact('product','search'));
        $query = Product::where('status', 'active');

        //  name filter
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $product = $query->paginate(9);
        $search = $request->search;

        return view('front.products.index', compact('product', 'search'));
    }

    public function show_product($id)
    {
        try{
            $category = Product::findorfail($id);
            }catch(Exception $e){
                return redirect()->route('adminCategory.index')
                ->with('info','Record not found');
            }
            $category=product::findOrFail($id);
            $products = $category->products()->with('store')->paginate(44);

        return view('front.products.show-product', compact('product'));
    }

    public function show(Product $product)
    {

        if ($product->status !== 'active' || $product->storgeQuantity <= 0) {
            abort(404);
        }

        return view('front.products.show', compact('product'));
    }
}
