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
        'order_number',
        'total_amount',
        'order_date',
        'status',
        'shipping_address',
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }

    public function items() {
        return $this->hasMany(OrderItem::class, 'id_orders', 'id_orders');
    }
}
