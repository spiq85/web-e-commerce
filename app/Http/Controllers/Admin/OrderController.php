<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
                $orders = Order::with('user', 'orderItems.product')
                ->orderBy('order_date' , 'desc')
                ->get();
                return view('admin.orders.index', compact('orders'));
        }

        // Menampilkan Detail Order
        public function show($id): view
        {
                try {
                        $orders = Order::with('user', 'orderItems.product')->findOrFail($id);
                        $allowedStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
                        return view('admin.orders.show', compact('orders', 'allowedStatuses'));
                } catch (ModelNotFoundException $e) {
                        return redirect()->route('admin.orders.index')->with('error', 'Order Tidak Ditemukan.');
                }
        }

        // Update Status Order
        public function updateStatus(Request $request, $id): RedirectResponse
        {
                try {
                        $orders = s::findOrFail($id);
                        $request->validate([
                                'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
                        ]);
                        $orders->status = $request->status;
                        $orders->save();
                        return redirect()->route('admin.orders.show', $orders->id_order)->with('success', 'Status Pesanan Berhasil Diupdate!'); 
                } catch (ModelNotFoundException $e) {
                        return redirect()->back()->with('error', 'Pesanan Tidak Ditemukan.');
                } catch (ValidationException $e) {
                        return redirect()->back()->withErros($e->errors())->withInput();
                } catch (\Exception $e) {
                        return redirect()->back()->with('error', 'Gagal Mengupdate Status Pesanan: '. $e->getMessage())->withInput();
                }
        }

}