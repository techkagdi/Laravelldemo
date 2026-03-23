<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Category;

use Illuminate\Http\Request;

class AdminCategoryController extends Controller 
{
    
    public function addcategory()
    {
        $category=Category::where('p_c_id', 0)->get();
        return view('admin/add-category',compact('category'));
    }

    public function createcategory(Request $request)
    {
        $request->validate([
            "c_name" => "required",
            "c_commission" => "required"
        ]);
        Category::create([
            "p_c_id" => $request->p_c_id,
            "c_name" => $request->c_name,
            "c_commission" => $request->c_commission
       ]);
       return redirect('admin/add-category')->with('msg','Add Category Successfully');
    }

    public function viewcategory()
    {
        $category= Category::all();
        return view('admin/view-category',compact('category'));
    }

    public function editcategory($c_id)
    {
        $category= Category::find($c_id);
        $p_category=Category::where('p_c_id', 0)->get();
        return view('admin/edit-category',compact('category','p_category'));
    }

     public function updatecategory(Request $request,$c_id)
    {
        $category= Category::find($c_id);
        $request->validate([
            "c_name" => "required",
            "c_commission" => "required"
        ]);
        $category->update([
            "p_c_id" => $request->p_c_id,
            "c_name" => $request->c_name,
            "c_commission" => $request->c_commission
       ]);
        return redirect('admin/view-category')->with('msg','Update Category Successfully');
    }

    public function deletecategory($c_id)
    {
        $category= Category::find($c_id);
        
        $category->delete();
        return redirect('admin/view-category')->with('msg','Delete Category Successfully');
    }


}
