<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // $guarded is removed since it's not needed when using $fillable
    protected $fillable = [
        'client_id',
        'product_id', 
        'quantity',
        'status',
        'total_price',
        'notes',
    ];

    // Define relationship with User model
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    // Define relationship with Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Define relationship with OrderItem model
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
