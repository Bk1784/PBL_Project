<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> 54f8835e0daa3c33b3ac5b3711778088801a8a57
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
<<<<<<< HEAD
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
=======
    use HasFactory;
    protected $guarded = [];
}
>>>>>>> 54f8835e0daa3c33b3ac5b3711778088801a8a57
