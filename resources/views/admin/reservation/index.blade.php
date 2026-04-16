<x-app-layout>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4">

            {{-- Page Title --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-lg text-gray-800">Daftar Reservasi</h2>
                    <p class="text-xs text-gray-500 mt-0.5 ">Pantau semua janji temu pelanggan dari seluruh cabang.</p>
                </div>
                <button onclick="window.location.reload()"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Refresh
                </button>
            </div>

            

            {{-- STATISTIK — 2x2 Grid di Mobile, 4 kolom di Desktop --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Total</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-0.5">{{ number_format($statsTotal) }}</h3>
                    </div>
                    <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Pending</p>
                        <h3 class="text-2xl font-bold text-yellow-500 mt-0.5">{{ number_format($statsPending) }}</h3>
                    </div>
                    <div class="p-2.5 bg-yellow-50 rounded-xl text-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Approved</p>
                        <h3 class="text-2xl font-bold text-blue-500 mt-0.5">{{ number_format($statsApproved) }}</h3>
                    </div>
                    <div class="p-2.5 bg-blue-50 rounded-xl text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Selesai</p>
                        <h3 class="text-2xl font-bold text-green-500 mt-0.5">{{ number_format($statsCompleted) }}</h3>
                    </div>
                    <div class="p-2.5 bg-green-50 rounded-xl text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">

                {{-- FILTER TOOLBAR --}}
                <form method="GET" action="{{ route('admin.reservation.index') }}"
                    class="p-4 border-b border-gray-100 bg-gray-50/50 space-y-3">
                    {{-- Row 1: Date Range --}}
                    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-3 py-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="border-none text-sm text-gray-700 focus:ring-0 bg-transparent flex-1 min-w-0">
                        <span class="text-gray-300 font-bold">—</span>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="border-none text-sm text-gray-700 focus:ring-0 bg-transparent flex-1 min-w-0">
                    </div>

                    {{-- Row 2: Toko & Status --}}
                    <div class="grid grid-cols-2 gap-2">
                        <select name="store_id"
                            class="border-gray-200 focus:border-indigo-400 focus:ring-indigo-400 rounded-lg text-sm">
                            <option value="">Semua Toko</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id_store }}" {{ request('store_id') == $store->id_store ? 'selected' : '' }}>{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                        <select name="status"
                            class="border-gray-200 focus:border-indigo-400 focus:ring-indigo-400 rounded-lg text-sm">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Approved
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🏁 Selesai
                            </option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>❌ Batal
                            </option>
                        </select>
                    </div>

                    {{-- Row 3: Search & Buttons --}}
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:ring-indigo-400 focus:border-indigo-400"
                                placeholder="Cari nama/HP...">
                        </div>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">Filter</button>
                        <a href="{{ route('admin.reservation.index') }}"
                            class="px-4 py-2 bg-white border border-gray-200 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-50 transition">Reset</a>
                    </div>
                </form>

                {{-- ===== MOBILE: CARD LIST (tampil di < md)=====--}} <div
                    class="block md:hidden divide-y divide-gray-100">
                    @forelse($reservations as $res)
                        @php
                            $statusStyles = [
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'approved' => 'bg-blue-100 text-blue-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'canceled' => 'bg-red-100 text-red-700',
                            ];
                            $statusStyle = $statusStyles[$res->status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <div class="p-4 space-y-3">
                            {{-- Header Card: Store + Status --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 font-bold text-sm flex items-center justify-center flex-shrink-0">
                                        {{ substr($res->store->store_name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-800">{{ $res->store->store_name ?? '-' }}
                                        </div>
                                        <div class="text-[11px] text-gray-400 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($res->booking_date)->translatedFormat('d M Y') }} •
                                            {{ \Carbon\Carbon::parse($res->booking_time)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full {{ $statusStyle }}">
                                    {{ ucfirst($res->status) }}
                                </span>
                            </div>

                            {{-- Info Bar: Customer + Service --}}
                            <div class="bg-gray-50 rounded-lg p-3 grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-[9px] text-gray-400 uppercase font-bold tracking-wider mb-1">Pelanggan
                                    </p>
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $res->customer_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $res->customer_phone }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] text-gray-400 uppercase font-bold tracking-wider mb-1">Layanan</p>
                                    <p class="text-sm font-semibold text-gray-800 truncate">
                                        {{ $res->service->service_name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $res->employee->employee_name ?? 'Bebas Pilih' }}</p>
                                </div>
                            </div>

                            {{-- Action: Update Status --}}
                            <form action="{{ route('admin.reservation.update-status', $res->id_reservation) }}"
                                method="POST" class="flex gap-2">
                                @csrf @method('PUT')
                                <select name="status"
                                    class="flex-1 border-gray-200 rounded-lg text-xs font-semibold focus:ring-indigo-400 focus:border-indigo-400">
                                    <option value="pending" {{ $res->status == 'pending' ? 'selected' : '' }}>⏳ Pending
                                    </option>
                                    <option value="approved" {{ $res->status == 'approved' ? 'selected' : '' }}>✅ Terima
                                    </option>
                                    <option value="completed" {{ $res->status == 'completed' ? 'selected' : '' }}>🏁 Selesai
                                    </option>
                                    <option value="canceled" {{ $res->status == 'canceled' ? 'selected' : '' }}>❌ Batalkan
                                    </option>
                                </select>
                                <button type="submit"
                                    class="px-4 py-2 bg-slate-800 text-white text-xs font-bold rounded-lg hover:bg-slate-900 transition">
                                    Simpan
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="py-14 flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-10 h-10 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="text-sm font-medium">Belum ada data reservasi</p>
                        </div>
                    @endforelse
            </div>

            {{-- ===== DESKTOP: TABLE (tampil di >= md) ===== --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                Toko & Waktu</th>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                Pelanggan</th>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                Detail Layanan</th>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($reservations as $res)
                            @php
                                $colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    'approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'completed' => 'bg-green-100 text-green-700 border-green-200',
                                    'canceled' => 'bg-red-100 text-red-700 border-red-200',
                                ];
                                $statusClass = $colors[$res->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 font-bold text-sm flex-shrink-0">
                                            {{ substr($res->store->store_name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $res->store->store_name ?? '-' }}</div>
                                            <div class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($res->booking_date)->translatedFormat('d M Y') }} •
                                                {{ \Carbon\Carbon::parse($res->booking_time)->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $res->customer_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $res->customer_phone }}</div>
                                    @if($res->customer_email)
                                        <div class="text-xs text-gray-400 truncate max-w-[150px]">{{ $res->customer_email }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $res->service->service_name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ $res->employee->employee_name ?? 'Bebas Pilih' }}</div>
                                    @if($res->notes)
                                        <div
                                            class="mt-1 px-2 py-0.5 bg-gray-50 text-gray-400 text-xs rounded border border-gray-100 inline-block italic">
                                            "{{ Str::limit($res->notes, 25) }}"</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full border {{ $statusClass }}">{{ ucfirst($res->status) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.reservation.update-status', $res->id_reservation) }}"
                                        method="POST">
                                        @csrf @method('PUT')
                                        <select name="status" onchange="this.form.submit()"
                                            class="block w-full text-xs border-gray-200 rounded-lg focus:ring-indigo-400 focus:border-indigo-400 cursor-pointer shadow-sm">
                                            <option value="pending" {{ $res->status == 'pending' ? 'selected' : '' }}>⏳
                                                Pending</option>
                                            <option value="approved" {{ $res->status == 'approved' ? 'selected' : '' }}>✅
                                                Terima</option>
                                            <option value="completed" {{ $res->status == 'completed' ? 'selected' : '' }}>🏁
                                                Selesai</option>
                                            <option value="canceled" {{ $res->status == 'canceled' ? 'selected' : '' }}>❌
                                                Batalkan</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center text-gray-400">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                        </path>
                                    </svg>
                                    <p class="text-sm font-medium">Belum ada data reservasi</p>
                                    <p class="text-xs mt-1">Data reservasi dari semua cabang akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="px-4 py-4 border-t border-gray-100 bg-gray-50/30">
                {{ $reservations->links() }}
            </div>

        </div>
    </div>
    </div>
</x-app-layout>