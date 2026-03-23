<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public $fillable = ["b_image","b_alt"];

    protected $primaryKey = 'b_id';
    }
