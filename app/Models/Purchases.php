<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Purchase.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    protected $fillable = ['product_id', 'qty', 'total_price'];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
