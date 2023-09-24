<?php

namespace App\Http\Controllers\Front;

use Throwable;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Arr;
use App\Events\OrderCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use App\Repositories\Cart\CartRepository;

class CheckOutController extends Controller
{
    //
    public function create(CartRepository $cart)
    {
        if ($cart->get()->count() == 0) {
            return redirect()->route('home');
        }

        return view('front.checkout', [
            'cart' => $cart,
            'countries' => Countries::getNames('en'),
            //'countries'=> "en",
        ]);
    }


    public function store(Request $request, CartRepository $cart)
    {
        $request->validate([
            'addr.billing.first_name' => ['required', 'string', 'max:255'],
            'addr.billing.last_name' => ['required', 'string', 'max:255'],
            'addr.billing.email' => ['required', 'string', 'max:255'],
            'addr.billing.phone_number' => ['required', 'string', 'max:255'],
            'addr.billing.city' => ['required', 'string', 'max:255'],
        ]);
        $items = $cart->get()->groupBy('product.store_id')->all();
        DB::beginTransaction();
        try {
            foreach ($items as $store_id => $cart_items) {
                $order = Order::create([
                    'store_id' => $store_id,
                    'user_id' => Auth::id(),
                    'payment_method' => 'cod',
                ]);

                foreach ($request->post('addr') as $type => $address) {
                    $address['type'] = $type;
                    // $address['country']="1";
                    $order->addresses()->create($address);
                }
            }

            DB::commit();
            event(new OrderCreated($order));
            foreach ($cart_items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);
            }
            $possibleDeliveryIds = DB::table('deliveries')->pluck('id')->toArray();
            $randomDeliveryId = Arr::random($possibleDeliveryIds);
            $order->delivery_id =  $randomDeliveryId;
            $order->product_id = $item->product_id;
            $order->save();
            DB::commit();
            // event('order.created',$order, Auth::user());
            event(new OrderCreated($order));
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect()->route('orders.payments.create',$order->id);
    }
}
