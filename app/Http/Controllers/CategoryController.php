<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    // Menampilkan Semua Kategori
    public function index()
    {
        try {
            $categories = Category::whereNull('parent_id')
                ->with('children.children')
                ->orderBy('category_name', 'asc')
                ->get();

                return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Mengambil Daftar Kategori',
                'error' => $e->getMessages()
            ], 500);
        }
    }

    // Menampilkan Detail Kategori
    public function show($id)
    {
        try {
            $category = Category::with('children.children')->findOrFail($id);
            return response()->json($category, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Kategori Tidak Ditemukan',
                'error' => $e->getMessages()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Menampilkan Detail Kategori',
                'error' => $e->getMessages()
            ], 500);
        }
    }
}
