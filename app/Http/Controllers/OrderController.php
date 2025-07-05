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

class OrderController extends Controller
{
    // POST /api/orders
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.id_products' => 'required|exists:products,id_products',
                'items.*.quantity' => 'required|integer|min:1',
                'shipping_address' => 'required|string|max:255',
                'payment_method' => 'required|string|max:50',
            ]);
            // Ambil User Yang Sedang Login
            $user = Auth::user();

            // Hitung Total Amount dan Stok Produk
            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id_products']);

                if($product->stock < $item['quantity']) {
                    DB::rollback();
                    return response()->json([
                        'message' => 'Stock Produk "' . $product->product_name . '" Stok Produk' . $product->stock . ' Tidak Cukup untuk Pesanan Anda.'
                    ], 400);
                }

                // Kurangi Stok Produk
                $product->stock -= $item['quantity'];
                $product->save();

                // Hitung Total Amount
                $subTotal = $product->price * $item['quantity'];
                $totalAmount += $subTotal;

                // Siapkan Data Untuk Order Item
                $orderItemsData[] = [
                    'id_products' => $product->id_products,
                    'quantity' => $item['quantity'],
                    'price_per_unit' => $Product->price,
                    'sub_total' => $subTotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // Buat Entri Order
            $order = Order::create([
                'id_users' => $user->id_users,
                'total_amount' => $totalAmount,
                'order_date' => now(),
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
            ]);
            // Buat Entri Order Item
            $order->orderitems()->createMany($orderItemsData);
            DB::commit();


            return response()->json([
                'message' => 'Pesanan Berhasil Dibuat',
                'order' => $order->load('orderitems.product')
            ],201);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Data Input Tidak Valid',
                'errors' => $e->errors() 
            ], 422);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Produk Tidak Ditemukan'
            ], 404);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Gagal Membuat Pesanan: ',
                'error' => $e->getMessage() 
            ], 500);
        }
    }

    // Get /api/orders
    // User : dapat melihat daftar pesanan mereka
    // Admin : dapat melihat semua pesanan
    public function index(Request $reques)
    {
        try {
            $user = Auth::user();
            if ($user->role === 'admin') {
                $orders = Order::with('user', 'orderItems.product')
                ->orderBy('order_date', 'desc')
                ->get();
            } else {
                $orders = Order::where('id_users', $user->id_users)
                ->with('user', 'orderItems.product')
                ->orderBy('order_date', 'desc')
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

    // Get api/orders/{id}
    // Melihat Detail Pesanan
    public function show($id)
    {
        try{

            $user = Auth::user();
            $order = Order::with('user', 'orderItems.product')->findOrFail($id);

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
    public function updateStatus(Request $request, $id) {
        try {
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            ]);

            $order = Order::findOrFail($id);
            $order->status = $request->status;
            $order->save();

            return response()->json([
                'message' => 'Status Pesanan Berhasil Diupdate',
                'order' => $order->load('user', 'orderItems.product')
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Pesanan Tidak Ditemukan',
                'errors' => $e->errors()
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
