{{-- resources/views/admin/users/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-rubylux-light leading-tight">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-rubylux-ruby-dark">
                <div class="p-6 text-rubylux-light">
                    <h3 class="text-xl font-bold mb-4">Daftar Semua User RubyLux</h3>

                    {{-- Pesan Sukses/Error/Info --}}
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
                    @if (session('info')) {{-- Tambahkan ini --}}
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('info') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-rubylux-ruby-dark">
                            <thead class="bg-rubylux-dark">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Username
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Status Aktif
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Terdaftar Sejak
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-rubylux-dark divide-y divide-rubylux-ruby-dark">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->id_users }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->username }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->role }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? 'Aktif' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {{-- Form Aksi Aktivasi/Non-Aktivasi --}}
                                            @if (!$user->is_active)
                                                <form action="{{ route('admin.users.activate', $user->id_users) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin mengaktifkan user ini?');">
                                                    @csrf
                                                    @method('PATCH') {{-- Method spoofing --}}
                                                    <button type="submit" class="text-green-500 hover:text-green-700">Aktifkan</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.deactivate', $user->id_users) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menonaktifkan user ini?');">
                                                    @csrf
                                                    @method('PATCH') {{-- Method spoofing --}}
                                                    <button type="submit" class="text-red-500 hover:text-red-700">Nonaktifkan</button>
                                                </form>
                                            @endif
                                            {{-- Tombol Edit (jika ada) --}}
                                            {{-- <a href="#" class="text-rubylux-ruby hover:text-rubylux-ruby-dark ml-3">Edit</a> --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-rubylux-light">Tidak ada user ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>