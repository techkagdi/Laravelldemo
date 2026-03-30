<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductdetailController extends Controller
{
    public function detail($category,$sub_category,$product_detail)

{
    // dd($product_detail);

    $product_detail= Product::where('p_name',$product_detail)->first();
        // dd($product_detail);
    return view('product-detail',['product_detail'=>$product_detail]);
}
    }
