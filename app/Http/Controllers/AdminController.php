<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin/login');
    }

    public function index()
    {
        return view('admin/index');
    }
    // public function addcategory()
    // {
    //     return view('admin/add-category');
    // }

    // public function viewcategory()
    // {
    //     return view('admin/view-category');
    // }

    // public function editcategory()
    // {
    //     return view('admin/edit-category');
    // }

    public function users()
    {
        return view('admin/users');
    }
    public function markProcessing($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'processing';
        $order->save();

        return redirect()->back()->with('msg', 'Order marked as Processing');
    }

    public function markOnTheWay($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'on the way';
        $order->save();

        return redirect()->back()->with('msg', 'Order marked as On The Way');
    }

    public function markDelivered($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'delivered';
        $order->save();

        return redirect()->back()->with('msg', 'Order marked as Delivered');
    }
    public function vendors()
    {
        return view('admin/vendors');
    }

    public function orders()
    {
        return view('admin/orders');
    }

    public function orderdetail($id)
    {
        $order = Order::with('items', 'user')
            ->where('order_id', $id)
            ->firstOrFail();

        return view('admin.order-detail', compact('order'));
    }
}

    //  public function products()
    // {
    //     return view('admin/products');
    // }
