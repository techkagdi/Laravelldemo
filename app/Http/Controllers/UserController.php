<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Billing;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    // public function submitPhoneAndBilling(Request $request)
    // {
    //     $request->validate([
    //         'phone' => 'required|max:10',
    //         'fullname' => 'required',
    //         'email' => 'required|email',
    //         'pincode' => 'required',
    //         'landmark' => 'required',
    //         'city' => 'required',
    //         'state' => 'required',
    //         'address' => 'required',

    //     ]);

    //     if (User::where('phone', $request->phone)->exists()) {
    //         return response()->json(['error' => 'phone_exists']);
    //     }
    //     Session::put('otp', '1234');
    //     Session::put('billing_data', $request->all());
    //     Session::put('phone', $request->phone);

    //     return response()->json(['otp_sent' => true]);
    // }


    // public function submitEmailAndBilling(Request $request)
    // {
    //     $request->validate([
    //         'email'    => 'required|email',
    //         'fullname' => 'required',
    //         'pincode'  => 'required',
    //         'landmark' => 'required',
    //         'city'     => 'required',
    //         'state'    => 'required',
    //         'address'  => 'required',
    //     ]);

    //     if (User::where('email', $request->email)->exists()) {
    //         return response()->json(['error' => 'email_exists']);
    //     }

    //     $otp = rand(100000, 999999);

    //     Session::put('otp', $otp);
    //     Session::put('otp_expires_at', now()->addMinutes(10));
    //     Session::put('billing_data', $request->all());
    //     Session::put('email', $request->email);

    //     $mail = new PHPMailer(true);
    //     try {
    //         $mail->isSMTP();
    //         $mail->Host       = env('MAIL_HOST');
    //         $mail->SMTPAuth   = true;
    //         $mail->Username   = env('MAIL_USERNAME');
    //         $mail->Password   = env('MAIL_PASSWORD');
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //         $mail->Port       = env('MAIL_PORT');
    //         $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    //         $mail->addAddress($request->email);
    //         $mail->isHTML(true);
    //         $mail->Subject = 'Your OTP Code';
    //         $mail->Body    = "<p>Your OTP is: <strong>{$otp}</strong></p><p>Valid for 10 minutes.</p>";
    //         $mail->send();

    //         return response()->json(['otp_sent' => true]);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'mail_failed', 'message' => $mail->ErrorInfo]);
    //     }
    // }

    // public function verifyOtp(Request $request)
    // {
    //     $user = User::where('phone', $request->phone)->first();

    //     if ($user) {
    //         if (!$user->is_login) {
    //             $user->is_login = true;
    //             $user->save();
    //         }
    //     } else {
    //         $user = User::create([
    //             'phone' => $request->phone,
    //             'is_login' => true,
    //         ]);
    //     }
    //     $billing = new Billing(Session::get('billing_data'));
    //     $billing->user_id = $user->user_id;
    //     $billing->save();

    //     Auth::login($user);

    //     Session::forget(['otp', 'billing_data', 'phone']);
    //     Session::put('user_id', $user->user_id);

    //     return response()->json(['verified' => true]);
    // }


    // public function verifyOtp(Request $request)
    // {
    //     $sessionOtp       = Session::get('otp');
    //     $sessionExpiresAt = Session::get('otp_expires_at');
    //     $email            = Session::get('email');

    //     if (!$sessionOtp || !$email) {
    //         return response()->json(['verified' => false, 'error' => 'Session expired. Please try again.']);
    //     }

    //     if (now()->greaterThan($sessionExpiresAt)) {
    //         return response()->json(['verified' => false, 'error' => 'OTP expired. Please request a new one.']);
    //     }

    //     if ((string)$request->otp !== (string)$sessionOtp) {
    //         return response()->json(['verified' => false, 'error' => 'Invalid OTP.']);
    //     }

    //     $user = User::where('email', $email)->first();

    //     if ($user) {
    //         if (!$user->is_login) {
    //             $user->is_login = true;
    //             $user->save();
    //         }
    //     } else {
    //         $user = User::create([
    //             'email'    => $email,
    //             'is_login' => true,
    //         ]);
    //     }

    //     $billing          = new Billing(Session::get('billing_data'));
    //     $billing->user_id = $user->user_id;
    //     $billing->save();

    //     Auth::login($user);

    //     Session::forget(['otp', 'otp_expires_at', 'billing_data', 'email']);
    //     Session::put('user_id', $user->user_id);

    //     return response()->json(['verified' => true]);
    // }
    public function submitEmailAndBilling(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'fullname' => 'required',
            'phone'    => 'required|max:10',
            'pincode'  => 'required',
            'landmark' => 'nullable',
            'city'     => 'required',
            'state'    => 'required',
            'address'  => 'required',
            'country'  => 'nullable',
        ]);

        // Check if email already registered
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['error' => 'email_exists']);
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store everything in session until OTP is verified
        Session::put('otp',            $otp);
        Session::put('otp_expires_at', now()->addMinutes(10));
        Session::put('billing_data',   $request->all());
        Session::put('email',          $request->email);

        // Send OTP via PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = env('MAIL_PORT');
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($request->email);
            $mail->isHTML(true);
            $mail->Subject = 'Your Registration OTP';
            $mail->Body    = "
            <h2>Welcome!</h2>
            <p>Your OTP for registration is: <strong style='font-size:24px'>{$otp}</strong></p>
            <p>This OTP is valid for <strong>10 minutes</strong>.</p>
        ";
            $mail->send();

            return response()->json(['otp_sent' => true]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to send OTP: ' . $mail->ErrorInfo
            ]);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $sessionOtp       = Session::get('otp');
            $sessionExpiresAt = Session::get('otp_expires_at');
            $email            = Session::get('email');
            $billingData      = Session::get('billing_data');

            // Debug: uncomment these lines temporarily if still getting errors
            // return response()->json([
            //     'debug_otp'      => $sessionOtp,
            //     'debug_email'    => $email,
            //     'debug_billing'  => $billingData,
            //     'request_otp'    => $request->otp,
            //     'request_email'  => $request->email,
            // ]);

            // Session expired or missing
            if (!$sessionOtp || !$email || !$billingData) {
                return response()->json([
                    'verified' => false,
                    'error'    => 'Session expired. Please fill the form again.'
                ]);
            }

            // OTP expired
            if ($sessionExpiresAt && now()->greaterThan($sessionExpiresAt)) {
                return response()->json([
                    'verified' => false,
                    'error'    => 'OTP expired. Please request a new one.'
                ]);
            }

            // OTP mismatch
            if ((string)$request->otp !== (string)$sessionOtp) {
                return response()->json([
                    'verified' => false,
                    'error'    => 'Invalid OTP. Please try again.'
                ]);
            }

            // Check if user already exists
            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'email'    => $email,
                    'phone'    => $billingData['phone'] ?? null,
                    'is_login' => true,
                ]);
            } else {
                $user->is_login = true;
                $user->save();
            }

            // Save billing record
            $billing           = new Billing();
            $billing->user_id  = $user->user_id;
            $billing->fullname = $billingData['fullname']  ?? '';
            $billing->email    = $billingData['email']     ?? '';
            // $billing->phone    = $billingData['phone']     ?? '';
            $billing->address  = $billingData['address']   ?? '';
            $billing->city     = $billingData['city']      ?? '';
            $billing->state    = $billingData['state']     ?? '';
            $billing->pincode  = $billingData['pincode']   ?? '';
            $billing->landmark = $billingData['landmark']  ?? '';
            $billing->country  = $billingData['country']   ?? 'India';
            $billing->save();

            Auth::login($user);

            Session::forget(['otp', 'otp_expires_at', 'billing_data', 'email']);
            Session::put('user_id', $user->user_id);

            return response()->json(['verified' => true]);
        } catch (\Exception $e) {
            // This will show you the REAL error instead of generic "server error"
            return response()->json([
                'verified' => false,
                'error'    => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/')->with('msg', 'Logged out successfully');
    }
    public function invoice($id)
    {
        $user = Auth::user();

        $order = \App\Models\Order::with('items')->where('order_id', $id)->firstOrFail();

        $billing = \App\Models\Billing::where('user_id', $user->user_id)->first();

        return view('user.invoice', compact('order', 'billing', 'user'));
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

    function checkPhone(Request $request)
    {
        $request->validate(['phone' => 'required']);
        $exists = User::where('phone', $request->phone)->exists();

        return response()->json(['exists' => $exists]);
    }

    // function AuthenticationVerifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'phone' => 'required',
    //         'otp' => 'required',
    //     ]);

    //     $user = User::where('phone', $request->phone)->first();

    //     if ($user && $request->otp === '1234') {
    //         $user->is_login = true;
    //         $user->save();
    //         Auth::login($user);

    //         return response()->json([
    //             'verified' => true,
    //             'dashboard_url' => url('user/' . $user->user_id),
    //         ]);
    //     }
    //     return response()->json(['verified' => false, 'error' => 'Invalid Otp']);
    // }/

    public function AuthenticationSendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Look up user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'error' => 'No account found with this email.']);
        }

        $otp                  = rand(100000, 999999);
        $user->otp            = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = env('MAIL_PORT');
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($user->email);
            $mail->isHTML(true);
            $mail->Subject = 'Your Login OTP';
            $mail->Body    = "<p>Your OTP is: <strong>{$otp}</strong></p><p>Valid for 10 minutes.</p>";
            $mail->send();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $mail->ErrorInfo]);
        }
    }

    public function AuthenticationVerifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['verified' => false, 'error' => 'Email not found.']);
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['verified' => false, 'error' => 'OTP expired.']);
        }

        if ((string)$request->otp !== (string)$user->otp) {
            return response()->json(['verified' => false, 'error' => 'Invalid OTP.']);
        }

        $user->otp            = null;
        $user->otp_expires_at = null;
        $user->is_login       = true;
        $user->save();

        Auth::login($user);

        return response()->json([
            'verified'      => true,
            'dashboard_url' => url('user/' . $user->user_id),
        ]);
    }
}
