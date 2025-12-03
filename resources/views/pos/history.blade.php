<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Riwayat Transaksi') }}
                </h2>
                <p class="text-sm text-gray-500">Rekapitulasi transaksi harian untuk closing kasir.</p>
            </div>

            {{-- FILTER TANGGAL --}}
            <form method="GET" action="{{ route('pos.history') }}" class="flex items-center gap-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <input type="date" name="date" value="{{ $date }}" 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                        onchange="this.form.submit()">
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. SHIFT SUMMARY CARDS (RINGKASAN SHIFT) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Total Transaksi --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Transaksi</p>
                        <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $summary['total_trx'] }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Pelanggan hari ini</p>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-indigo-50 to-transparent opacity-50"></div>
                </div>

                {{-- Uang Tunai (Cash) --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-sm font-semibold text-green-600 uppercase tracking-wider flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Uang Tunai (Cash)
                        </p>
                        <h3 class="text-3xl font-extrabold text-gray-800 mt-1">Rp {{ number_format($summary['total_cash'], 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Uang fisik di laci kasir</p>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-green-50 to-transparent opacity-50"></div>
                </div>

                {{-- Digital (QRIS) --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-sm font-semibold text-purple-600 uppercase tracking-wider flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            Non-Tunai (QRIS)
                        </p>
                        <h3 class="text-3xl font-extrabold text-gray-800 mt-1">Rp {{ number_format($summary['total_digital'], 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Masuk ke rekening</p>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-purple-50 to-transparent opacity-50"></div>
                </div>
            </div>

            {{-- 2. TABEL DATA --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Toolbar Pencarian --}}
                <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                    <div class="relative w-full sm:w-96">
                        <form method="GET" action="{{ route('pos.history') }}">
                            <input type="hidden" name="date" value="{{ $date }}">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                placeholder="Cari ID Transaksi atau Nama Capster...">
                        </form>
                    </div>
                    
                    {{-- Tombol Reset --}}
                    @if(request('search') || request('date') != date('Y-m-d'))
                        <a href="{{ route('pos.history') }}" class="text-xs font-medium text-red-600 hover:text-red-800 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Reset Filter
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID & Capster</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $t)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    {{-- Waktu --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($t->transaction_date)->format('H:i') }}
                                        </span>
                                        <span class="text-xs text-gray-500 block">WIB</span>
                                    </td>

                                    {{-- ID & Capster --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-3">
                                                {{ substr($t->employee->employee_name ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $t->employee->employee_name ?? 'Unknown' }}
                                                </div>
                                                <div class="text-xs text-gray-500 font-mono">
                                                    #{{ $t->id_transaction }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Total --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="window.open('{{ route('pos.print-struk', $t->id_transaction) }}', '_blank', 'width=400,height=600')" 
                                            class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition-colors flex items-center justify-end gap-1 ml-auto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            Cetak
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <p class="text-base font-medium">Belum ada transaksi hari ini.</p>
                                            <p class="text-sm mt-1">Semangat jualan! Transaksi akan muncul di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $transactions->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>