<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminBannerController extends Controller 
{
    
    public function addbanner()
    {
        return view('admin/add-banner');
    }

    public function createbanner(Request $request)
    {
        $request->validate([
            "banner" => "required",
            "alt" => "required"
        ]);
        Banner::create([
            "b_image" => $request->file('banner')->store('banners', 'public'),
            "b_alt" => $request->alt
       ]);
       return redirect('admin/add-banner')->with('msg','Add Banner Successfully');
    }

    public function viewbanner()
    {
        $banners= Banner::all();
        return view('admin/view-banner',compact('banners'));
    }

    public function deletebanner($b_id)
    {
        $banner= Banner::find($b_id);
        
        $banner->delete();
        return redirect('admin/view-banner')->with('msg','Delete Banner Successfully');
    }


}
