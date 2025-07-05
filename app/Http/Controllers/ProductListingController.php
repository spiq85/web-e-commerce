<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ProductListingController extends Controller
{
    // Menampilkan Product
    public function index(Request $request): View
    {
        $productsQuery = Product::with('category')->orderBy('product_name', 'asc');

        $activeCategory = null;
        if($request->has('category')) {
            $categorySlug = $request->query('category');
            
            $activeCategory = Category::where('slug', $categorySlug)->first();

            if($activeCategory){
                $categoryIds = $this->getCategoryAndChildrenIds($activeCategory);

                $productsQuery->whereIn('id_categories', $categoryIds);
            } else {
                return redirect()->route('products.index')->with('error', 'Kategori Tidak Ditemukan. ');
            }
        }
        $products = $productsQuery->get();

        $mainCategories = Category::whereNull('parent_id')->with('childrenRecursive')->orderBy('category_name', 'asc')->get();
        return view('products.index', compact('products', 'mainCategories', 'activeCategory'));
    }

    private function getCategoriesAndChildrenIds(Category $category): array
    {
        $ids = [$category->id_categories];

        foreach ($category->children as $child){
            $ids = array_merge($ids, $this->getCategoryAndChildrenIds($child));
        }
        return $ids;
    }

    // Menampilkan Detail Product
    public function show($id): View
    {
        try {
            $products = Product::with('category')->findOrFail($id);
            return view('products.show', compact('products'));
        } catch (ModelNotFoundException $e)
        {
            return redirect()->route('products.index')->with('error', 'Product Tidak Ditemukan.');
        }
    }
}
