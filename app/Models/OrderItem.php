<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $primaryKey = 'id_order_items';

    protected $fillable = [
        'id_orders',
        'id_products',
        'quantity',
        'price_at_purchase',
    ];

    public function order() {
        return $this->belongsTo(Order::Class, 'id_orders', 'id_orders');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'id_products', 'id_products');
    }
}
