<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $primaryKey = 'bill_id';

    // 👇 agar table name 'billing' hai to ye add karo
    protected $table = 'billing';

    protected $fillable = [
    'user_id', 
    'country',
    'fullname',
    'email',
    'pincode',
    'landmark',
    'city',
    'state',
    'address'
];
    
}