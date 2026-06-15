<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-lg sm:text-2xl text-gray-800 leading-tight">
                {{ __('Riwayat Transaksi') }}
            </h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Rekap harian untuk closing kasir.</p>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-3 sm:space-y-4">

            {{-- 1. SHIFT SUMMARY CARDS --}}
            {{-- Baris 1: Transaksi, Cash, QRIS, Transfer --}}
            {{-- 1. SHIFT SUMMARY CARDS --}}
            {{-- 1. SHIFT SUMMARY CARDS --}}
            {{-- Baris 1: Transaksi, Cash, QRIS, Transfer --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">

                {{-- Card: Transaksi --}}
                <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-[9px] sm:text-[10px] font-bold text-indigo-500 uppercase tracking-wider">Transaksi</p>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-gray-800">{{ $summary['total_trx'] }}</h3>
                    <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5">Total nota/struk hari ini</p>
                    <div class="absolute right-0 top-0 h-full w-12 bg-gradient-to-l from-indigo-50/60 to-transparent"></div>
                </div>

                {{-- Card: Cash --}}
                <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-7 h-7 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <p class="text-[9px] sm:text-[10px] font-bold text-green-600 uppercase tracking-wider">Cash</p>
                    </div>
                    <div class="flex items-baseline gap-1.5 flex-wrap">
                        <h3 class="text-sm sm:text-xl font-extrabold text-gray-800">
                            <span class="text-[10px] font-normal text-gray-400">Rp </span>{{ number_format($summary['total_cash'], 0, ',', '.') }}
                        </h3>
                        <span class="text-[9px] font-semibold text-green-500 bg-green-50 border border-green-100 rounded-full px-1.5 py-0.5">({{ $summary['total_cash_count'] }} trx)</span>
                    </div>
                    <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5">Pendapatan cash hari ini</p>
                    <div class="absolute right-0 top-0 h-full w-12 bg-gradient-to-l from-green-50/60 to-transparent"></div>
                </div>

                {{-- Card: QRIS --}}
                <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-7 h-7 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <p class="text-[9px] sm:text-[10px] font-bold text-purple-600 uppercase tracking-wider">QRIS</p>
                    </div>
                    <div class="flex items-baseline gap-1.5 flex-wrap">
                        <h3 class="text-sm sm:text-xl font-extrabold text-gray-800">
                            <span class="text-[10px] font-normal text-gray-400">Rp </span>{{ number_format($summary['total_digital'], 0, ',', '.') }}
                        </h3>
                        <span class="text-[9px] font-semibold text-purple-500 bg-purple-50 border border-purple-100 rounded-full px-1.5 py-0.5">({{ $summary['total_qris_count'] }} trx)</span>
                    </div>
                    <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5">Masuk ke rekening</p>
                    <div class="absolute right-0 top-0 h-full w-12 bg-gradient-to-l from-purple-50/60 to-transparent"></div>
                </div>


            </div>

            {{-- Baris 2: Total Penjualan Produk, Total Pengeluaran, Hasil Cash Kasir, Total Pemasukan --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 sm:gap-4">

                {{-- Card: Total Penjualan Produk --}}
                <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <p class="text-[9px] sm:text-[10px] font-bold text-amber-600 uppercase tracking-wider">Penjualan Produk</p>
                    </div>
                    <div class="flex items-baseline gap-1.5 flex-wrap">
                        <h3 class="text-sm sm:text-xl font-extrabold text-gray-800">
                            <span class="text-[10px] font-normal text-gray-400">Rp </span>{{ number_format($summary['total_product_sales'], 0, ',', '.') }}
                        </h3>
                        <span class="text-[9px] font-semibold text-amber-600 bg-amber-50 border border-amber-100 rounded-full px-1.5 py-0.5">({{ $summary['total_product_sales_count'] }} item)</span>
                    </div>
                    <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5">Produk terjual hari ini</p>
                    <div class="absolute right-0 top-0 h-full w-12 bg-gradient-to-l from-amber-50/60 to-transparent"></div>
                </div>

                {{-- Card: Total Pengeluaran --}}
                <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-red-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-[9px] sm:text-[10px] font-bold text-red-500 uppercase tracking-wider">Total Pengeluaran</p>
                    </div>
                    <div class="flex items-baseline gap-1.5 flex-wrap">
                        <h3 class="text-sm sm:text-xl font-extrabold text-red-600">
                            <span class="text-[10px] font-normal text-gray-400">Rp </span>{{ number_format($summary['total_expenses'], 0, ',', '.') }}
                        </h3>
                        @if(($summary['total_expenses_count'] ?? 0) > 0)
                        <span class="text-[9px] font-semibold text-red-500 bg-red-50 border border-red-100 rounded-full px-1.5 py-0.5">({{ $summary['total_expenses_count'] }} bon)</span>
                        @endif
                    </div>
                    <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5">Bon & tips hari ini</p>
                    @if(($summary['total_tips'] ?? 0) > 0)
                    <p class="text-[9px] text-orange-400 mt-0.5">Tips: Rp {{ number_format($summary['total_tips'], 0, ',', '.') }}</p>
                    @endif
                    <div class="absolute right-0 top-0 h-full w-12 bg-gradient-to-l from-red-50/60 to-transparent"></div>
                </div>

                {{-- Card: Hasil Cash Kasir --}}
                @php $hasil_cash_kasir = $summary['total_cash'] - $summary['total_expenses']; @endphp
                <div class="bg-gradient-to-br from-indigo-500 to-violet-600 p-3 sm:p-4 rounded-xl shadow-md relative overflow-hidden group hover:shadow-lg transition-shadow">
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <p class="text-[9px] sm:text-[10px] font-bold text-indigo-100 uppercase tracking-wider">Hasil Cash Kasir</p>
                    </div>
                    <h3 class="text-sm sm:text-xl font-extrabold text-white">
                        <span class="text-[10px] font-normal text-indigo-200">Rp </span>{{ number_format(max(0, $hasil_cash_kasir), 0, ',', '.') }}
                    </h3>
                    <p class="text-[9px] sm:text-xs text-indigo-200 mt-0.5">Cash - Bon - Tips</p>
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -right-1 -top-4 w-14 h-14 bg-white/5 rounded-full"></div>
                </div>

                {{-- Card: Total Pemasukan --}}
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-3 sm:p-4 rounded-xl shadow-md relative overflow-hidden group hover:shadow-lg transition-shadow">
                    <div class="flex items-center gap-2 mb-1.5">
                        <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-[9px] sm:text-[10px] font-bold text-emerald-100 uppercase tracking-wider">Total Pemasukan</p>
                    </div>
                    <h3 class="text-sm sm:text-xl font-extrabold text-white">
                        <span class="text-[10px] font-normal text-emerald-200">Rp </span>{{ number_format($summary['total_income'], 0, ',', '.') }}
                    </h3>
                    <p class="text-[9px] sm:text-xs text-emerald-200 mt-0.5">Cash + QRIS</p>
                    <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -right-1 -top-4 w-14 h-14 bg-white/5 rounded-full"></div>
                </div>
            </div>

            {{-- 2. FILTER & PENCARIAN --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-3 bg-gray-50/50">
                    <form method="GET" action="{{ route('pos.history') }}" class="flex flex-col sm:flex-row gap-2">
                        <div class="relative w-full sm:w-[160px] flex-shrink-0">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="date" name="date" value="{{ $date }}"
                                class="block w-full pl-9 pr-3 py-2 border border-gray-200 rounded-md text-sm bg-white focus:ring-indigo-500 focus:border-indigo-500"
                                onchange="this.form.submit()">
                        </div>
                        <div class="flex flex-1 gap-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="block w-full pl-9 pr-3 py-2 border border-gray-200 rounded-md text-sm bg-white placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Cari Nama Capster...">
                            </div>
                            <button type="submit" class="hidden sm:flex items-center px-3 py-2 bg-indigo-600 text-white text-xs font-medium rounded-md hover:bg-indigo-700 transition">Cari</button>
                            @if(request('search') || request('date') != date('Y-m-d'))
                                <a href="{{ route('pos.history') }}" class="flex-shrink-0 py-2 px-2.5 bg-white border border-gray-200 text-red-500 text-xs font-semibold rounded-md hover:bg-red-50 transition flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    <span class="hidden sm:inline">Reset</span>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- 3. RINCIAN PER CAPSTER --}}
            @forelse($groupedByCapster as $capsterData)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">

                    {{-- Header Capster — compact --}}
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/70 flex items-center justify-between gap-3">
                        {{-- Avatar + Name --}}
                        <div class="flex items-center gap-2.5">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm ring-1 ring-indigo-200 flex-shrink-0">
                                {{ substr($capsterData['employee']->employee_name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-800 leading-tight">{{ $capsterData['employee']->employee_name ?? 'Unknown' }}</h3>
                                <p class="text-[10px] text-gray-400">Capster</p>
                            </div>
                        </div>

                        {{-- Mini Summary Badges --}}
                        <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap justify-end">
                            <div class="bg-white rounded px-2 py-1 border border-gray-200 text-center" title="Jumlah sesi layanan capster ini (1 transaksi bisa melibatkan 2 capster)">
                                <p class="text-[8px] font-semibold text-gray-400 uppercase">Sesi Potong</p>
                                <p class="text-sm font-extrabold text-indigo-600">{{ $capsterData['total_trx'] }}</p>
                            </div>
                            @if($capsterData['cash_count'] > 0)
                            <div class="bg-green-50 rounded px-2 py-1 border border-green-200 text-center">
                                <p class="text-[8px] font-semibold text-green-500 uppercase">Cash</p>
                                <p class="text-sm font-extrabold text-green-600">{{ $capsterData['cash_count'] }}x</p>
                            </div>
                            @endif
                            @if($capsterData['qris_count'] > 0)
                            <div class="bg-purple-50 rounded px-2 py-1 border border-purple-200 text-center">
                                <p class="text-[8px] font-semibold text-purple-500 uppercase">QRIS</p>
                                <p class="text-sm font-extrabold text-purple-600">{{ $capsterData['qris_count'] }}x</p>
                            </div>
                            @endif
                            @if($capsterData['total_product_qty'] > 0)
                            <div class="bg-white rounded px-2 py-1 border border-amber-200 text-center">
                                <p class="text-[8px] font-semibold text-amber-500 uppercase">Produk</p>
                                <p class="text-sm font-extrabold text-amber-600">{{ $capsterData['total_product_qty'] }}</p>
                            </div>
                            @endif
                            @if($capsterData['total_food_qty'] > 0)
                            <div class="bg-white rounded px-2 py-1 border border-orange-200 text-center">
                                <p class="text-[8px] font-semibold text-orange-500 uppercase">Makanan</p>
                                <p class="text-sm font-extrabold text-orange-600">{{ $capsterData['total_food_qty'] }}</p>
                            </div>
                            @endif
                            @if($capsterData['total_tips'] > 0)
                            <div class="bg-white rounded px-2 py-1 border border-green-200 text-center">
                                <p class="text-[8px] font-semibold text-green-500 uppercase">Tip</p>
                                <p class="text-xs font-bold text-green-600"><span class="text-[9px] font-normal text-gray-400">Rp</span> {{ number_format($capsterData['total_tips'], 0, ',', '.') }}</p>
                            </div>
                            @endif
                            @if($capsterData['total_expenses'] > 0)
                            <div class="bg-white rounded px-2 py-1 border border-red-200 text-center">
                                <p class="text-[8px] font-semibold text-red-400 uppercase">Keluar</p>
                                <p class="text-xs font-bold text-red-600">Rp {{ number_format($capsterData['total_expenses'], 0, ',', '.') }}</p>
                            </div>
                            @endif
                            <div class="bg-white rounded px-2 py-1 border border-gray-200 text-center">
                                <p class="text-[8px] font-semibold text-gray-400 uppercase">Total</p>
                                <p class="text-xs font-bold text-gray-800"><span class="text-[9px] font-normal text-gray-400">Rp</span> {{ number_format($capsterData['total_amount'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- ===== MOBILE VIEW ===== --}}
                    <div class="block md:hidden">
                        {{-- Mobile: Transaksi --}}
                        <div class="px-3 pt-3 pb-1 border-b border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Riwayat Transaksi</p>
                        </div>
                        {{-- Mini Table Header --}}
                        <div class="grid grid-cols-12 px-3 py-1.5 bg-gray-50 border-b border-gray-100 text-[9px] font-bold text-gray-400 uppercase tracking-wider">
                            <div class="col-span-1">No</div>
                            <div class="col-span-3">Waktu</div>
                            <div class="col-span-3">Total</div>
                            <div class="col-span-2">Tips</div>
                            <div class="col-span-2">Metode</div>
                            <div class="col-span-1 text-right">Aksi</div>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($capsterData['transactions'] as $index => $t)
                            @php $mn = $t->paymentMethod->method_name ?? '-'; @endphp
                            <div class="grid grid-cols-12 px-3 py-2 items-center gap-0.5 hover:bg-indigo-50/40 transition">
                                <div class="col-span-1 text-[9px] text-gray-400">{{ $index + 1 }}</div>
                                <div class="col-span-3">
                                    <span class="text-[10px] font-semibold text-gray-700 leading-tight block">{{ \Carbon\Carbon::parse($t->transaction_date)->format('H:i') }}</span>
                                    <span class="text-[8px] text-gray-400 leading-tight block">{{ \Carbon\Carbon::parse($t->transaction_date)->format('d M') }}</span>
                                </div>
                                <div class="col-span-3">
                                    <span class="text-[10px] font-bold text-gray-800">Rp {{ number_format($t->display_amount ?? $t->total_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="col-span-2">
                                    @if(($t->display_tips ?? $t->tips ?? 0) > 0)
                                        <span class="text-[9px] font-semibold text-green-600">{{ number_format($t->display_tips ?? $t->tips, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-[9px] text-gray-300">-</span>
                                    @endif
                                </div>
                                <div class="col-span-2">
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full
                                        {{ $mn === 'Cash' ? 'bg-green-100 text-green-700' : ($mn === 'Qris' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600') }}">
                                        {{ $mn === 'Qris' ? 'QRIS' : $mn }}
                                    </span>
                                </div>
                                <div class="col-span-1 flex justify-end flex-col sm:flex-row items-end sm:items-center gap-1">
                                    <button onclick="openDetailModal({{ $t->id_transaction }})"
                                        class="p-1 bg-white border border-slate-200 rounded text-gray-500 hover:text-indigo-600 transition flex-shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button onclick="window.open('{{ route('pos.print-struk', $t->id_transaction) }}', '_blank', 'width=400,height=600')"
                                        class="p-1 bg-white border border-slate-200 rounded text-indigo-500 hover:text-indigo-700 transition flex-shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($capsterData['total_tips'] > 0)
                        <div class="px-3 py-2 bg-green-50 border-t border-green-100 flex justify-between">
                            <span class="text-[10px] font-bold text-gray-500">Total Tips</span>
                            <span class="text-[10px] font-black text-green-600">Rp {{ number_format($capsterData['total_tips'], 0, ',', '.') }}</span>
                        </div>
                        @endif

                        {{-- Mobile: Pengeluaran --}}
                        @if($capsterData['expenses']->isNotEmpty())
                        <p class="px-3 pt-3 pb-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-t border-gray-100">Riwayat Pengeluaran</p>
                        <div class="divide-y divide-gray-100">
                            @foreach($capsterData['expenses'] as $expense)
                            <div class="px-3 py-2.5 flex justify-between items-center hover:bg-red-50/40 transition">
                                <div class="flex-1 pr-2">
                                    <div class="text-xs font-semibold text-gray-700 line-clamp-1">{{ $expense->description }}</div>
                                    <div class="text-[9px] text-gray-400">{{ \Carbon\Carbon::parse($expense->expense_date)->format('H:i') }} WIB</div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-xs font-black text-red-600">-Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                    <button onclick="openPosExpenseModal('{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y H:i') }}', `{{ addslashes($expense->description) }}`, '{{ number_format($expense->amount, 0, ',', '.') }}')" class="p-1.5 bg-white border border-slate-200 rounded text-gray-500 hover:text-indigo-600 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="px-3 py-2 bg-red-50 border-t border-red-100 flex justify-between">
                            <span class="text-[10px] font-bold text-gray-500">Total Pengeluaran</span>
                            <span class="text-[10px] font-black text-red-600">Rp {{ number_format($capsterData['total_expenses'], 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>


                    {{-- ===== DESKTOP: RIWAYAT TRANSAKSI ===== --}}
                    <div class="hidden md:block">
                        <div class="px-4 py-2 bg-gray-50 border-b border-gray-100">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Riwayat Transaksi</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider w-10">No</th>
                                        <th class="px-4 py-2 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider w-28">Waktu</th>
                                        <th class="px-4 py-2 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider">Total</th>
                                        <th class="px-4 py-2 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider">Tips</th>
                                        <th class="px-4 py-2 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider">Metode</th>
                                        <th class="px-4 py-2 text-right text-[10px] font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($capsterData['transactions'] as $index => $t)
                                        <tr class="hover:bg-indigo-50/40 transition duration-150">
                                            <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-400">{{ $index + 1 }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <span class="text-xs font-semibold text-gray-700">{{ \Carbon\Carbon::parse($t->transaction_date)->format('d M Y H:i') }}</span>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <span class="text-xs font-bold text-gray-800">Rp {{ number_format($t->display_amount ?? $t->total_amount, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                <span class="text-xs {{ ($t->display_tips ?? $t->tips ?? 0) > 0 ? 'text-green-600 font-semibold' : 'text-gray-400' }}">
                                                    Rp {{ number_format($t->display_tips ?? $t->tips ?? 0, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                @if($t->paymentMethod && $t->paymentMethod->method_name == 'Cash')
                                                    <span class="text-xs font-medium text-green-700">Cash</span>
                                                @elseif($t->paymentMethod && $t->paymentMethod->method_name == 'Qris')
                                                    <span class="text-xs font-medium text-purple-700">QRIS</span>
                                                @else
                                                    <span class="text-xs text-gray-600">{{ $t->paymentMethod->method_name ?? '-' }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-right">
                                                <div class="inline-flex items-center gap-1.5">
                                                    <button onclick="openDetailModal({{ $t->id_transaction }})"
                                                        class="text-gray-600 hover:text-gray-900 text-xs font-semibold bg-gray-50 hover:bg-gray-100 border border-gray-200 px-2.5 py-1 rounded transition-colors inline-flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        Detail
                                                    </button>
                                                    <button onclick="window.open('{{ route('pos.print-struk', $t->id_transaction) }}', '_blank', 'width=400,height=600')"
                                                        class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded transition-colors inline-flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                        Cetak
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($capsterData['total_tips'] > 0)
                            <div class="px-4 py-2 bg-green-50 border-t border-green-100 flex justify-end">
                                <span class="text-xs font-bold text-gray-600">Total Tips: <span class="text-green-600">Rp {{ number_format($capsterData['total_tips'], 0, ',', '.') }}</span></span>
                            </div>
                            @endif
                        </div>

                        {{-- Desktop: Tabel Pengeluaran --}}
                        @if($capsterData['expenses']->isNotEmpty())
                        <div class="border-t border-gray-100">
                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-100">
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Riwayat Pengeluaran</h4>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider w-10">No</th>
                                            <th class="px-4 py-2 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider w-36">Tanggal</th>
                                            <th class="px-4 py-2 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider">Keterangan</th>
                                            <th class="px-4 py-2 text-left text-[10px] font-medium text-gray-400 uppercase tracking-wider">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach($capsterData['expenses'] as $index => $expense)
                                            <tr class="hover:bg-red-50/30 transition duration-150">
                                                <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-400">{{ $index + 1 }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <span class="text-xs font-semibold text-gray-700">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y H:i') }}</span>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <span class="text-xs text-gray-600">{{ $expense->description }}</span>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <span class="text-xs font-bold text-red-600">- Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="px-4 py-2 bg-red-50 border-t border-red-100 flex justify-end">
                                    <span class="text-xs font-bold text-gray-600">Total Pengeluaran: <span class="text-red-600">Rp {{ number_format($capsterData['total_expenses'], 0, ',', '.') }}</span></span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-12 text-center text-gray-400">
                        <div class="flex flex-col items-center">
                            <div class="bg-gray-50 p-3 rounded-full mb-3">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-gray-900 font-bold text-sm">Belum ada transaksi</h3>
                            <p class="text-xs mt-1 text-gray-500">Semangat jualan! Transaksi akan muncul di sini.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ===== MODAL DETAIL PENGELUARAN POS ===== --}}
    <div id="posExpenseModal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="closePosExpenseModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transform transition-all scale-100">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Detail Pengeluaran</h3>
                            <p id="peTime" class="text-xs text-gray-500 mt-0.5"></p>
                        </div>
                    </div>
                    <button onclick="closePosExpenseModal()" class="p-1.5 rounded-lg hover:bg-gray-200 transition text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-5 py-5">
                    <div class="mb-5">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Keterangan</h4>
                        <p id="peDesc" class="text-sm text-gray-800 bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed"></p>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</p>
                        <p id="peTotal" class="text-lg font-black text-red-600"></p>
                    </div>
                </div>
                <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-end bg-gray-50">
                    <button onclick="closePosExpenseModal()" class="w-full sm:w-auto px-4 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition shadow-sm">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL DETAIL TRANSAKSI ===== --}}
    <div id="detailModal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeDetailModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-3 sm:p-6">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 flex-shrink-0">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Detail Transaksi</h3>
                        <p id="modalSubtitle" class="text-xs text-gray-500 mt-0.5"></p>
                    </div>
                    <button onclick="closeDetailModal()" class="p-1.5 rounded-lg hover:bg-gray-100 transition text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <div id="modalLoading" class="flex items-center justify-center py-16">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                            <p class="text-xs text-gray-400">Memuat data...</p>
                        </div>
                    </div>
                    <div id="modalContent" class="hidden">
                        <div class="px-5 py-4 grid grid-cols-2 gap-3 border-b border-gray-100">
                            <div>
                                <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider">Kasir</p>
                                <p id="mKasir" class="text-sm font-semibold text-gray-800 mt-0.5"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider">Capster</p>
                                <p id="mCapster" class="text-sm font-semibold text-gray-800 mt-0.5"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider">Metode Bayar</p>
                                <p id="mPayment" class="text-sm font-semibold mt-0.5"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider">Tips</p>
                                <p id="mTips" class="text-sm font-semibold mt-0.5"></p>
                            </div>
                        </div>
                        <div id="mItems" class="divide-y divide-gray-100"></div>
                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-600">Total Bayar</p>
                            <p id="mTotal" class="text-base font-extrabold text-gray-900"></p>
                        </div>
                    </div>
                </div>
                <div id="modalFooter" class="hidden px-5 py-3 border-t border-gray-100 flex items-center justify-end gap-2 flex-shrink-0 bg-white">
                    <button onclick="closeDetailModal()" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">Tutup</button>
                    <button id="btnCetak" class="px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    const printBaseUrl = '{{ url('/pos/struk') }}';
    const detailBaseUrl = '{{ url('/pos/transaction-detail') }}';

    function formatRp(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    function openDetailModal(id) {
        const modal = document.getElementById('detailModal');
        const loading = document.getElementById('modalLoading');
        const content = document.getElementById('modalContent');
        const footer  = document.getElementById('modalFooter');

        loading.classList.remove('hidden');
        content.classList.add('hidden');
        footer.classList.add('hidden');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        fetch(`${detailBaseUrl}/${id}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('modalSubtitle').textContent = `#${data.id} · ${data.date} WIB`;
            document.getElementById('mKasir').textContent   = data.kasir;
            document.getElementById('mCapster').textContent = data.capster;

            const paymentEl = document.getElementById('mPayment');
            paymentEl.textContent = data.payment_method;
            const methodColors = { 'Cash': 'text-green-600', 'Qris': 'text-purple-600', 'Transfer': 'text-blue-600' };
            paymentEl.className = `text-sm font-semibold mt-0.5 ${methodColors[data.payment_method] || 'text-gray-800'}`;

            const tipsEl = document.getElementById('mTips');
            tipsEl.textContent = formatRp(data.tips);
            tipsEl.className   = `text-sm font-semibold mt-0.5 ${data.tips > 0 ? 'text-green-600' : 'text-gray-400'}`;

            document.getElementById('mTotal').textContent = formatRp(data.total_amount);

            const typeConfig = {
                service: { label: 'Layanan',           bg: 'bg-indigo-50/60', text: 'text-indigo-600',  iconColor: 'text-indigo-400' },
                product: { label: 'Produk',             bg: 'bg-amber-50/60',  text: 'text-amber-600',   iconColor: 'text-amber-400'  },
                food:    { label: 'Makanan & Minuman',  bg: 'bg-orange-50/60', text: 'text-orange-600',  iconColor: 'text-orange-400' },
            };
            const icons = {
                service: `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/></svg>`,
                product: `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>`,
                food:    `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>`,
            };

            const grouped = { service: [], product: [], food: [] };
            data.items.forEach(item => { (grouped[item.type] || grouped.service).push(item); });

            let html = '';
            ['service', 'product', 'food'].forEach(type => {
                const items = grouped[type];
                if (!items.length) return;
                const cfg = typeConfig[type];
                html += `<div class="px-5 py-1.5 ${cfg.bg}"><p class="text-[9px] font-bold ${cfg.text} uppercase tracking-wider">${cfg.label}</p></div>`;
                items.forEach(item => {
                    html += `
                    <div class="px-5 py-2.5 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="${cfg.iconColor} flex-shrink-0">${icons[type]}</span>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate">${item.name}</p>
                                ${item.capster ? `<p class="text-[10px] text-gray-400">by ${item.capster}</p>` : ''}
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-[10px] text-gray-500">${item.qty}x · ${formatRp(item.price)}</p>
                            <p class="text-xs font-bold text-gray-900">${formatRp(item.subtotal)}</p>
                        </div>
                    </div>`;
                });
            });
            document.getElementById('mItems').innerHTML = html;
            document.getElementById('btnCetak').onclick = () => {
                window.open(`${printBaseUrl}/${data.id}`, '_blank', 'width=400,height=600');
            };
            loading.classList.add('hidden');
            content.classList.remove('hidden');
            footer.classList.remove('hidden');
        })
        .catch(() => {
            loading.innerHTML = '<p class="text-xs text-red-500 py-8 text-center">Gagal memuat data. Coba lagi.</p>';
        });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => { 
        if (e.key === 'Escape') {
            closeDetailModal(); 
            closePosExpenseModal();
        }
    });

    function openPosExpenseModal(time, desc, total) {
        document.getElementById('peTime').textContent = time + ' WIB';
        document.getElementById('peDesc').textContent = desc;
        document.getElementById('peTotal').textContent = '-Rp ' + total;
        
        document.getElementById('posExpenseModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePosExpenseModal() {
        document.getElementById('posExpenseModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    </script>
</x-app-layout>