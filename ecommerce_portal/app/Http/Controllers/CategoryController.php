<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function detail($category)
    {
        
        $category= Category::where('c_name', $category)->first();

        return view('category',['category'=>$category]);
    }
}
