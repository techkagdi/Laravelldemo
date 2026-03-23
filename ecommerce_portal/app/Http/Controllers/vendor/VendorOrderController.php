<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class VendorOrderController extends Controller
{
    public function index()
    {
        $vendorId = Auth::guard('vendor')->id();

        $orders = Order::whereHas('orderItems.product', function ($query) use ($vendorId){
            $query->where('vendor_id', $vendorId);
        })->with(['orderItems' => function ($query) use ($vendorId){
            $query->whereHas('product', function ($q) use ($vendorId){
                $q->where('vendor_id', $vendorId);
            });
        }])->latest()->get();
        return view('vendor.orders.index', compact('orders'));
    }
}
