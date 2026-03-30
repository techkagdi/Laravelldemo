<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class VendorOrderController extends Controller
{
    public function index()
    {
        $vendorId = session('vendorId');

        $orders = Order::whereHas('items.product', function ($query) use ($vendorId){
            $query->where('v_id', $vendorId);
        })->with(['items' => function ($query) use ($vendorId){
            $query->whereHas('product', function ($q) use ($vendorId){
                $q->where('v_id', $vendorId);
            });
        }])->latest()->get();

        return view('vendor.orders', compact('orders'));
    }
}