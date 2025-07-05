<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-rubylux-light leading-tight">
            {{ __('Manajemen Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-rubylux-ruby-dark">
                <div class="p-6 text-rubylux-light">
                    <h3 class="text-xl font-bold mb-4">Daftar Semua Pesanan RubyLux</h3>

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

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-rubylux-ruby-dark">
                            <thead class="bg-rubylux-dark">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        ID Order
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        User
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Tanggal Order
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-rubylux-dark divide-y divide-rubylux-ruby-dark">
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->id_orders }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->username ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($order->total_amount, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ ['pending'=>'bg-yellow-100 text-yellow-800', 'processing'=>'bg-blue-100 text-blue-800', 'shipped'=>'bg-purple-100 text-purple-800', 'delivered'=>'bg-green-100 text-green-800', 'cancelled'=>'bg-red-100 text-red-800'][$order->status] }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->order_date->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.orders.show', $order->id_orders) }}" class="text-rubylux-ruby hover:text-rubylux-ruby-dark">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-rubylux-light">Tidak ada pesanan ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>