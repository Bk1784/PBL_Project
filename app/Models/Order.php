<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'product_id', 
        'quantity',
        'status',
        'total_price',
        'notes'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}