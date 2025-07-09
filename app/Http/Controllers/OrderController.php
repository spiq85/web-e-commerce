<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem; 
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Str;


class OrderController extends Controller
{
    // POST /api/orders
    // Fungsi ini yang akan dipanggil oleh aplikasi Flutter untuk membuat pesanan
    public function store(Request $request)
    {
        DB::beginTransaction(); // Memulai transaksi database

        try {
            // 1. Validasi Input dari Frontend (Flutter)
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.id_products' => 'required|integer|exists:products,id_products', // id_products harus ada di tabel products
                'items.*.quantity' => 'required|integer|min:1',
                'shipping_address' => 'required|string|max:255',
                'payment_method' => 'required|string|max:50',
            ]);

            // 2. Ambil User Yang Sedang Login (dari token Sanctum)
            $user = Auth::user();

            // 3. Hitung Total Amount dan Cek Stok Produk
            $totalAmount = 0;
            $orderItemsData = []; // Untuk menyimpan data item pesanan

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id_products']); // Cari produk, jika tidak ada lempar 404

                if ($product->stock < $item['quantity']) {
                    DB::rollback(); // Batalkan transaksi jika stok tidak cukup
                    return response()->json([
                        'message' => 'Stok Produk "' . $product->product_name . '" tidak cukup untuk pesanan Anda. Stok tersedia: ' . $product->stock
                    ], 400); // HTTP status 400 Bad Request
                }

                // Kurangi Stok Produk
                $product->stock -= $item['quantity'];
                $product->save(); // Simpan perubahan stok ke database

                // Hitung subtotal untuk item ini
                $subTotal = $product->price * $item['quantity'];
                $totalAmount += $subTotal;

                // Siapkan Data Untuk Order Item (Pastikan nama kolom sesuai fillable di OrderItem.php)
                $orderItemsData[] = [
                    'id_products' => $product->id_products,
                    'quantity' => $item['quantity'],
                    'price_at_purchase' => $product->price, // Menggunakan harga produk saat ini
                    'created_at' => now(), // Waktu pembuatan item
                    'updated_at' => now(), // Waktu update item
                ];
            }

            // 4. Buat Entri di Tabel 'orders'
            $order = Order::create([
                'id_users' => $user->id_users, // ID user yang sedang login
                'order_number' => 'RLX-' . Str::random(8), // Generate nomor order acak
                'total_amount' => $totalAmount,
                'order_date' => now(), // Waktu saat ini
                'status' => 'pending', // Status awal pesanan
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending', // Status pembayaran awal
            ]);

            // 5. Buat Entri di Tabel 'order_items'
            // Menggunakan relasi 'items' (sesuai nama method di Model Order.php)
            $order->items()->createMany($orderItemsData);

            DB::commit(); // Komit transaksi jika semua berhasil

            return response()->json([
                'message' => 'Pesanan Berhasil Dibuat!',
                'order' => $order->load('user', 'items.product') // Eager load relasi 'user' dan 'items' beserta 'product' di dalamnya
            ], 201); // HTTP status 201 Created

        } catch (ValidationException $e) {
            DB::rollback(); // Batalkan transaksi jika validasi gagal
            return response()->json([
                'message' => 'Data Input Tidak Valid',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            DB::rollback(); // Batalkan transaksi jika produk tidak ditemukan
            return response()->json([
                'message' => 'Produk Tidak Ditemukan'
            ], 404);
        } catch (\Exception $e) {
            DB::rollback(); // Batalkan transaksi untuk error lainnya
            return response()->json([
                'message' => 'Gagal Membuat Pesanan: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /api/orders
    // User : dapat melihat daftar pesanan mereka
    // Admin : dapat melihat semua pesanan
    public function index(Request $request) // <<< PERBAIKI: $reques menjadi $request
    {
        try {
            $user = Auth::user();
            $orders = null;

            if ($user->role === 'admin') {
                $orders = Order::with('user', 'items.product') // <<< PERBAIKI 'orderItems' menjadi 'items'
                               ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal order dibuat
                               ->get();
            } else {
                $orders = Order::where('id_users', $user->id_users)
                               ->with('user', 'items.product') // <<< PERBAIKI 'orderItems' menjadi 'items'
                               ->orderBy('created_at', 'desc')
                               ->get();
            }
            return response()->json($orders, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Mengambil Daftar Pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /api/orders/{id}
    // Melihat Detail Pesanan
    public function show($id)
    {
        try {
            $user = Auth::user();
            $order = Order::with('user', 'items.product')->findOrFail($id); // <<< PERBAIKI 'orderItems' menjadi 'items'

            if ($user->role !== 'admin' && $order->id_users !== $user->id_users) {
                return response()->json([
                    'message' => 'Anda Tidak Memiliki Akses Untuk Melihat Pesanan Ini'
                ], 403);
            }
            return response()->json($order, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Pesanan Tidak Ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Mengambil Detail Pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // PATCH /api/admin/orders/{id}/status
    // Admin mengubah status pesanan
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            ]);

            $order = Order::findOrFail($id);
            $order->status = $request->status;
            $order->save();

            // PERBAIKI: Menggunakan $order->id_orders untuk redirect (ini untuk API, jadi load kembali relasi)
            return response()->json([
                'message' => 'Status Pesanan Berhasil Diupdate',
                'order' => $order->load('user', 'items.product') // <<< PERBAIKI 'orderItems' menjadi 'items'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Pesanan Tidak Ditemukan'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Data Input Tidak Valid'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Mengupdate Status Pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}