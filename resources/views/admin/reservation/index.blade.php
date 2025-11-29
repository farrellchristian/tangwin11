<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Daftar Reservasi') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pantau semua janji temu pelanggan dari seluruh cabang.</p>
            </div>
            
            {{-- Tombol Refresh (Opsional, buat gaya aja) --}}
            <button onclick="window.location.reload()" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Refresh Data
            </button>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ search: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- ALERT SUKSES (Desain Modern) --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 shadow-sm" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div>
                        <span class="font-medium">Berhasil!</span> {{ session('success') }}
                    </div>
                    <button @click="show = false" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- STATISTIK RINGKAS (KARTU) --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Total --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Total Reservasi</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $reservations->total() }}</h3>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-full text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                </div>
                {{-- Pending --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Menunggu Konfirmasi</p>
                        <h3 class="text-2xl font-bold text-yellow-600">
                            {{ $reservations->where('status', 'pending')->count() }}
                        </h3>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-full text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                {{-- Approved --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Siap Datang</p>
                        <h3 class="text-2xl font-bold text-blue-600">
                            {{ $reservations->where('status', 'approved')->count() }}
                        </h3>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                {{-- Completed --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Selesai</p>
                        <h3 class="text-2xl font-bold text-green-600">
                            {{ $reservations->where('status', 'completed')->count() }}
                        </h3>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                
                {{-- TOOLBAR (SEARCH & FILTER) --}}
                <div class="p-5 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    {{-- Search Bar (Client-Side Filter Sederhana atau sekadar UI Placeholder) --}}
                    <div class="relative w-full md:w-1/3">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-indigo-500 focus:border-indigo-500" placeholder="Cari nama pelanggan..." required>
                    </div>

                    {{-- Legend Status (Opsional) --}}
                    <div class="flex gap-2 text-xs text-gray-500">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-400"></span> Pending</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-400"></span> Approved</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-400"></span> Selesai</span>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toko & Waktu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Layanan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($reservations as $res)
                                {{-- Filter Search Sederhana dengan AlpineJS --}}
                                <tr class="hover:bg-gray-50 transition duration-150" 
                                    x-show="!search || '{{ strtolower($res->customer_name) }}'.includes(search.toLowerCase())">
                                    
                                    {{-- 1. TOKO & WAKTU --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                                                {{ substr($res->store->store_name ?? '?', 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $res->store->store_name ?? 'Unknown Store' }}
                                                </div>
                                                <div class="text-sm text-gray-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    {{ \Carbon\Carbon::parse($res->booking_date)->translatedFormat('d M Y') }}
                                                    <span class="mx-1">•</span>
                                                    {{ \Carbon\Carbon::parse($res->booking_time)->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- 2. PELANGGAN --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $res->customer_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $res->customer_phone }}</div>
                                        @if($res->customer_email)
                                            <div class="text-xs text-gray-400 truncate max-w-[150px]">{{ $res->customer_email }}</div>
                                        @endif
                                    </td>

                                    {{-- 3. DETAIL LAYANAN --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-semibold">
                                            {{ $res->service->service_name ?? '-' }}
                                        </div>
                                        <div class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $res->employee->employee_name ?? 'Bebas Pilih' }}
                                        </div>
                                        @if($res->notes)
                                            <div class="mt-1 px-2 py-1 bg-gray-50 text-gray-500 text-xs rounded-md border border-gray-100 inline-block italic">
                                                "{{ Str::limit($res->notes, 25) }}"
                                            </div>
                                        @endif
                                    </td>

                                    {{-- 4. STATUS --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $colors = [
                                                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                'approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'completed' => 'bg-green-100 text-green-700 border-green-200',
                                                'canceled' => 'bg-red-100 text-red-700 border-red-200',
                                            ];
                                            $statusClass = $colors[$res->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $statusClass }}">
                                            {{ ucfirst($res->status) }}
                                        </span>
                                    </td>

                                    {{-- 5. AKSI (DROPDOWN STATUS) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('admin.reservation.update-status', $res->id_reservation) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="relative">
                                                <select name="status" onchange="this.form.submit()" 
                                                    class="block w-full pl-3 pr-8 py-1.5 text-xs border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm transition ease-in-out duration-150 cursor-pointer hover:border-gray-400">
                                                    <option value="pending" {{ $res->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                                    <option value="approved" {{ $res->status == 'approved' ? 'selected' : '' }}>✅ Terima</option>
                                                    <option value="completed" {{ $res->status == 'completed' ? 'selected' : '' }}>🏁 Selesai</option>
                                                    <option value="canceled" {{ $res->status == 'canceled' ? 'selected' : '' }}>❌ Batalkan</option>
                                                </select>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            <p class="text-base font-medium">Belum ada data reservasi</p>
                                            <p class="text-sm mt-1">Data reservasi dari semua cabang akan muncul di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $reservations->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>