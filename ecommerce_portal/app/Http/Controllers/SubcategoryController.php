<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class SubcategoryController extends Controller
{
    public function detail($category,$sub_category)
    {
        $subcat = Category::where('c_name',$sub_category)->firstOrFail();

        return view('subcategory',['subcat'=>$subcat]);
    }
}