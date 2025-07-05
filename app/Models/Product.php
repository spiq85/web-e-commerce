<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id_products';

    protected $fillable = [
        'product_name',
        'price',
        'brand',
        'stock',
        'description',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_categories', 'id_categories');
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class, 'id_products', 'id_products');
    }
}
