<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category; // Import model Category
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage; // Import Storage facade (tetap ada untuk jaga-jaga, meski nanti kita pakai unlink)
use Illuminate\Support\Str; // Import Str helper untuk nama file acak

class ProductController extends Controller
{
    // Menampilkan daftar semua produk
    public function index(): View
    {
        $products = Product::orderBy('id_products', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    // Menampilkan form untuk membuat produk baru
    public function create(): View
    {
        $categories = Category::whereNull('parent_id')
        ->with('childrenRecursive')
        ->orderBy('category_name', 'asc')
        ->get();
        return view('admin.products.create', compact('categories'));
    }

    // Menyimpan produk baru dari form web
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validasi Input
            $request->validate([
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'brand' => 'nullable|string|max:100',
                'stock' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'id_categories' => 'required|exists:categories,id_categories', 
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePathInPublic = null; // Ini yang akan disimpan di DB

            if ($request->hasFile('image')) {
                $uploadedFile = $request->file('image');
                $filename = Str::random(40) . '.' . $uploadedFile->getClientOriginalExtension(); // Nama file acak + ekstensi asli

                // Simpan file LANGSUNG ke public/uploads/products
                $uploadedFile->move(public_path('uploads/products'), $filename);

                $imagePathInPublic = 'uploads/products/' . $filename; // Path relatif dari folder public untuk disimpan di DB
            }

            // Buat produk baru
            Product::create([
                'product_name' => $request->product_name,
                'price' => $request->price,
                'brand' => $request->brand,
                'stock' => $request->stock,
                'description' => $request->description,
                'id_categories' => $request->id_categories,
                'image' => $imagePathInPublic,
            ]);

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())->withInput();
        }
    }

    // Menampilkan form untuk mengedit produk
    public function edit($id): View
    {
        try {
            $product = Product::findOrFail($id);
            $categories = Category::whereNull('parent_id')
            ->with('childrenRecursive')
            ->orderBy('category_name', 'asc')
            ->get();
            return view('admin.products.edit', compact('product', 'categories'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.products.index')->with('error', 'Produk tidak ditemukan.');
        }
    }

    // Mengupdate produk dari form web
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $product = Product::findOrFail($id);

            // Validasi Input (termasuk file gambar dan id_categories)
            $request->validate([
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'brand' => 'nullable|string|max:100',
                'stock' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'id_categories' => 'required|exists:categories,id_categories',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePathInPublic = $product->image; // Default ke gambar lama

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada dan itu adalah gambar dari system public/uploads
                if ($product->image && file_exists(public_path($product->image))) {
                    unlink(public_path($product->image)); // Menghapus file fisik lama
                }

                $uploadedFile = $request->file('image');
                $filename = Str::random(40) . '.' . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->move(public_path('uploads/products'), $filename); // <<< Simpan baru

                $imagePathInPublic = 'uploads/products/' . $filename; // Path baru untuk DB
            }

            $product->fill([
                'product_name' => $request->product_name,
                'price' => $request->price,
                'brand' => $request->brand,
                'stock' => $request->stock,
                'description' => $request->description,
                'id_categories' => $request->id_categories,
                'image' => $imagePathInPublic, // Update path gambar
            ]);
            $product->save();

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diupdate!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.products.index')->with('error', 'Produk tidak ditemukan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate produk: ' . $e->getMessage())->withInput();
        }
    }

    // Menghapus produk dari web
    public function destroy($id): RedirectResponse
    {
        try {
            $product = Product::findOrFail($id);

            // Cek apakah produk ini masih ada di order_items
            if (\App\Models\OrderItem::where('id_products', $id)->exists()) {
                return redirect()->back()->with('error', 'Produk tidak dapat dihapus karena sudah ada di pesanan.');
            }

            // Hapus gambar terkait jika ada dan itu gambar dari system public/uploads
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image)); // Menghapus file fisik
            }

            $product->delete();
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}