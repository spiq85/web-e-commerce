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
        'id_categories',
        'product_name',
        'price',
        'brand',
        'stock',
        'description',
        'image'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_categories', 'id_categories');
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class, 'id_products', 'id_products');
    }
}
