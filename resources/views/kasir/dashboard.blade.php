<x-app-layout>
    {{-- Header Gradient --}}
    <div class="bg-gradient-to-r from-indigo-600 to-blue-500 pb-32 pt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="text-white">
                    <h1 class="text-3xl font-bold">Halo, {{ Auth::user()->name }}! 👋</h1>
                    <p class="mt-2 text-indigo-100 text-sm opacity-90">Selamat bertugas di <span class="font-bold bg-white/20 px-2 py-1 rounded">{{ Auth::user()->store->store_name ?? 'Pusat' }}</span>. Semangat untuk hari ini!</p>
                </div>
                
                {{-- Status Presensi Badge --}}
                <div class="hidden md:block">
                    @if($hasClockedIn)
                        <div class="flex items-center bg-green-500/20 border border-green-400/50 text-white px-4 py-2 rounded-full backdrop-blur-sm">
                            <span class="relative flex h-3 w-3 mr-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-400"></span>
                            </span>
                            Sudah Absen Masuk
                        </div>
                    @else
                        <div class="flex items-center bg-red-500/20 border border-red-400/50 text-white px-4 py-2 rounded-full backdrop-blur-sm">
                            <span class="w-3 h-3 bg-red-400 rounded-full mr-2 animate-pulse"></span>
                            Belum Absen
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content (Overlap Header) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 pb-12">
        
        {{-- 1. STATISTIK CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 flex items-center relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute right-0 top-0 h-full w-20 bg-blue-50 transform skew-x-12 translate-x-10 group-hover:translate-x-6 transition-transform"></div>
                <div class="p-3 bg-blue-100 rounded-xl text-blue-600 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <div class="ml-4 z-10">
                    <p class="text-sm font-medium text-gray-500">Transaksi Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $dailyTransactions }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 flex items-center relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute right-0 top-0 h-full w-20 bg-green-50 transform skew-x-12 translate-x-10 group-hover:translate-x-6 transition-transform"></div>
                <div class="p-3 bg-green-100 rounded-xl text-green-600 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-4 z-10">
                    <p class="text-sm font-medium text-gray-500">Omzet Anda Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-indigo-900 rounded-2xl shadow-lg p-6 border border-indigo-800 text-white flex flex-col justify-center items-center relative overflow-hidden" x-data="clock()">
                <div class="absolute inset-0 bg-indigo-800 opacity-30 pattern-dots"></div>
                <p class="text-indigo-200 text-xs font-bold tracking-widest uppercase mb-1 relative z-10">Waktu Sekarang</p>
                <h3 class="text-4xl font-black tracking-tight relative z-10" x-text="time">00:00:00</h3>
                <p class="text-xs text-indigo-300 mt-1 relative z-10" x-text="date">...</p>
            </div>
        </div>

        {{-- 2. MENU AKSES CEPAT (QUICK ACTIONS) --}}
        <h2 class="text-lg font-bold text-gray-800 mb-4 px-1">Menu Cepat</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            
            <a href="{{ route('pos.index') }}" class="group bg-white p-6 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 transition-all duration-200 flex flex-col items-center justify-center text-center hover:border-indigo-200 hover:scale-[1.02]">
                <div class="h-14 w-14 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="font-bold text-gray-800 group-hover:text-indigo-600">Kasir (POS)</h3>
                <p class="text-xs text-gray-400 mt-1">Buat transaksi baru</p>
            </a>

            <a href="{{ route('pos.history') }}" class="group bg-white p-6 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 transition-all duration-200 flex flex-col items-center justify-center text-center hover:border-blue-200 hover:scale-[1.02]">
                <div class="h-14 w-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-bold text-gray-800 group-hover:text-blue-600">Riwayat</h3>
                <p class="text-xs text-gray-400 mt-1">Cek transaksi hari ini</p>
            </a>

            <a href="{{ route('presence.index') }}" class="group bg-white p-6 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 transition-all duration-200 flex flex-col items-center justify-center text-center hover:border-purple-200 hover:scale-[1.02]">
                <div class="h-14 w-14 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="font-bold text-gray-800 group-hover:text-purple-600">Presensi</h3>
                <p class="text-xs text-gray-400 mt-1">Absen masuk/pulang</p>
            </a>

            <a href="{{ route('kasir.expenses.select-employee') }}" class="group bg-white p-6 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 transition-all duration-200 flex flex-col items-center justify-center text-center hover:border-orange-200 hover:scale-[1.02]">
                <div class="h-14 w-14 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="font-bold text-gray-800 group-hover:text-orange-600">Pengeluaran</h3>
                <p class="text-xs text-gray-400 mt-1">Catat bon & beli barang</p>
            </a>

        </div>

        {{-- 3. TABEL AKTIVITAS TERAKHIR --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Aktivitas Terakhir Anda</h3>
                <a href="{{ route('pos.history') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions as $t)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $t->transaction_date->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $t->id_transaction }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-bold">
                                    Rp {{ number_format($t->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Berhasil
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">
                                    Belum ada transaksi hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Alpine JS Clock --}}
    <script>
        function clock() {
            return {
                time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':'),
                date: new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }),
                init() {
                    setInterval(() => {
                        this.time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':');
                        this.date = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    }, 1000);
                }
            }
        }
    </script>
</x-app-layout>