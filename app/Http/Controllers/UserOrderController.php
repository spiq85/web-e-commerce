<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;


class UserOrderController extends Controller
{
    public function placeOrder(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id_products',
                'quantity' => 'required|integer|min:1',
                'shipping_address' => 'required|string|max:255',
                'payment_method' => 'required|string|max:50'
            ]);

            $user = Auth::user();
            $product = Product::findOrFail($request->product_id);

            if($product->stock < $request->quantity) {
                DB::rollback();
                throw ValidationException::withMessages([
                    'quantity' => 'Stok Produk"'. $product->product_name . '"Tidak Mencukupi Stok Tersedia: ' . $product->stock,
                ]);
            }

            $product->stock -= $request->quantity;
            $product->save();

            $totalAmount = $product->price * $request->quantity;

            $order = Order::create([
                'id_users' => $user->id_users,
                'order_number' => 'RLX-' . Str::random(8),
                'total_amount' => $totalAmount,
                'order_date' => now(),
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            OrderItem::create([
                'id_orders' => $order->id_orders,
                'id_products' => $product->id_products,
                'quantity' => $request->quantity,
                'price_at_purchase' => $product->price, 
            ]);

            DB::commit();
            return redirect('/products/' . $product->id_products)->with('success', 'Pesanan Anda Berhasil Dibuat Silahkan Cek Riwayat Anda');
        } catch(ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Produk Tidak Ditemukan')->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal Membuat Pesanan: ' . $e->getMessage())->withInput();
        }
    }
}
