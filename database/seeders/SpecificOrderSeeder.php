<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class SpecificOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 2;
        $productId = 1;

        $user = User::find($userId);
        $product = Product::find($productId);

        if (!$user) {
            $this->command->error('User Dengan ID {userId} Tidak Ditemukan. Mohon Pastikan User Tersebut Ada');
            return;
        }

        if (!$product) {
            $this->command->error('Product Dengan ID {productId} Tidak Ditemukan. Mohon Pastikan Product Tersebut Ada');
            return;
        }

        $quantity = 1;
        if($product->stock < $quantity) {
            $this->command->warn("Stok Produk '{$product->product_name}'  Tidak Cukup Untuk Pesanan Ini. Stok Tersedia: {$product->stock}.");

            $quantity = $product->stock > 0 ? $product->stock : 0;
            if($quantity === 0) {
                $this->command->error("Tidak Ada Stok Untuk Produk '{$product->product_name}' Pesanan Tidak Dibuat.");
                return;
            }
        }

        if ($quantity > 0) {
            $product->stock -= $quantity;
            $product->save();
        }

        $totalAmount = $product->price * $quantity;
        $order = Order::create([
            'id_users' => $user->id_users, // Sesuai kolom di migrasi orders
            'order_number' => 'RLX-' . Str::random(8), // Buat nomor order acak
            'total_amount' => $totalAmount,
            'order_date' => now(), // Waktu saat ini
            'status' => 'pending', // Status awal
            'shipping_address' => 'Jl. Contoh Alamat No. 123, Jakarta',
            'payment_method' => 'Transfer Bank',
            'payment_status' => 'pending',
        ]);

        OrderItem::create([
            'id_orders' => $order->id_orders, // Sesuai kolom di migrasi order_items
            'id_products' => $product->id_products, // Sesuai kolom di migrasi order_items
            'quantity' => $quantity,
            'price_at_purchase' => $product->price,
        ]);

        $this->command->info("Order '{$order->order_number}' created for user '{$user->username}' with product '{$product->product_name}'.");

    }
}
