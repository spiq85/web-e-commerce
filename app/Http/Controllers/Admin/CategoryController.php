<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    // Menampilkan Daftar Kategori
    public function index(): View
    {
        $parentCategoriesForView = Category::whereNull('parent_id')
            ->with('children.childrenRecursive')
            ->orderBy('category_name', 'desc') 
            ->get();
        return view('admin.categories.index', compact('parentCategoriesForView'));
    }

    // Menampilkan Form Tambah Kategori
    public function create(): View
    {
        $parentCategories = Category::orderBy('category_name', 'asc')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    // Menyimpan Kategori Baru
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'category_name' => 'required|string|max:100|unique:categories,category_name',
                'parent_id' => 'nullable|integer|exists:categories,id_categories',
                'description' => 'nullable|string',
                'image_url' => 'nullable|url|max:255',
            ]);

            $slug = Str::slug($request->category_name); 
            Category::create([
                'category_name' => $request->category_name,
                'slug' => $slug,
                'parent_id' => $request->parent_id,
                'description' => $request->description,
                'image_url' => $request->image_url,
            ]);
            // PERBAIKI DI SINI: 'succes' menjadi 'success'
            return redirect()->route('admin.categories.index')->with('success', 'Kategori Berhasil Ditambahkan!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Menambahkan Kategori: ' . $e->getMessage())->withInput();
        }
    }

    // Menampilkan Form Edit Kategori
    public function edit($id): View
    {
        try {
            $category = Category::findOrFail($id);
            $parentCategories = Category::where('id_categories', '!=', $id)
                ->orderBy('category_name', 'asc')
                ->get();
            return view('admin.categories.edit', compact('category', 'parentCategories')); 
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.categories.index')->with('error', 'Kategori Tidak Ditemukan.');
        }
    }

    // Update Kategori
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $category = Category::findOrFail($id); 
            $request->validate([
                'category_name' => 'required|string|max:100|unique:categories,category_name,' . $id . ',id_categories',
                'parent_id' => 'nullable|integer|exists:categories,id_categories',
                'description' => 'nullable|string',
                'image_url' => 'nullable|url|max:255',
            ]);

            if ($request->parent_id == $category->id_categories) { 
                throw ValidationException::withMessages([
                    'parent_id' => 'Kategori Tidak Bisa Menjadi Induk Diri Sendiri',
                ]);
            }

            $slug = Str::slug($request->category_name);

            $category->fill([ 
                'category_name' => $request->category_name,
                'slug' => $slug,
                'parent_id' => $request->parent_id,
                'description' => $request->description,
                'image_url' => $request->image_url, 
            ]);
            $category->save(); // Simpan perubahan
            return redirect()->route('admin.categories.index')->with('success', 'Kategori Berhasil Diupdate!'); 
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Kategori Tidak Ditemukan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Mengupdate Kategori: ' . $e->getMessage())->withInput();
        }
    }

    // Menghapus Kategori
    public function destroy($id): RedirectResponse
    {
        try {
            $category = Category::findOrFail($id);
            if ($category->products()->exists()) { 
                return redirect()->back()->with('error', 'Kategori Tidak Dapat Dihapus Karena Masih Memiliki Produk');
            }
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Kategori Berhasil Dihapus');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Kategori Tidak Ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Menghapus Kategori: ' . $e->getMessage());
        }
    }
}