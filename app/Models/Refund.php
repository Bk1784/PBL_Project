<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'refund_reason',
        'refund_image',
        'reject_reason',
        'status',
        'refunded_at',
        'refund_qty',
    ];

    protected $casts = [
        'refunded_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
