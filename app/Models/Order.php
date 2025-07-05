<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_orders';

    protected $fillable = [
        'id_users',
        'total_amount',
        'order_date',
        'status',
        'shipping_address',
        'payment_method',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public function items() {
        return $this->hasMany(OrderItem::class, 'id_orders', 'id_orders');
    }
}
