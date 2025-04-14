<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description', 'price', 'stock'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function totalSold()
    {
        return $this->orderItems()->sum('qty');
    }

    public function totalRevenue()
    {
        return $this->orderItems()->sum(DB::raw('qty * price'));
    }

    public function scopeFrequentlySold($query)
    {
        return $query->withCount(['orderItems as sold_count' => function($query) {
            $query->select(DB::raw('sum(qty)'));
        }])->orderBy('sold_count', 'desc');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
