<?php

namespace App\Http\Controllers;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

use function Pest\Laravel\get;

class HomeController extends Controller
{
   public function index()
   {
        $banners= Banner::all();

        $products= Product::limit(4)->get();

        $popular= Product::latest()->limit(4)->get();

        $recent= Product::latest()->limit(4)->get();

        $electronics= Category::where('c_id', 11)->limit(4)->get();

        return view('home',compact('banners','products','electronics','popular','recent'));
   }
}


// 08:40

