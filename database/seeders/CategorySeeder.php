<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wanita = Category::firstOrCreate(
            ['category_name' => 'Wanita'],
            ['slug' => Str::slug('Wanita'), 'parent_id' => null]  
        );
        $pria = Category::firstOrCreate(
            ['category_name' => 'Pria'],
            ['slug' => Str::slug('Pria'), 'parent_id' => null]
        );

        Category::firstOrCreate(
            ['category_name' => 'Celana'],
            ['slug' => Str::slug('Celana'), 'parent_id' => $wanita->id_categories]
        );

        Category::firstOrCreate(
            ['category_name' => 'Kemaja Lengan Putih Pendek'],
            ['slug' => Str::slug('Kemeja Lengan Putih Pendek'), 'parent_id' => $pria->id_categories]
        );
    }
}
