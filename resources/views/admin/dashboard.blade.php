<x-app-layout>
    {{-- LOAD CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- HEADER SECTION --}}
    <div class="bg-slate-900 pb-32 pt-12 rounded-b-[3rem] shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
             <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
             <div class="absolute top-0 right-1/4 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-indigo-400 font-semibold tracking-wide uppercase text-xs mb-1">Executive Overview</h2>
                    <h1 class="text-3xl md:text-4xl font-bold text-white">
                        Selamat Datang, {{ Auth::user()->name }}
                    </h1>
                    <p class="text-slate-400 mt-2 text-sm">
                        Monitoring performa bisnis & keuangan per <span class="text-white font-semibold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>.
                    </p>
                </div>
                
                <div class="flex gap-3">
                    <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-4 py-2 bg-white/10 border border-white/20 rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-white/20 transition backdrop-blur-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan Detail
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 pb-12 relative z-20">
        
        {{-- 1. STATS CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Income Today --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-indigo-500 transform transition hover:-translate-y-1 hover:shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-green-600 bg-green-100 px-2 py-1 rounded-md">Hari Ini</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">Rp {{ number_format($stats['income_today'], 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 mt-1 uppercase font-semibold">Pemasukan</p>
            </div>

            {{-- Transactions Today --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-blue-500 transform transition hover:-translate-y-1 hover:shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-1 rounded-md">Aktif</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $stats['trx_today'] }}</h3>
                <p class="text-xs text-slate-500 mt-1 uppercase font-semibold">Transaksi</p>
            </div>

            {{-- Monthly Income --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-emerald-500 transform transition hover:-translate-y-1 hover:shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-100 px-2 py-1 rounded-md">Bulan Ini</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">Rp {{ number_format($stats['income_month'], 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 mt-1 uppercase font-semibold">Total Omzet</p>
            </div>

            {{-- Employees --}}
            <div class="bg-white rounded-2xl shadow-lg p-6 border-b-4 border-purple-500 transform transition hover:-translate-y-1 hover:shadow-xl">
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-purple-50 rounded-xl text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-purple-600 bg-purple-100 px-2 py-1 rounded-md">Hadir</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $stats['active_employees'] }}</h3>
                <p class="text-xs text-slate-500 mt-1 uppercase font-semibold">Karyawan</p>
            </div>
        </div>

        {{-- 2. CHARTS SECTION (NEW & GACOR) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            {{-- Line Chart: Tren Pendapatan --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    Tren Pendapatan (7 Hari Terakhir)
                </h3>
                <div class="h-72">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>

            {{-- Doughnut Chart: Komposisi Penjualan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    Komposisi Penjualan
                </h3>
                <div class="h-64 flex justify-center">
                    <canvas id="compositionChart"></canvas>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-xs text-slate-400">Berdasarkan jumlah item terjual bulan ini.</p>
                </div>
            </div>
        </div>

        {{-- 3. BOTTOM SECTION: Transactions & Top Employees --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Transaksi Terbaru --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">Aktivitas Terbaru</h3>
                    <a href="{{ route('pos.history') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Waktu</th>
                                <th class="px-6 py-3 font-semibold">Toko</th>
                                <th class="px-6 py-3 font-semibold">Capster</th>
                                <th class="px-6 py-3 font-semibold text-right">Total</th>
                                <th class="px-6 py-3 font-semibold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentTransactions as $trx)
                                <tr class="hover:bg-slate-50 transition">
                                    {{-- Waktu --}}
                                    <td class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($trx->transaction_date)->format('H:i') }}
                                        <span class="text-xs text-slate-400 font-normal ml-1">WIB</span>
                                    </td>

                                    {{-- Toko --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $trx->store->store_name ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- Capster --}}
                                    <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                        {{ $trx->employee->employee_name ?? 'Unknown' }}
                                    </td>

                                    {{-- Total (Rata Kanan) --}}
                                    <td class="px-6 py-4 text-right font-bold text-slate-800 whitespace-nowrap">
                                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                    </td>

                                    {{-- Status (Rata Tengah) --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Sukses
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">
                                        Belum ada transaksi hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Top Employees & Low Stock (Combined for height balance) --}}
            <div class="space-y-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800">🏆 Top Capster</h3>
                    </div>
                    <div class="p-2">
                        @forelse($topEmployees as $index => $emp)
                            <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-lg transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $index == 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-600' }}">{{ $index + 1 }}</div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $emp->employee_name }}</p>
                                        <p class="text-[10px] text-slate-500">{{ $emp->store->store_name ?? '-' }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-indigo-600">Rp {{ number_format($emp->transactions_sum_total_amount ?? 0, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="text-center text-xs text-slate-400 py-4">Belum ada data.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
                    <div class="p-5 border-b border-red-50 bg-red-50/30 flex items-center gap-2">
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                        <h3 class="font-bold text-red-800">Stok Menipis</h3>
                    </div>
                    <div class="p-2">
                        @forelse($lowStockProducts as $prod)
                            <div class="flex items-center justify-between p-3 hover:bg-red-50/30 rounded-lg transition border-b border-slate-50 last:border-0">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $prod->product_name }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $prod->store->store_name ?? '-' }}</p>
                                </div>
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-md">Sisa: {{ $prod->stock_available }}</span>
                            </div>
                        @empty
                            <p class="text-center text-xs text-green-600 font-medium py-4">Stok Aman!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS Blob --}}
    <style>.animate-blob { animation: blob 7s infinite; } .animation-delay-2000 { animation-delay: 2s; } @keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }</style>

    {{-- SCRIPT CHART.JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. LINE CHART: Income Trend
            const ctxIncome = document.getElementById('incomeChart').getContext('2d');
            new Chart(ctxIncome, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($chartValues),
                        borderColor: '#6366f1', // Indigo 500
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4 // Smooth curve
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#f1f5f9' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 2. DOUGHNUT CHART: Composition
            const ctxComp = document.getElementById('compositionChart').getContext('2d');
            new Chart(ctxComp, {
                type: 'doughnut',
                data: {
                    labels: @json($pieLabels),
                    datasets: [{
                        data: @json($pieValues),
                        backgroundColor: [
                            '#6366f1', // Indigo (Service)
                            '#3b82f6', // Blue (Product)
                            '#f59e0b'  // Amber (Food)
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, font: { size: 11 } } }
                    }
                }
            });
        });
    </script>
</x-app-layout>