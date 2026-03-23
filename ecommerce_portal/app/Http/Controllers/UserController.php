<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Billing;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function register()
{
    return view('register');
}
    
    public function register1()
{
    return view('register1');
}

public function login()
{
    return view('login');
}

public function login1()
{
    return view('login1');
}

// User Dashboard Function Start Here:
public function index()
{
    return view('user/index');
}

public function history()
{
    return view('user/order-history');

}

public function detail($order_id)
{
    $user = Auth::user();
    
        $billing = Billing::where('user_id', $user->user_id)->first();
        $order = Order::with('items')
        ->where('user_id', $user->user_id)
        ->where('order_id', $order_id)
        ->firstOrFail();
        
        return view('user.detail', compact('order', 'billing', 'user'));
    
}

public function settings()
{
    return view('user/settings');
}
public function submitPhoneAndBilling(Request $request)
{
    $request->validate([
        'phone' => 'required|max:10',
        'fullname' => 'required',
        'email' => 'required|email',
        'pincode' => 'required',
        'landmark' => 'required',
        'city' => 'required',
        'state' => 'required',
        'address' => 'required',

    ]);

    if (User::where('phone', $request->phone)->exists()) {
        return response()->json(['error' => 'phone_exists']);
    }
        Session::put('otp', '1234');
        Session::put('billing_data', $request->all());
        Session::put('phone', $request->phone);
        
        return response()->json(['otp_sent' => true]);  
}

public function verifyOtp(Request $request)
{
    $user = User::where('phone', $request->phone)->first();

    if ($user){
        if(!$user->is_login){
            $user->is_login = true;
            $user->save();        
        }
    } else {
        $user = User::create([
            'phone' => $request->phone,
            'is_login' => true,
        ]);
    }
    $billing = new Billing(Session::get('billing_data'));
    $billing->user_id = $user->user_id;
    $billing->save();

    Auth::login($user);

    Session::forget(['otp', 'billing_data', 'phone']);
    Session::put('user_id', $user->user_id);

        return response()->json(['verified' => true]);
} 
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'fullname' => 'required',
            'email' => 'required|email',
            'pincode' => 'nullable|string',
            'landmark' => 'nullable|string',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
        ]);

        $billing = Billing::firstOrCreate(['user_id' => $user->user_id]);

        $billing->update([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'pincode' => $request->pincode,
            'landmark' => $request->landmark,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
        ]);
        return redirect()->back()->with('success', 'Settings Updated Successfully');

    }

    function checkPhone(Request $request){
        $request->validate(['phone' =>'required']);
        $exists = User::where('phone', $request->phone)->exists();

        return response()->json(['exists' => $exists]);
    }

    function AuthenticationVerifyOtp(Request $request)
    {
        $request->validate([
            'phone' =>'required',
            'otp' =>'required',
            ]);

            $user = User::where('phone', $request->phone)->first();

            if($user && $request->otp === '1234'){
                $user->is_login = true;
                $user->save();
                Auth::login($user);

                return response()->json([
                    'verified' => true,
                    'dashboard_url' => url('user/' .$user->user_id),
                ]);
            }
            return response()->json(['verified' => false, 'error' => 'Invalid Otp']);
    }
}