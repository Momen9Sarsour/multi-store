<?php

namespace App\Http\Controllers\Dashboard;

use Exception;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    //
    public function index(Request $request)
    {
        // $store = Store::all();
        $store = Auth::user()->store;
        $category = Category::all();
        $product = Product::all();
        $user = User::all();
        $order = Order::all();
        $delivery = Delivery::all();

        $query = Order::query();
        $query->where('store_id', $store->id);
        $search = $request->search;
        $status = $request->status;
        // Apply name filter
        // if ($request->has('search')) {
        //     $query->where('name', 'like', '%' . $request->input('search') . '%');
        // }
        // if ($search !== null) {
        //     $query->where(function ($query) use ($search) {
        //         $query->where('name', 'like', '%' . $search . '%')
        //               ->orWhereNull('name'); // Include orders with no name
        //     });
        // }
        // if ($search !== null) {
        //     $query->where(function ($query) use ($search) {
        //         $query->where('name', 'like', '%' . $search . '%')
        //               ->orWhere('user_id', function ($query) use ($search) {
        //                   $query->select('id')
        //                         ->from('users')
        //                         ->where('name', 'like', '%' . $search . '%');
        //               });
        //     });
        // }

        $status = $request->input('status');
        if ($status === 'Pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'processing') {
            $query->where('status', 'processing');
        } elseif ($status === 'delivering') {
            $query->where('status', 'delivering');
        } elseif ($status === 'completed') {
            $query->where('status', 'completed');
        } elseif ($status === 'cancelled') {
            $query->where('status', 'cancelled');
        } elseif ($status === 'refunded') {
            $query->where('status', 'refunded');
        }

        $orders = $query->get();

        return view('dashboard.VendorAdmin.orders.index', compact('category', 'product', 'store', 'user', 'order', 'delivery', 'orders', 'search', 'status'));
    }

    public function create()
    {
        // $store = Store::all();
        $user = User::all();
        $users = Auth::user();
        // $product = Product::all();
        $store = $users->store; // Assuming you have a relationship set up between User and Store models
        $product = $store->products;
        $delivery = Delivery::all();
        $order = new Order();
        return view('dashboard.VendorAdmin.orders.create', compact('user', 'product', 'delivery', 'order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable',
            'store_id' => 'nullable',
            'delivery_id' => 'nullable',
            'product_id' => 'nullable',
            'total' => 'required',
            'address' => 'nullable',
            'status' => 'nullable',
        ]);
        // store data
        $store = Auth::user()->store;
        $order = new Order();
        $order->user_id = $request->user_id;
        $order->store_id = $store->id;
        $order->delivery_id = $request->delivery_id;
        $order->product_id = $request->product_id;
        $order->total = $request->total;
        // $order->address = $request->address;
        $order->payment_method = "Payment";
        $order->status = $request->status;
        $order->save();
        // dd($request->all());
        //PRG
        return redirect()->route('VendorAdminOrders.index')->with('success', 'Order created!');
    }

    public function edit(string $id)
    {
        try {
            $order = Order::findorfail($id);
        } catch (Exception $e) {
            return redirect()->route('adminOrder.index')
                ->with('info', 'Record not found');
        }
        $user = User::all();
        $users = Auth::user();
        //   $store=Store::findOrFail($id);
        $store = $users->store; // Assuming you have a relationship set up between User and Store models
        $product = $store->products;
        $delivery = Delivery::all();
        $order = Order::findOrFail($id);

        return view('dashboard.VendorAdmin.orders.edit',compact('user' , 'product', 'delivery', 'order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'nullable',
            'store_id' => 'nullable',
            'delivery_id' => 'nullable',
            'product_id' => 'nullable',
            'total' => 'required',
            'address' => 'nullable',
            'status' => 'nullable',
        ]);
        //store data
        $store = Auth::user()->store;
        $order = Order::find($id);
        $order->user_id = $request->user_id;
        $order->store_id = $store->id;
        $order->delivery_id = $request->delivery_id;
        $order->product_id = $request->product_id;
        $order->total = $request->total;
        // $order->address = $request->address;
        $order->payment_method = "Payment";
        $order->status = $request->status;
        $order->save();

        return redirect()->route('VendorAdminOrders.index')->with('success', 'Order updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('VendorAdminOrders.index')->with('success', 'Order deleted!');
    }



}
