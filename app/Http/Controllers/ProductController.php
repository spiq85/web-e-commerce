<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    // Get api/produts
    public function index()
    {
        try {
            $products = Product::orderBy('created_at', 'asc')->get();
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Mengambil Daftar Produk: ' ,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get api/products/{id}
    public function show($id)
    {
        try {
            // Mencari produk berdasarkan ID
            $products = Product::findOrFail($id);
            return response()->json($products, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Produk Menampilkan Detail Produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST api/products
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'brand' => 'required|string|max:255',
                'stock' => 'required|integer|min:0',
                'description' => 'required|string',
                'image' => 'nullable|url|max:255'
            ]);

            $products = Product::create([
                'product_name' => $request->product_name,
                'price' => $request->price,
                'brand' => $request->brand,
                'stock' => $request->stock,
                'description' => $request->description,
                'image' => $request->image
            ]);

            return response()->json([
                'message' => 'Produk Berhasil Ditambahkan',
                'product' => $products
            ],201);
        } catch(ValidationException $e)
        {
            return response()->json([
                'message' => 'Data Input Tidak Valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Menambahkan Produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PUT api/products/{id}\
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $request->validate([
                'product_name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'brand' => 'required|string|max:255',
                'stok' => 'required|integer|min:0',
                'description' => 'required|tring',
                'image' => 'nullable|url|max:255'
            ]);
            $product->fill($request->all());
            $product->save();

            return response()->json([
                'message' => 'Produk Berhasil Diperbarui',
                'product' => $product
            ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Produk Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Data Input Tidak Valid',
                'errors' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Memperbarui Produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete api/products/{id}
    public function destroy($id)
    {
        try {
            $hasOrderItems = \App\Models\OrderItem::where('id_products', $id)->exist();
            
            if($hasOrderItems) {
                return response()->json([
                    'message' => 'Produk Tidak Dapat Dihapus Karena Sudah ada di Pesanan, Hapus Pesanan Terkait Dulu. '
                ], 409);
            }

            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json([
                'message' => 'Produk Berhasil Dihapus'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Produk Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Menghapus Produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
