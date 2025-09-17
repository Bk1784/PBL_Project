<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Process\ProcessResult;

class OrderItemClient extends Model
{
    protected $table = 'order_item_clients';
    protected $fillable = ['product_id','qty','price'];

    public function produk(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
