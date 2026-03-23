<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $fillable = ["p_c_id","c_name","c_commission"];
    protected $primaryKey = 'c_id';

    function parentCategory()
    {
        return $this->belongsTo(Category::class,'p_c_id');
    }

     function subCategory()
    {
        return $this->hasMany(Category::class,'p_c_id');
    }
    function products()
    {
        return $this->hasMany(Product::class,'c_id');
    }
}

