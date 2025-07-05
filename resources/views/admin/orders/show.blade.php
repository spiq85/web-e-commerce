<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-rubylux-light leading-tight">
            {{ __('Detail Pesanan') }} (ID: {{ $order->id_orders }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-rubylux-ruby-dark">
                <div class="p-6 text-rubylux-light">
                    <h3 class="text-xl font-bold mb-4">Informasi Pesanan</h3>

                    {{-- Pesan Sukses/Error --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                        <div>
                            <p class="font-semibold">ID Pesanan:</p> <p>{{ $order->id_orders }}</p>
                            <p class="font-semibold">Pelanggan:</p> <p>{{ $order->user->username ?? 'N/A' }} ({{ $order->user->email ?? 'N/A' }})</p>
                            <p class="font-semibold">Tanggal Pesanan:</p> <p>{{ $order->order_date->format('d M Y H:i') }}</p>
                            <p class="font-semibold">Total Harga:</p> <p>{{ number_format($order->total_amount, 2, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Status:</p> <p class="mb-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ['pending'=>'bg-yellow-100 text-yellow-800', 'processing'=>'bg-blue-100 text-blue-800', 'shipped'=>'bg-purple-100 text-purple-800', 'delivered'=>'bg-green-100 text-green-800', 'cancelled'=>'bg-red-100 text-red-800'][$order->status] }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                            <p class="font-semibold">Alamat Pengiriman:</p> <p>{{ $order->shipping_address }}</p>
                            <p class="font-semibold">Metode Pembayaran:</p> <p>{{ $order->payment_method }}</p>
                        </div>
                    </div>

                    <h4 class="text-lg font-bold mb-3">Item Pesanan:</h4>
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-rubylux-ruby-dark">
                            <thead class="bg-rubylux-dark">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Harga Satuan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-rubylux-dark divide-y divide-rubylux-ruby-dark">
                                @forelse ($order->orderItems as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->product_name ?? 'Produk Dihapus' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($item->price_at_purchase, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-rubylux-light">Tidak ada item dalam pesanan ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h4 class="text-lg font-bold mb-3">Ubah Status Pesanan:</h4>
                    <form action="{{ route('admin.orders.updateStatus', $order->id_orders) }}" method="POST">
                        @csrf
                        @method('PATCH') {{-- Method spoofing untuk PATCH --}}

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-rubylux-light">Status Baru</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md shadow-sm form-select">
                                @foreach ($allowedStatuses as $statusOption)
                                    <option value="{{ $statusOption }}" {{ $order->status == $statusOption ? 'selected' : '' }}>
                                        {{ ucfirst($statusOption) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-rubylux-ruby border border-transparent rounded-md font-semibold text-xs text-rubylux-light uppercase tracking-widest hover:bg-rubylux-ruby-dark focus:outline-none focus:ring-2 focus:ring-rubylux-ruby focus:ring-offset-2 transition ease-in-out duration-150">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>