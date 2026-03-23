<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    public $fillable = ["full_name","phone","email","password","address","id_number","business_name",
    "business_type","gst_number","business_category","bank_account_no","payment_method","image"];

    protected $primaryKey = 'v_id';
    
}
