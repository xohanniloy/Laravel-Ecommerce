<?php

namespace App\Models;

use App\Models\Product;
use App\Models\CustomerProfile;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['product_id', 'customer_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer(){
        return $this->belongsTo(CustomerProfile::class);
    }
}
