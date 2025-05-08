<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    // $guarded is removed since it's not needed when using $fillable
    protected $fillable = [
        'product_id', 
        'quantity',
        'status',
        'total_price',
        'notes',
    ];

<<<<<<< HEAD
    // Define relationship with User model
    public function user() 
=======
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
>>>>>>> 54da1c908276b32719a33e777eec789f05f71469
    {
        return $this->belongsTo(User::class, 'user_id');
    }

<<<<<<< HEAD
    // Define relationship with Product model
=======

>>>>>>> 54da1c908276b32719a33e777eec789f05f71469
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
