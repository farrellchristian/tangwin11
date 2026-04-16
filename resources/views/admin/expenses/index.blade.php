<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Pengeluaran & Limit Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            {{-- FILTER SECTION --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
                x-data="{
                     open: false,
                     filterType: '{{ old('filter_type', $filterType) }}',
                     selectedYear: '{{ old('year', $selectedYear) }}',
                     selectedMonth: '{{ old('month', $selectedMonth) }}',
                     selectedDay: '{{ old('day', $selectedDay) }}',
                     selectedWeek: '{{ old('week', $selectedWeek) }}',

                     availableMonths: [],
                     availableDays: [],
                     availableWeeks: {{ Js::from($weeksForDropdown ?? []) }},

                     loadingMonths: false,
                     loadingDays: false,
                     loadingWeeks: false,

                     fetchMonths() {
                         if (!this.selectedYear) { this.availableMonths = []; this.selectedMonth = ''; return; }
                         this.loadingMonths = true;
                         fetch(`/admin/expenses/filters/months/${this.selectedYear}`)
                             .then(res => res.ok ? res.json() : Promise.reject('Network response was not ok.'))
                             .then(data => {
                                 this.availableMonths = data;
                                 if (!data.some(m => m.value === this.selectedMonth)) {
                                     this.selectedMonth = data[0]?.value || '';
                                 }
                                 this.fetchDays();
                                 this.fetchWeeks();
                             })
                             .catch(err => {
                                 console.error('Error fetching months:', err);
                                 this.availableMonths = [];
                                 this.selectedMonth = '';
                             })
                             .finally(() => this.loadingMonths = false);
                     },
                     fetchDays() {
                         if (!this.selectedYear || !this.selectedMonth || this.filterType !== 'harian') { this.availableDays = []; return; }
                         this.loadingDays = true;
                         fetch(`/admin/expenses/filters/days/${this.selectedYear}/${this.selectedMonth}`)
                             .then(res => res.ok ? res.json() : Promise.reject('Network response was not ok.'))
                             .then(data => {
                                 this.availableDays = data;
                                 if (!data.includes(this.selectedDay)) {
                                     this.selectedDay = data[0] || '';
                                 }
                             })
                             .catch(err => {
                                  console.error('Error fetching days:', err);
                                  this.availableDays = [];
                                  this.selectedDay = '';
                              })
                             .finally(() => this.loadingDays = false);
                     },
                     fetchWeeks() {
                         if (!this.selectedYear || !this.selectedMonth || this.filterType !== 'mingguan') { this.availableWeeks = []; return; }
                         this.loadingWeeks = true;
                         fetch(`/admin/expenses/filters/weeks/${this.selectedYear}/${this.selectedMonth}`)
                             .then(res => res.ok ? res.json() : Promise.reject('Network response was not ok.'))
                             .then(data => {
                                 this.availableWeeks = data;
                                 if (!data.some(w => w.value == this.selectedWeek)) {
                                     this.selectedWeek = data[0]?.value || '';
                                 }
                             })
                              .catch(err => {
                                  console.error('Error fetching weeks:', err);
                                  this.availableWeeks = [];
                                  this.selectedWeek = '';
                              })
                             .finally(() => this.loadingWeeks = false);
                     },

                     isLoading(type) {
                          if (type === 'month') return this.loadingMonths;
                          if (type === 'day') return this.loadingDays;
                          if (type === 'week') return this.loadingWeeks;
                          return false;
                     }
                 }"
                x-init="
                     fetchMonths();
                     $watch('selectedYear', value => { fetchMonths(); });
                     $watch('selectedMonth', value => { fetchDays(); fetchWeeks(); });
                     $watch('filterType', value => {
                          if (value === 'harian') fetchDays();
                          else this.availableDays = [];
                          if (value === 'mingguan') fetchWeeks();
                          else this.availableWeeks = [];
                     });
                 ">

                {{-- Filter Header (collapsible on mobile) --}}
                <button @click="open = !open" type="button" class="w-full flex items-center justify-between p-4 md:hidden">
                    <div class="flex items-center gap-2">
                        <div class="bg-slate-100 p-2 rounded-lg text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Filter Pengeluaran</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold capitalize" :class="filterType === 'harian' ? 'bg-blue-100 text-blue-700' : filterType === 'mingguan' ? 'bg-purple-100 text-purple-700' : filterType === 'bulanan' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700'" x-text="filterType"></span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                {{-- Desktop: always visible label --}}
                <div class="hidden md:flex items-center gap-2 px-6 pt-5 pb-0">
                    <div class="bg-slate-100 p-2 rounded-lg text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Filter Riwayat Pengeluaran</span>
                </div>

                {{-- Filter Content --}}
                <div class="md:block" :class="open ? 'block' : 'hidden'" x-cloak>
                    <form method="GET" action="{{ route('admin.expenses.index') }}" class="p-4 md:p-6 pt-3 md:pt-4 space-y-3 md:space-y-0 md:grid md:grid-cols-6 md:gap-4 md:items-end">

                        {{-- Tipe Filter --}}
                        <div>
                            <label for="filter_type" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tipe Filter</label>
                            <select name="filter_type" id="filter_type" x-model="filterType" class="block w-full rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-gray-50 hover:bg-white transition">
                                <option value="harian">Harian</option>
                                <option value="mingguan">Mingguan</option>
                                <option value="bulanan">Bulanan</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>

                        {{-- Filter Tahun --}}
                        <div>
                            <label for="year" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tahun</label>
                            <select name="year" id="year" x-model="selectedYear" class="block w-full rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-gray-50 hover:bg-white transition">
                                @forelse ($availableYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @empty
                                <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                @endforelse
                            </select>
                        </div>

                        {{-- Filter Bulan --}}
                        <div x-show="filterType === 'harian' || filterType === 'mingguan' || filterType === 'bulanan'">
                            <label for="month" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Bulan</label>
                            <select name="month" id="month" x-model="selectedMonth" :disabled="loadingMonths" class="block w-full rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-gray-50 hover:bg-white transition disabled:bg-gray-100">
                                <template x-if="loadingMonths">
                                    <option value="">Loading...</option>
                                </template>
                                <template x-if="!loadingMonths && availableMonths.length === 0">
                                    <option value="">Tidak ada data</option>
                                </template>
                                <template x-if="!loadingMonths" x-for="month in availableMonths" :key="month.value">
                                    <option :value="month.value" x-text="month.name"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Filter Tanggal --}}
                        <div x-show="filterType === 'harian'">
                            <label for="day" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal</label>
                            <select name="day" id="day" x-model="selectedDay" :disabled="loadingDays || availableDays.length === 0" class="block w-full rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-gray-50 hover:bg-white transition disabled:bg-gray-100">
                                <template x-if="loadingDays">
                                    <option value="">Loading...</option>
                                </template>
                                <template x-if="!loadingDays && availableDays.length === 0">
                                    <option value="">Tidak ada data</option>
                                </template>
                                <template x-if="!loadingDays" x-for="day in availableDays" :key="day">
                                    <option :value="day" x-text="parseInt(day)"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Filter Minggu --}}
                        <div x-show="filterType === 'mingguan'">
                            <label for="week" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Minggu Ke</label>
                            <select name="week" id="week" x-model="selectedWeek" :disabled="loadingWeeks || availableWeeks.length === 0" class="block w-full rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-gray-50 hover:bg-white transition disabled:bg-gray-100">
                                <template x-if="loadingWeeks">
                                    <option value="">Loading...</option>
                                </template>
                                <template x-if="!loadingWeeks && availableWeeks.length === 0">
                                    <option value="">Tidak ada data</option>
                                </template>
                                <template x-if="!loadingWeeks" x-for="week in availableWeeks" :key="week.value">
                                    <option :value="week.value" x-text="week.name"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Filter Toko --}}
                        <div>
                            <label for="store_id" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Toko</label>
                            <select name="store_id" id="store_id" class="block w-full rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-gray-50 hover:bg-white transition">
                                <option value="">Semua Toko</option>
                                @foreach ($stores as $store)
                                <option value="{{ $store->id_store }}" {{ request('store_id') == $store->id_store ? 'selected' : '' }}>
                                    {{ $store->store_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Filter --}}
                        <div class="flex gap-2 md:col-span-6 lg:col-span-1">
                            <button type="submit" class="flex-1 md:w-full inline-flex justify-center items-center gap-1.5 py-2.5 px-4 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('admin.expenses.index') }}" class="py-2.5 px-4 bg-white border border-gray-200 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-50 transition md:hidden">
                                Reset
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            {{-- ALERTS --}}
            
            @if (session('error'))
            <div class="p-3 sm:p-4 rounded-xl bg-red-50 border border-red-100 flex items-center gap-3 text-red-700">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
            @endif

            {{-- RIWAYAT PENGELUARAN --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">

                {{-- Section Header --}}
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 bg-gray-50/30 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-base sm:text-lg">Riwayat Pengeluaran</h3>
                        <p class="text-[10px] sm:text-xs text-slate-500">Data pengeluaran operasional.</p>
                    </div>
                    <span class="text-[10px] sm:text-xs text-gray-400 font-medium">{{ $expenses->total() }} data</span>
                </div>

                {{-- ===== MOBILE: CARD LIST ===== --}}
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse ($expenses as $expense)
                    <div class="p-4 space-y-3">

                        {{-- Row 1: Employee + Amount --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-red-50 text-red-500 font-bold text-sm flex items-center justify-center flex-shrink-0 border border-red-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $expense->employee->employee_name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-gray-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $expense->expense_date->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-red-600">- Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                        </div>

                        {{-- Row 2: Details --}}
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-[9px] text-gray-400 uppercase font-bold tracking-wider mb-1">Keterangan</p>
                            <p class="text-sm text-gray-800">{{ $expense->description }}</p>
                        </div>

                        {{-- Row 3: Store + User + Actions --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg border border-blue-100">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"></path>
                                    </svg>
                                    {{ $expense->store->store_name ?? 'N/A' }}
                                </span>
                                <span class="text-[10px] text-gray-400">oleh {{ $expense->user->name ?? 'N/A' }}</span>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.expenses.edit', $expense->id_expense) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-indigo-600 hover:border-indigo-200 transition active:scale-95" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.expenses.destroy', $expense->id_expense) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data pengeluaran ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-200 transition active:scale-95" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-14 flex flex-col items-center justify-center text-gray-400 px-4">
                        <div class="bg-gray-50 p-4 rounded-full mb-3">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-900 font-bold">Tidak ada data</h3>
                        <p class="text-gray-500 text-sm mt-1 max-w-xs text-center">Tidak ada data pengeluaran untuk periode ini.</p>
                    </div>
                    @endforelse
                </div>

                {{-- ===== DESKTOP: TABLE ===== --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-600">
                        <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-400">
                            <tr>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Keterangan</th>
                                <th class="px-6 py-4">Jumlah</th>
                                <th class="px-6 py-4">Karyawan</th>
                                <th class="px-6 py-4">Toko</th>
                                <th class="px-6 py-4">Diinput Oleh</th>
                                <th class="px-6 py-4 text-right">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($expenses as $expense)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $expense->expense_date->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-[200px] truncate">{{ $expense->description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-red-600">- Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $expense->employee->employee_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-700 border border-blue-200">{{ $expense->store->store_name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $expense->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.expenses.edit', $expense->id_expense) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-indigo-600 hover:border-indigo-200 hover:shadow-sm transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.expenses.destroy', $expense->id_expense) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data pengeluaran ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-red-600 hover:border-red-200 hover:shadow-sm transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center text-gray-400">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    <p class="text-sm font-medium">Tidak ada data pengeluaran untuk periode ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-100 bg-gray-50/30">
                    {{ $expenses->links() }}
                </div>
            </div>

            {{-- LIMIT KARYAWAN --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">

                {{-- Section Header --}}
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 bg-gray-50/30">
                    <h3 class="font-bold text-slate-800 text-base sm:text-lg">Limit Pengeluaran Harian</h3>
                    <p class="text-[10px] sm:text-xs text-slate-500">Atur batas pengeluaran harian per karyawan.</p>
                </div>

                {{-- Bulk Update --}}
                <div class="p-4 sm:px-6 border-b border-gray-100 bg-amber-50/30" x-data="bulkLimitSettingData()">
                    <form @submit.prevent="updateAllLimits()">
                        <p class="text-xs font-semibold text-gray-700 mb-2">Set Semua Limit (Sama Rata)</p>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1 sm:max-w-[200px]">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="number"
                                    x-model="globalLimit"
                                    placeholder="0"
                                    min="0" step="1000"
                                    class="block w-full pl-9 rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-white"
                                    required>
                            </div>
                            <button type="submit"
                                class="px-4 py-2.5 bg-green-600 text-white rounded-lg text-xs font-semibold hover:bg-green-700 transition disabled:opacity-50 whitespace-nowrap"
                                :disabled="loading">
                                <span x-show="!loading">Terapkan</span>
                                <span x-show="loading">Proses...</span>
                            </button>
                        </div>
                        <p x-show="error" x-text="error" class="text-xs text-red-600 mt-2"></p>
                    </form>
                </div>

                {{-- ===== MOBILE: CARD LIST for Limits ===== --}}
                <div class="block md:hidden divide-y divide-gray-100" x-data="limitSettingData()">
                    @forelse ($employees as $employee)
                    <div class="p-4 space-y-3">
                        {{-- Employee Info --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 font-bold text-sm flex items-center justify-center flex-shrink-0">
                                    {{ substr($employee->employee_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $employee->employee_name }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $employee->store->store_name ?? 'N/A' }}</div>
                                </div>
                            </div>

                            {{-- Current Limit Badge --}}
                            @if($employee->daily_expense_limit !== null)
                            <span class="text-xs font-bold text-green-700 bg-green-50 px-2 py-1 rounded-lg border border-green-200">
                                Rp {{ number_format($employee->daily_expense_limit, 0, ',', '.') }}
                            </span>
                            @else
                            <span class="text-[10px] font-medium text-gray-400 bg-gray-50 px-2 py-1 rounded-lg border border-gray-200">
                                Tanpa limit
                            </span>
                            @endif
                        </div>

                        {{-- Set Limit Form --}}
                        <form @submit.prevent="updateLimit({{ $employee->id_employee }})">
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                    <input type="number"
                                        x-model="limits[{{ $employee->id_employee }}]"
                                        placeholder="{{ $employee->daily_expense_limit ?? 'Tanpa limit' }}"
                                        min="0" step="1000"
                                        class="block w-full pl-8 rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-gray-50">
                                </div>
                                <button type="submit"
                                    class="px-3 py-2.5 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 disabled:opacity-50 transition whitespace-nowrap"
                                    :disabled="loading">
                                    Simpan
                                </button>
                            </div>
                            <p x-show="errors[{{ $employee->id_employee }}]" x-text="errors[{{ $employee->id_employee }}]" class="text-xs text-red-600 mt-1"></p>
                        </form>
                    </div>
                    @empty
                    <div class="py-14 flex flex-col items-center justify-center text-gray-400 px-4">
                        <div class="bg-gray-50 p-4 rounded-full mb-3">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-900 font-bold">Tidak ada karyawan</h3>
                        <p class="text-gray-500 text-sm mt-1 text-center">Tidak ada data karyawan aktif.</p>
                    </div>
                    @endforelse
                </div>

                {{-- ===== DESKTOP: TABLE for Limits ===== --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-600">
                        <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-400">
                            <tr>
                                <th class="px-6 py-4">Nama Karyawan</th>
                                <th class="px-6 py-4">Toko</th>
                                <th class="px-6 py-4">Limit Harian Saat Ini</th>
                                <th class="px-6 py-4">Set Limit Baru (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" x-data="limitSettingData()">
                            @forelse ($employees as $employee)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 font-bold text-sm flex items-center justify-center flex-shrink-0">
                                            {{ substr($employee->employee_name, 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-gray-900">{{ $employee->employee_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $employee->store->store_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    @if($employee->daily_expense_limit !== null)
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($employee->daily_expense_limit, 0, ',', '.') }}</span>
                                    @else
                                    <span class="text-gray-400 italic">Tidak ada limit</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form @submit.prevent="updateLimit({{ $employee->id_employee }})">
                                        <div class="flex items-center space-x-2">
                                            <input type="number"
                                                x-model="limits[{{ $employee->id_employee }}]"
                                                placeholder="{{ $employee->daily_expense_limit ?? 'Kosongkan jika tanpa limit' }}"
                                                min="0" step="1000"
                                                class="block w-40 rounded-lg border-gray-200 shadow-sm focus:border-indigo-400 focus:ring-indigo-400 sm:text-sm">
                                            <button type="submit"
                                                class="px-3 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 disabled:opacity-50 transition"
                                                :disabled="loading">
                                                <span x-show="!loading">Simpan</span>
                                                <span x-show="loading">...</span>
                                            </button>
                                        </div>
                                        <p x-show="errors[{{ $employee->id_employee }}]" x-text="errors[{{ $employee->id_employee }}]" class="text-xs text-red-600 mt-1"></p>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-14 text-center text-sm text-gray-400">Tidak ada data karyawan aktif.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Script Alpine.js untuk Update Limit --}}
    <script>
        function limitSettingData() {
            /* ... kode update limit ... */
            return {
                limits: {},
                errors: {},
                loading: false,
                updateLimit(employeeId) {
                    this.loading = true;
                    this.errors[employeeId] = '';
                    const newLimit = this.limits[employeeId];
                    fetch(`/admin/employees/${employeeId}/update-limit`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                daily_expense_limit: newLimit === '' || newLimit === undefined || newLimit === null ? null : newLimit
                            }) // Kirim null jika kosong/undefined
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw err;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                alert('Limit berhasil diperbarui!');
                                window.location.reload();
                            } else {
                                this.errors[employeeId] = data.message || 'Gagal.';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            let errorMsg = 'Error.';
                            if (error?.errors?.daily_expense_limit) {
                                errorMsg = error.errors.daily_expense_limit.join(', ');
                            } else if (error?.message) {
                                errorMsg = error.message;
                            }
                            this.errors[employeeId] = errorMsg;
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            }
        }

        function bulkLimitSettingData() {
            return {
                globalLimit: '',
                error: '',
                loading: false,
                updateAllLimits() {
                    this.loading = true;
                    this.error = '';

                    if (!this.globalLimit && this.globalLimit !== '0') {
                        this.error = 'Masukkan nominal limit.';
                        this.loading = false;
                        return;
                    }

                    fetch(`/admin/employees/update-limit-bulk`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                daily_expense_limit: this.globalLimit
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw err;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                alert('Limit semua karyawan berhasil diperbarui!');
                                window.location.reload();
                            } else {
                                this.error = data.message || 'Gagal menyimpan perubahan.';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                            if (error?.message) {
                                errorMsg = error.message;
                            }
                            this.error = errorMsg;
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            }
        }
    </script>
</x-app-layout>