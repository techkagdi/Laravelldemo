<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    public function checkout()
        {
            return view('checkout');
        }

        public function placeOrder(Request $request)
        {
            $request->validate([
                'cart' => 'required|array|min:1',
                'payment_mode' => 'required|string',
            ]);

            $user = Auth::user();

            if(!$user){
                return response()->json(['success' => false, 'message' => 'Not Logged In']);
            }

            $cart = $request->cart;

            $total= 0;
            foreach($cart as $item){
                if(!isset($item['product_price'], $item['quantity'])){
                    return response()->json(['success' => false, 'message' => 'Invalid Cart Item Data']);
                }
                $total += $item['product_price'] * $item['quantity'];
            }

            $year = now()->format('Y');
            $lastOrder = Order::whereYear('created_at', $year)->orderBy('order_id', 'desc')->first();
            $orderNo = 'ORD' . $year . str_pad($lastOrder ? intval(substr($lastOrder->order_no, -3)) + 1 : 1, 3, '0', STR_PAD_LEFT);

            $order = Order::create([
                'user_id' => $user->user_id,
                'order_no' => $orderNo, //ORD2025001
                'status' => 'pending',
                'payment_mode' => $request->payment_mode,
                'total' => $total,
            ]);

            foreach($cart as $item){
                if(!isset($item['product_price'], $item['quantity'], $item['image_url'], $item['product_name'], ))
                    {
                        continue;
                }

                OrderItem::create([
                    'order_id' => $order->order_id,
                    'image' => $item['image_url'],
                    'name' => $item['product_name'],
                    'price' => $item['product_price'],
                    'qty' => $item['quantity'],
                    'total' => $item['product_price'] * $item['quantity'],

                ]);
            }
            return response()->json(['success' => true, 'order_id' => $order->order_id]);
        }
}
