<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User; // Import model User (untuk relasi di view)
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    // Menampilkan Semua Daftar Order
    public function index(): View
    {
        $orders = Order::with('user', 'items.product') // <<< UBAH 'orderItems' menjadi 'items' (sesuai nama relasi di Order.php)
                       ->orderBy('created_at', 'desc')
                       ->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Menampilkan Detail Order
    public function show($id): View
    {
        try {
            $order = Order::with('user', 'items.product')->findOrFail($id); 
            $allowedStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
            return view('admin.orders.show', compact('order', 'allowedStatuses'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.orders.index')->with('error', 'Pesanan Tidak Ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')->with('error', 'Gagal Mengambil Detail Pesanan: ' . $e->getMessage());
        }
    }

    // Update Status Order
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        try {
            $order = Order::findOrFail($id);
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            ]);
            $order->status = $request->status;
            $order->save();
            // PERBAIKI: Menggunakan $order->id_orders untuk redirect
            return redirect()->route('admin.orders.show', $order->id_orders)->with('success', 'Status Pesanan Berhasil Diupdate!');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Pesanan Tidak Ditemukan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Mengupdate Status Pesanan: ' . $e->getMessage())->withInput();
        }
    }
}