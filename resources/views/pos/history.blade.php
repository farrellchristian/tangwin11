<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-lg sm:text-2xl text-gray-800 leading-tight">
                {{ __('Riwayat Transaksi') }}
            </h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Rekap harian untuk closing kasir.</p>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            {{-- 1. SHIFT SUMMARY CARDS — grid row on mobile --}}
            <div class="grid grid-cols-3 gap-2 sm:gap-6">
                {{-- Total Transaksi --}}
                <div class="bg-white p-3 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-[9px] sm:text-sm font-semibold text-gray-400 sm:text-gray-500 uppercase tracking-wider">Transaksi</p>
                        <h3 class="text-xl sm:text-3xl font-extrabold text-gray-800 mt-0.5 sm:mt-1">{{ $summary['total_trx'] }}</h3>
                        <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5 sm:mt-1 hidden sm:block">Pelanggan hari ini</p>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-16 sm:w-24 bg-gradient-to-l from-indigo-50 to-transparent opacity-50"></div>
                </div>

                {{-- Uang Tunai (Cash) --}}
                <div class="bg-white p-3 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-[9px] sm:text-sm font-semibold text-green-600 uppercase tracking-wider flex items-center gap-0.5 sm:gap-1">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 hidden sm:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Cash
                        </p>
                        <h3 class="text-base sm:text-3xl font-extrabold text-gray-800 mt-0.5 sm:mt-1">
                            <span class="text-[10px] sm:text-base font-normal text-gray-400">Rp</span>
                            {{ number_format($summary['total_cash'], 0, ',', '.') }}
                        </h3>
                        <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5 sm:mt-1 hidden sm:block">Uang fisik di laci kasir</p>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-16 sm:w-24 bg-gradient-to-l from-green-50 to-transparent opacity-50"></div>
                </div>

                {{-- Digital (QRIS) --}}
                <div class="bg-white p-3 sm:p-6 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-[9px] sm:text-sm font-semibold text-purple-600 uppercase tracking-wider flex items-center gap-0.5 sm:gap-1">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 hidden sm:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            QRIS
                        </p>
                        <h3 class="text-base sm:text-3xl font-extrabold text-gray-800 mt-0.5 sm:mt-1">
                            <span class="text-[10px] sm:text-base font-normal text-gray-400">Rp</span>
                            {{ number_format($summary['total_digital'], 0, ',', '.') }}
                        </h3>
                        <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5 sm:mt-1 hidden sm:block">Masuk ke rekening</p>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-16 sm:w-24 bg-gradient-to-l from-purple-50 to-transparent opacity-50"></div>
                </div>
            </div>

            {{-- 2. FILTER & PENCARIAN --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-3 sm:p-4 bg-gray-50/50">
                    <form method="GET" action="{{ route('pos.history') }}" class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        
                        {{-- Filter Date --}}
                        <div class="relative w-full sm:w-[180px] flex-shrink-0">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="date" name="date" value="{{ $date }}"
                                class="block w-full pl-10 pr-3 py-2.5 sm:py-2 border border-gray-200 rounded-lg text-sm bg-white focus:ring-indigo-500 focus:border-indigo-500"
                                onchange="this.form.submit()">
                        </div>

                        {{-- Search --}}
                        <div class="flex flex-1 gap-2">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="block w-full pl-10 pr-3 py-2.5 sm:py-2 border border-gray-200 rounded-lg text-sm bg-white placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Cari Nama Capster...">
                            </div>

                            <button type="submit" class="hidden sm:flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                Cari
                            </button>

                            @if(request('search') || request('date') != date('Y-m-d'))
                                <a href="{{ route('pos.history') }}" class="flex-shrink-0 py-2.5 sm:py-2 px-3 bg-white border border-gray-200 text-red-500 text-xs font-semibold rounded-lg hover:bg-red-50 transition flex items-center gap-1 justify-center">
                                    <svg class="w-4 h-4 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    <span class="hidden sm:inline">Reset</span>
                                </a>
                            @endif
                        </div>

                    </form>
                </div>
            </div>

            {{-- 3. RINCIAN PER CAPSTER --}}
            @forelse($groupedByCapster as $capsterData)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    {{-- Header Capster --}}
                    <div class="p-4 sm:p-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50/80 to-white">
                        <div class="flex items-center justify-between gap-3">
                            {{-- Avatar + Name --}}
                            <div class="flex items-center gap-2.5 sm:gap-3">
                                <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-base sm:text-lg ring-2 ring-indigo-200 flex-shrink-0">
                                    {{ substr($capsterData['employee']->employee_name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-lg font-bold text-gray-800">{{ $capsterData['employee']->employee_name ?? 'Unknown' }}</h3>
                                    <p class="text-[10px] sm:text-xs text-gray-500">Capster</p>
                                </div>
                            </div>

                            {{-- Mini Summary Badges --}}
                            <div class="flex items-center gap-1.5 sm:gap-3">
                                <div class="bg-white rounded-lg px-2.5 sm:px-4 py-1.5 sm:py-2 border border-gray-200 shadow-sm text-center">
                                    <p class="text-[8px] sm:text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Potong</p>
                                    <p class="text-base sm:text-xl font-extrabold text-indigo-600">{{ $capsterData['total_trx'] }}</p>
                                </div>
                                <div class="bg-white rounded-lg px-2.5 sm:px-4 py-1.5 sm:py-2 border border-gray-200 shadow-sm text-center">
                                    <p class="text-[8px] sm:text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Total</p>
                                    <p class="text-xs sm:text-lg font-bold text-gray-800">
                                        <span class="text-[9px] sm:text-sm font-normal text-gray-400">Rp</span>
                                        {{ number_format($capsterData['total_amount'], 0, ',', '.') }}
                                    </p>
                                </div>
                                @if($capsterData['total_tips'] > 0)
                                <div class="bg-white rounded-lg px-2.5 sm:px-4 py-1.5 sm:py-2 border border-green-200 shadow-sm text-center hidden sm:block">
                                    <p class="text-[10px] font-semibold text-green-500 uppercase tracking-wider">Tips</p>
                                    <p class="text-lg font-bold text-green-600">Rp {{ number_format($capsterData['total_tips'], 0, ',', '.') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Tips badge on mobile (shown below if tips > 0) --}}
                        @if($capsterData['total_tips'] > 0)
                        <div class="mt-2 sm:hidden">
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-lg border border-green-200">
                                💰 Tips: Rp {{ number_format($capsterData['total_tips'], 0, ',', '.') }}
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- ===== MOBILE: CARD LIST ===== --}}
                    <div class="block md:hidden divide-y divide-gray-100">
                        @foreach($capsterData['transactions'] as $index => $t)
                        <div class="p-4 flex items-center justify-between gap-3">
                            {{-- Left: Time + ID + Amount --}}
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                {{-- Time circle --}}
                                <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex flex-col items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-gray-800 leading-none">{{ \Carbon\Carbon::parse($t->transaction_date)->format('H:i') }}</span>
                                    <span class="text-[8px] text-gray-400 leading-none">WIB</span>
                                </div>

                                {{-- Details --}}
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($t->total_amount, 0, ',', '.') }}</span>
                                        @if($t->tips > 0)
                                        <span class="text-[9px] text-green-600 font-medium">+Tips {{ number_format($t->tips, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] text-gray-400 font-mono">#{{ $t->id_transaction }}</span>
                                        {{-- Payment method --}}
                                        @if($t->paymentMethod && $t->paymentMethod->method_name == 'Cash')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium bg-green-50 text-green-700 border border-green-200">💵 Cash</span>
                                        @elseif($t->paymentMethod && $t->paymentMethod->method_name == 'Qris')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium bg-purple-50 text-purple-700 border border-purple-200">📱 QRIS</span>
                                        @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium bg-gray-50 text-gray-600 border border-gray-200">{{ $t->paymentMethod->method_name ?? '-' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Right: Print button --}}
                            <button onclick="window.open('{{ route('pos.print-struk', $t->id_transaction) }}', '_blank', 'width=400,height=600')"
                                class="p-2 bg-white border border-slate-200 rounded-lg text-indigo-500 hover:text-indigo-700 hover:border-indigo-200 transition active:scale-95 flex-shrink-0" title="Cetak Struk">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>

                    {{-- ===== DESKTOP: TABLE ===== --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Waktu</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                    <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($capsterData['transactions'] as $index => $t)
                                    <tr class="hover:bg-indigo-50/50 transition duration-150">
                                        {{-- No --}}
                                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $index + 1 }}
                                        </td>

                                        {{-- Waktu --}}
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <span class="text-sm font-bold text-gray-800">
                                                {{ \Carbon\Carbon::parse($t->transaction_date)->format('H:i') }}
                                            </span>
                                            <span class="text-xs text-gray-500 block">WIB</span>
                                        </td>

                                        {{-- ID Transaksi --}}
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <span class="text-xs text-gray-500 font-mono bg-gray-100 px-2 py-1 rounded">
                                                #{{ $t->id_transaction }}
                                            </span>
                                        </td>

                                        {{-- Total --}}
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">
                                                Rp {{ number_format($t->total_amount, 0, ',', '.') }}
                                            </div>
                                            @if($t->tips > 0)
                                                <div class="text-[10px] text-green-600 italic">
                                                    + Tips {{ number_format($t->tips, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Metode --}}
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            @if($t->paymentMethod && $t->paymentMethod->method_name == 'Cash')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                    💵 Cash
                                                </span>
                                            @elseif($t->paymentMethod && $t->paymentMethod->method_name == 'Qris')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                                    📱 QRIS
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $t->paymentMethod->method_name ?? '-' }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Aksi (Cetak) --}}
                                        <td class="px-5 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <button onclick="window.open('{{ route('pos.print-struk', $t->id_transaction) }}', '_blank', 'width=400,height=600')"
                                                class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition-colors flex items-center justify-end gap-1 ml-auto">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                Cetak
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-14 text-center text-gray-400">
                        <div class="flex flex-col items-center">
                            <div class="bg-gray-50 p-4 rounded-full mb-3">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-gray-900 font-bold">Belum ada transaksi</h3>
                            <p class="text-sm mt-1 text-gray-500">Semangat jualan! Transaksi akan muncul di sini.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>