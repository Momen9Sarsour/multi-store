<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $products = Product::filter($request->query())
        ->with('category:id,name','store:id,name','tags:id,name')
        ->paginate();
        $responseData = [
            'products' => ProductResource::collection($products),
        ];
   
        return response()->json($responseData);
    /*$formatProducts = [];

    foreach ($products as $product) {
        $formatProducts[] = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'category' => $product->category->name,
            'store' => $product->store->name,
            'tags' => $product->tags->pluck('name')->toArray(),
        ];
    }
    
    return response()->json($formatProducts);*/
    /*$formattedProducts = [];

    foreach ($products as $product) {
        $formattedProducts[] = (object)[
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'category' => $product->category->name,
            'store' => $product->store->name,
            'tags' => $product->tags->pluck('name')->toArray(),
        ];
    }

   return response()->json($formattedProducts);*/


     
           
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
         'name'=>'required|string|max:255',
         'description'=>'nullable|string|max:255',
         'category_id'=>'required|exists:categories,id',
         'store_id'=>'required|exists:stores,id',
         'status'=>'in:active,inactive',
         'price'=>'required|numeric|min:0',
         'compare_price'=>'nullable|numeric|gt:price'
         
        ]);
        $product= Product::create($request->all());
        return response()->json($product,201,[
            'Location'=>route('products.show',$product->id),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
        //
        return $product
        ->load('category:id,name','store:id,name','tags:id,name');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        $request->validate([
            'name'=>'sometimes|required|string|max:255',
            'description'=>'nullable|string|max:255',
            'category_id'=>'sometimes|required|exists:categories,id',
            'store_id'=>'sometimes|required|exists:stores,id',
            'status'=>'in:active,inactive',
            'price'=>'sometimes|required|numeric|min:0',
            'compare_price'=>'nullable|numeric|gt:price'  
           ]);

           $product->update($request->all());
           return Response::json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Product::destroy($id);
    return [
        'message' => 'Product deleted successfully',
    ];
    }
}
