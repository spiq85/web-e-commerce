<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id_categories';

    protected $fillable = [
        'category_name',
        'slug',
        'description',
        'image_url',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id_categories');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id_categories')->orderBy('category_name', 'asc');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_categories', 'id_categories'); 
    }
}
