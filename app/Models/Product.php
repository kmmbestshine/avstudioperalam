<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['units','productcategory_id','company_id', 'name', 'code','codess', 'quantity', 'stock', 'price', 'status', 'created_by', 'modified_by', 'created_at', 'updated_at','gst_percent'];

}
