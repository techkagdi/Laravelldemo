<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;
use App\Models\Order;

class VendorController extends Controller
{
        public function signup()
    {
        return view('vendor/signup');
    }

       public function register(Request $request)
    {
       $request->validate([
       "full_name" => "required",
       "phone" => "required|regex:/^[0-9]{10}/|unique:vendors,phone",
       "email" => "required|email|unique:vendors,email",
       "password" => "required",
       "address" => "required"
       ]);

       Vendor::create([
            "full_name" => $request->full_name,
            "phone" => $request->phone,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "address" => $request->address
           
       ]);

       return redirect('vendor/signup')->with('msg','Registered Successfully');
    }
                                                   
    public function login()
    {
        return view('vendor/login');
    }

    public function login_create(Request $request)
    {
        $request->validate([
       "phone" => "required",
       "password" => "required"
     
        ]);
        $checkVendor=Vendor::where(['phone'=>$request->phone])->first();

        // dd($checkVendor);

        if($checkVendor && Hash::check($request->password,$checkVendor->password)){

            if($checkVendor->status=="verified") {
                session(['vendorLogin'=>true]);
                session(['vendorName'=>$checkVendor->full_name]);
                session(['vendorId'=>$checkVendor->v_id]);
                return redirect('vendor/');
            }else{
                return redirect('vendor/login')->with('msg','You are Not Verified');
            }       

        }else{
       return redirect('vendor/login')->with('msg','Invalid Phone/Password');
        }
    }

       public function logout()
    {
        session()->forget('vendorLogin');
               return redirect('vendor/login');
    }
   
    public function forget()
    {
        return view('vendor/forget');
    }

   

public function index()
{
    // Total Orders
    $totalOrders = Order::count();

    // Total Sale (sum of all orders)
    $totalSale = Order::sum('total');

    // Pending Orders
    $pendingOrders = Order::where('status', 'pending')->count();

    // Recent Orders (latest 5)
    $orders = Order::latest()->take(5)->get();

    return view('vendor.index', compact(
        'totalOrders',
        'totalSale',
        'pendingOrders',
        'orders'
    ));
}
    // public function index()
    // {
    // $vendorId =  Auth::guard('vendor')->id();

    //     $orders = Order::whereHas('orderItems.product', function ($query) use ($vendorId) {
    //         $query->where('v_id', $vendorId);
    //     })->with(['orderItems' => function ($query) use ($vendorId) {
    //         $query->whereHas('product', function ($q) use ($vendorId) {
    //             $q->where('v_id', $vendorId);
    //         });
    //     }])->latest()->get();

        // $allOrders = Order::whereHas('order_items.product', function ($query) use ($vendorId) {
        //     $query->where('v_id', $vendorId);
        // })->get();

        // $totalOrders = $allOrders->count();
        // $totalSale = $allOrders->sum('total');
        // $pendingOrders = $allOrders->where('status', 'pending')->count();

        // return view('vendor.index', compact('orders', 'totalOrders', 'totalSale', 'pendingOrders'));
        // return view('vendor.orders', compact('ordders'));
        
    // }

            // aa sacho code che vendor/orders no ...
//             public function orders()
// {
//     $vendorId = session('vendorId');

//     $orders = Order::whereHas('items.product', function ($query) use ($vendorId) {
//         $query->where('v_id', $vendorId);
//     })->with(['items' => function ($query) use ($vendorId) {
//         $query->whereHas('product', function ($q) use ($vendorId) {
//             $q->where('v_id', $vendorId);
//         });
//     }])->latest()->get();

//     return view('vendor.orders', compact('orders'));
// }
    

     public function orders()
{
    $vendorId = session('vendorId');

    $orders = Order::whereHas('items.product', function ($query) use ($vendorId) {
        $query->where('v_id', $vendorId);
    })->with(['items' => function ($query) use ($vendorId) {
        $query->whereHas('product', function ($q) use ($vendorId) {
            $q->where('v_id', $vendorId);
        });
    }])->latest()->get();

    return view('vendor.orders', compact('orders'));
}
    public function orderdetail($id)
    {

        // return view('vendor/order-detail');
        $vendorId = session('vendorId');
        $order = Order::with(['billing'])->findOrFail($id);

        $orderItems = $order->items()
        ->whereHas('product', function ($query) use ($vendorId){
            $query->where('v_id', $vendorId);
        })
        ->with('product')
        ->get();
        return view('vendor/order-detail', compact('order', 'orderItems'));
    }

    public function users()
    {
        return view('vendor/users');
    }

     public function profile()
    {
        $v_id = session('vendorId');
        
        $vendor = Vendor::find($v_id);
        return view('vendor/profile',compact('vendor'));
    }

    public function updateprofile(Request $request)
    {
        $v_id = session('vendorId');
        
        $vendor = Vendor::find($v_id);

        $request->validate([
            "full_name" => "required",
            "phone" => "required",
            "email" => "required",
            "address" => "required",
            "id_number" => "required",
            "business_name" => "required",
            "business_type" => "required",
            "gst_number" => "required",
            "business_category" => "required",
            "bank_account_no" => "required",
            "payment_method" => "required",
            
        ]);
        $image = $vendor->image;
        if ($request->hasFile('image'))
            {
                $image = $request->file('image')->store('vendors', 'public');
            }

        $vendor->update([
            "full_name" => $request->full_name,
            "phone" => $request->phone,
            "email" => $request->email,
            "address" => $request->address,
            "id_number" => $request->id_number,
            "business_name" => $request->business_name,
            "business_type" => $request->business_type,
            "gst_number" => $request->gst_number,
            "business_category" => $request->business_category,
            "bank_account_no" => $request->bank_account_no,
            "payment_method" => $request->payment_method,
            "image" => $image

        ]);

       return redirect('vendor/profile')->with('msg','Your Profile Updated Successfully!');
    }

    public function verify($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->status = 'verified';
        $vendor->save();

        return redirect()->back()->with('success', 'Vendor Verified Successfully');
    }

    public function unverify($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->status = 'unverified';
        $vendor->save();

        return redirect()->back()->with('success', 'Vendor UnVerified Successfully');
    }

    public function processing($id)
    {
        $order = Vendor::findOrFail($id);
        $order->status = 'processing';
        $order->save();

        return redirect()->back()->with('success', 'Order in Processing');
    }

    public function ontheway($id)
    {
        $order = Vendor::findOrFail($id);
        $order->status = 'on the way';
        $order->save();

        return redirect()->back()->with('success', 'Order on the Way');
    }

    public function delivered($id)
    {
        $order = Vendor::findOrFail($id);
        $order->status = 'delivered';
        $order->save();

        return redirect()->back()->with('success', 'Order Delivered');
    }






}
