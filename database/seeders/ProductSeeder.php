<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'id_products' => 1,
            'product_name' => 'Kemeja Putih',
            'price' => 100.50,
            'brand' => 'uniqlo',
            'id_categories' => 2,
            'stock' => 10,
            'description' => 'Terbuat Dari Bahan Premium',
        ]);
    }
}
