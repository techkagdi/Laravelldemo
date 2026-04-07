<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductdetailController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\admin\AdminCategoryController;
use App\Http\Controllers\admin\AdminBannerController;
use App\Http\Middleware\VendorMiddleware;
use App\Http\Controllers\vendor\ProductController;
use App\Http\Controllers\vendor\VendorOrderController;
use App\Http\Controllers\Admin\AdminReportController;

Route::get('/snehal', function () {
    return view('welcome');
});

// Route::post('/check-phone', [UserController::class, 'checkPhone'])->name('check.phone');
// Route::post('/authentication-verify-otp', [UserController::class, 'authenticationVerifyOtp'])->name('authentication.verify.otp');

Route::post('/check-email', [UserController::class, 'checkEmail'])->name('check.email');

Route::post('/authentication-send-otp', [UserController::class, 'AuthenticationSendOtp'])->name('authentication.send.otp');

Route::post('/authentication-verify-otp', [UserController::class, 'AuthenticationVerifyOtp'])->name('authentication.verify.otp');



// Order Report
Route::get('admin/order-report', [AdminReportController::class, 'orderReport'])->name('admin.order.report');
Route::get('admin/order-report/export', [AdminReportController::class, 'exportOrderReport'])->name('admin.order.report.export');

// Product Report
Route::get('admin/product-report', [AdminReportController::class, 'productReport'])->name('admin.product.report');
Route::get('admin/product-report/export', [AdminReportController::class, 'exportProductReport'])->name('admin.product.report.export');
Route::post('/submit-email-billing', [UserController::class, 'submitEmailAndBilling'])->name('submit.email.billing');
Route::post('/verify-otp', [UserController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place.order');
Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

Route::get('/', [HomeController::class, 'index']);

Route::get('Category/{category}', [CategoryController::class, 'detail'])->name('category');

Route::get('Sub-Category/{category}/{sub_category}', [SubcategoryController::class, 'detail'])->name('category.sub_category');

Route::get('Sub-Category/{category}/{sub_category}/{product_detail}', [
    ProductdetailController::class,
    'detail'
])->name('product_detail');

Route::get('/cart-list/{slug}', [CartController::class, 'list']);

Route::get('/checkout/{slug}', [CheckoutController::class, 'checkout']);

Route::get('register', [UserController::class, 'register']);

Route::get('register1', [UserController::class, 'register1']);

Route::get('login', [UserController::class, 'login']);

Route::get('login1', [UserController::class, 'login1']);

// User Dashboard Route Start Here:
Route::get('user/{slug}', [UserController::class, 'index']);
Route::get('user/invoice/{id}', [UserController::class, 'invoice']);
Route::get('user/order-history/{slug}', [UserController::class, 'history']);
Route::get('admin/order/processing/{id}', [AdminController::class, 'markProcessing'])->name('order.processing');
Route::get('admin/order/ontheway/{id}', [AdminController::class, 'markOnTheWay'])->name('order.ontheway');
Route::get('admin/order/delivered/{id}', [AdminController::class, 'markDelivered'])->name('order.delivered');
Route::get('user/detail/{slug}', [UserController::class, 'detail']);

Route::get('user/settings/{slug}', [UserController::class, 'settings']);
Route::post('user/settings/update', [UserController::class, 'updateSettings'])->name('user.settings.update');

// Vendor Dashboard Route Start Here:

Route::get('vendor/signup', [VendorController::class, 'signup']);
Route::post('vendor/signup', [VendorController::class, 'register']);


// Route::prefix('vendor')->middleware(['auth:vendor'])->group(function(){
//     Route::get('/orders', [VendorOrderController::class, 'index'])->name('vendor.orders');
// });

Route::prefix('vendor')->middleware([VendorMiddleware::class])->group(function () {

    Route::get('/orders', [VendorOrderController::class, 'index'])->name('vendor.orders');
});


Route::get('vendor/login', [VendorController::class, 'login']);
Route::post('vendor/login', [VendorController::class, 'login_create']);

Route::get('vendor/logout', [VendorController::class, 'logout']);

Route::get('vendor/forget', [VendorController::class, 'forget']);


Route::get('admin/', [VendorController::class, 'index'])->middleware(VendorMiddleware::class);

Route::get('admin/add-product', [ProductController::class, 'addproduct']);
Route::post('admin/add-product', [ProductController::class, 'createproduct']);

Route::get('admin/view-product', [ProductController::class, 'viewproduct']);

Route::get('admin/edit-product/{p_id}', [ProductController::class, 'editproduct']);
Route::put('admin/edit-product/{p_id}', [ProductController::class, 'updateproduct']);

Route::delete('admin/delete-product/{p_id}', [ProductController::class, 'deleteproduct']);

// Route::get('vendor/orders',[VendorController::class, 'orders']);

// Route::get('vendor/order-detail',[VendorController::class, 'orderdetail']);
Route::get('admin/order-detail/{id}', [VendorController::class, 'orderdetail'])->name('admin.order.detail');

Route::get('admin/users', [VendorController::class, 'users']);

Route::get('admin/profile', [VendorController::class, 'profile']);
Route::put('admin/profile', [VendorController::class, 'updateprofile']);

use App\Http\Controllers\AuthController;

Route::get('/logout', [UserController::class, 'logout'])->name('logout');
// Admin Dashboard Start Here

Route::get('admin/login', [AdminController::class, 'login']);

Route::get('admin/', [AdminController::class, 'index']);

Route::get('admin/order-detail/{slug}', [AdminController::class, 'orderdetail'])->name('admin.order.detail');

Route::get('admin/add-category', [AdminCategoryController::class, 'addcategory']);
Route::post('admin/add-category', [AdminCategoryController::class, 'createcategory']);

Route::get('admin/view-category', [AdminCategoryController::class, 'viewcategory']);

Route::get('admin/edit-category/{c_id}', [AdminCategoryController::class, 'editcategory']);
Route::put('admin/edit-category/{c_id}', [AdminCategoryController::class, 'updatecategory']);

Route::delete('admin/delete-category/{c_id}', [AdminCategoryController::class, 'deletecategory']);

Route::get('admin/users', [AdminController::class, 'users']);

Route::get('admin/vendors', [AdminController::class, 'vendors']);

Route::get('admin/order/{id}/processing', [VendorController::class, 'processing'])->name('order.processing');
Route::get('admin/order/{id}/ontheway', [VendorController::class, 'ontheway'])->name('order.ontheway');
Route::get('admin/order/{id}/delivered', [VendorController::class, 'delivered'])->name('order.delivered');


Route::get('admin/vendors/{id}/verified', [VendorController::class, 'verify'])->name('vendor.verify');
Route::get('admin/vendors/{id}/unverified', [VendorController::class, 'unverify'])->name('vendor.unverify');

Route::get('admin/orders', [AdminController::class, 'orders']);

Route::get('admin/add-banner', [AdminBannerController::class, 'addbanner']);
Route::post('admin/add-banner', [AdminBannerController::class, 'createbanner']);

Route::get('admin/view-banner', [AdminBannerController::class, 'viewbanner']);

Route::delete('admin/delete-banner/{b_id}', [AdminBannerController::class, 'deletebanner']);


// Route::get('admin/products',[AdminController::class, 'products']);
