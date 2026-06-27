<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 {{ __('Laporan Keuangan') }}
        </h2>
    </x-slot>

    <div class="py-12" @open-expense-modal.window="openExpenseModal($event.detail)" @open-transaction-modal.window="openTransactionModal($event.detail)" x-data="{
             // State untuk Filter
             filterType: '{{ old('filter_type', $filterType) }}',
             selectedYear: '{{ old('year', $selectedYear) }}',
             selectedMonth: '{{ old('month', $selectedMonth) }}',
             selectedDay: '{{ old('day', $selectedDay) }}',
             selectedWeek: '{{ old('week', $selectedWeek) }}',
             availableMonths: [],
             availableDays: [],
             availableWeeks: {{ Js::from($weeksForDropdown ?? []) }},
             loadingMonths: false, loadingDays: false, loadingWeeks: false,

             // State untuk Modal Ringkasan
             showIncomeModal: false, loadingIncomeDetails: false, incomeData: null,
             showExpenditureModal: false, loadingExpenditureDetails: false, expenditureData: null,
             showProfitLossModal: false, loadingProfitLossDetails: false, profitLossData: null,

             // State untuk Modal Detail Aksi
             showTransactionDetailModal: false, loadingTransactionDetail: false, transactionDetailData: null,
             showExpenseDetailModal: false, loadingExpenseDetail: false, expenseDetailData: null,

             // Fungsi Fetch API Filter
             fetchMonths() {
                 if (!this.selectedYear) { 
                     this.availableMonths = []; 
                     this.selectedMonth = ''; 
                     this.resetSubFilters(); 
                     return Promise.resolve(); 
                 }
                 this.loadingMonths = true; 
                 this.availableMonths = [];
                 return fetch(`/admin/reports/filters/months/${this.selectedYear}`)
                     .then(res => res.ok ? res.json() : Promise.reject('Error fetching months'))
                     .then(data => { this.availableMonths = data; })
                     .catch(err => { 
                         console.error(err); 
                         this.availableMonths = []; 
                         this.selectedMonth = ''; 
                     })
                     .finally(() => this.loadingMonths = false);
             },
             fetchDays() {
                 if (!this.selectedYear || !this.selectedMonth || this.filterType !== 'harian') { 
                     this.availableDays = []; 
                     this.selectedDay = '';
                     return Promise.resolve();
                 }
                 this.loadingDays = true; 
                 this.availableDays = [];
                 return fetch(`/admin/reports/filters/days/${this.selectedYear}/${this.selectedMonth}`)
                     .then(res => res.ok ? res.json() : Promise.reject('Error fetching days'))
                     .then(data => { this.availableDays = data; })
                     .catch(err => { 
                         console.error(err); 
                         this.availableDays = []; 
                         this.selectedDay = ''; 
                     })
                     .finally(() => this.loadingDays = false);
             },
             fetchWeeks() {
                 if (!this.selectedYear || !this.selectedMonth || this.filterType !== 'mingguan') { 
                     this.availableWeeks = []; 
                     this.selectedWeek = '';
                     return Promise.resolve();
                 }
                 this.loadingWeeks = true; 
                 this.availableWeeks = [];
                 return fetch(`/admin/reports/filters/weeks/${this.selectedYear}/${this.selectedMonth}`)
                     .then(res => res.ok ? res.json() : Promise.reject('Error fetching weeks'))
                     .then(data => { this.availableWeeks = data; })
                     .catch(err => { 
                         console.error(err); 
                         this.availableWeeks = []; 
                         this.selectedWeek = ''; 
                     })
                     .finally(() => this.loadingWeeks = false);
             },
             resetSubFilters() { 
                 this.availableMonths = []; 
                 this.selectedMonth = ''; 
                 this.availableDays = []; 
                 this.selectedDay = ''; 
                 this.availableWeeks = []; 
                 this.selectedWeek = ''; 
             },
             
             // Fungsi untuk Membuka Modal Detail Pemasukan
             openIncomeModal() {
                 this.loadingIncomeDetails = true;
                 this.showIncomeModal = true;
                 this.incomeData = null;
                 
                 const params = new URLSearchParams({
                     filter_type: this.filterType,
                     year: this.selectedYear,
                     month: this.selectedMonth,
                     day: this.selectedDay,
                     week: this.selectedWeek,
                     store_id: document.getElementById('store_id').value,
                     payment_method_id: document.getElementById('payment_method_id').value
                 });

                 fetch(`{{ route('admin.reports.details.income') }}?${params.toString()}`)
                     .then(res => res.ok ? res.json() : Promise.reject('Gagal mengambil data rincian.'))
                     .then(data => {
                         if(data.success) {
                             this.incomeData = data;
                         } else {
                             alert(data.message || 'Gagal memuat data.');
                             this.showIncomeModal = false;
                         }
                     })
                     .catch(err => {
                         console.error(err);
                         alert(err.message || 'Terjadi kesalahan.');
                         this.showIncomeModal = false;
                     })
                     .finally(() => this.loadingIncomeDetails = false);
             },

             // Fungsi untuk Membuka Modal Detail Pengeluaran
             openExpenditureModal() {
                 this.loadingExpenditureDetails = true;
                 this.showExpenditureModal = true;
                 this.expenditureData = null;

                 const params = new URLSearchParams({
                     filter_type: this.filterType,
                     year: this.selectedYear,
                     month: this.selectedMonth,
                     day: this.selectedDay,
                     week: this.selectedWeek,
                     store_id: document.getElementById('store_id').value
                 });

                 fetch(`{{ route('admin.reports.details.expenditure') }}?${params.toString()}`)
                     .then(res => res.ok ? res.json() : Promise.reject('Gagal mengambil data rincian.'))
                     .then(data => {
                         if(data.success) {
                             this.expenditureData = data;
                         } else {
                             alert(data.message || 'Gagal memuat data.');
                             this.showExpenditureModal = false;
                         }
                     })
                     .catch(err => {
                         console.error(err);
                         alert(err.message || 'Terjadi kesalahan.');
                         this.showExpenditureModal = false;
                     })
                     .finally(() => this.loadingExpenditureDetails = false);
             },

             // Fungsi untuk Membuka Modal Detail Laba/Rugi
             openProfitLossModal() {
                 this.loadingProfitLossDetails = true;
                 this.showProfitLossModal = true;
                 this.profitLossData = null;

                 const params = new URLSearchParams({
                     filter_type: this.filterType,
                     year: this.selectedYear,
                     month: this.selectedMonth,
                     day: this.selectedDay,
                     week: this.selectedWeek,
                     store_id: document.getElementById('store_id').value,
                     payment_method_id: document.getElementById('payment_method_id').value
                 });

                 fetch(`{{ route('admin.reports.details.profit-loss') }}?${params.toString()}`)
                     .then(res => res.ok ? res.json() : Promise.reject('Gagal mengambil data rincian.'))
                     .then(data => {
                         if(data.success) {
                             this.profitLossData = data;
                         } else {
                             alert(data.message || 'Gagal memuat data.');
                             this.showProfitLossModal = false;
                         }
                     })
                     .catch(err => {
                         console.error(err);
                         alert(err.message || 'Terjadi kesalahan.');
                         this.showProfitLossModal = false;
                     })
                     .finally(() => this.loadingProfitLossDetails = false);
             },

             // Fungsi untuk Membuka Modal Detail Transaksi
             openTransactionModal(transactionId) {
                 this.loadingTransactionDetail = true;
                 this.transactionDetailData = null;
                 
                 // Dispatch event 'open-modal' to the x-modal component
                 window.dispatchEvent(new CustomEvent('open-modal', { detail: 'transaction-detail-modal' }));

                 fetch(`/admin/reports/details/transaction/${transactionId}`)
                     .then(res => res.ok ? res.json() : Promise.reject('Gagal mengambil data transaksi.'))
                     .then(data => {
                         if(data.success) {
                             this.transactionDetailData = data.transaction;
                         } else {
                             alert(data.message || 'Gagal memuat data.');
                             window.dispatchEvent(new CustomEvent('close-modal', { detail: 'transaction-detail-modal' }));
                         }
                     })
                     .catch(err => {
                         console.error(err);
                         alert(err.message || 'Terjadi kesalahan.');
                         window.dispatchEvent(new CustomEvent('close-modal', { detail: 'transaction-detail-modal' }));
                     })
                     .finally(() => this.loadingTransactionDetail = false);
             },

             // Fungsi untuk Membuka Modal Detail Pengeluaran Individual
             openExpenseModal(expenseId) {
                 this.loadingExpenseDetail = true;
                 this.expenseDetailData = null;
                 
                 // Dispatch event 'open-modal' to the x-modal component
                 window.dispatchEvent(new CustomEvent('open-modal', { detail: 'expense-detail-modal' }));

                 fetch(`/admin/reports/details/expense/${expenseId}`)
                     .then(res => res.ok ? res.json() : Promise.reject('Gagal mengambil data pengeluaran.'))
                     .then(data => {
                         if(data.success) {
                             this.expenseDetailData = data.expense;
                         } else {
                             alert(data.message || 'Gagal memuat data.');
                             window.dispatchEvent(new CustomEvent('close-modal', { detail: 'expense-detail-modal' }));
                         }
                     })
                     .catch(err => {
                         console.error(err);
                         alert(err.message || 'Terjadi kesalahan.');
                         window.dispatchEvent(new CustomEvent('close-modal', { detail: 'expense-detail-modal' }));
                     })
                     .finally(() => this.loadingExpenseDetail = false);
             },
             
             // Helper untuk format mata uang
             formatCurrency(value) {
                const numberValue = Number(value);
                if (isNaN(numberValue)) return '0';
                return new Intl.NumberFormat('id-ID').format(numberValue);
             }
         }" x-init="
             fetchMonths().then(() => {
                 $nextTick(() => {
                     document.getElementById('month').value = '{{ old('month', $selectedMonth) }}';
                     fetchDays().then(() => {
                         $nextTick(() => { 
                             if(document.getElementById('day')) { 
                                 document.getElementById('day').value = '{{ old('day', $selectedDay) }}'; 
                             } 
                         });
                     });
                     fetchWeeks().then(() => {
                         $nextTick(() => { 
                             if(document.getElementById('week')) { 
                                 document.getElementById('week').value = '{{ old('week', $selectedWeek) }}'; 
                             } 
                         });
                     });
                 });
             });
             $watch('selectedYear', value => { 
                 selectedMonth = ''; 
                 selectedDay = ''; 
                 selectedWeek = ''; 
                 fetchMonths(); 
             });
             $watch('selectedMonth', value => { 
                 if(value) { 
                     selectedDay = ''; 
                     selectedWeek = ''; 
                     fetchDays(); 
                     fetchWeeks(); 
                 } else { 
                     availableDays = []; 
                     selectedDay = ''; 
                     availableWeeks = []; 
                     selectedWeek = ''; 
                 } 
             });
             $watch('filterType', value => { 
                 if (value === 'harian') fetchDays(); 
                 else { availableDays = []; } 
                 if (value === 'mingguan') fetchWeeks(); 
                 else { availableWeeks = []; } 
             });
         ">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                <details class="p-6 text-gray-900 border-b border-gray-200" open>
                    <summary class="flex justify-between items-center cursor-pointer mb-4 select-none">
                        <h3 class="text-lg font-semibold text-gray-700">🔍 Filter Laporan</h3>
                        <span class="text-sm text-gray-500 hover:text-indigo-600">Klik untuk buka/tutup</span>
                    </summary>
                    <div>
                        <form method="GET" action="{{ route('admin.reports.index') }}"
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4 items-end">
                            <!-- Dropdown Tipe Filter -->
                            <div>
                                <label for="filter_type" class="block text-sm font-medium text-gray-700">Tipe</label>
                                <select name="filter_type" id="filter_type" x-model="filterType"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="harian">Harian</option>
                                    <option value="mingguan">Mingguan</option>
                                    <option value="bulanan">Bulanan</option>
                                    <option value="tahunan">Tahunan</option>
                                </select>
                            </div>

                            <!-- Dropdown Tahun -->
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700">Tahun</label>
                                <select name="year" id="year" x-model="selectedYear"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @forelse ($availableYears as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @empty
                                        <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                    @endforelse
                                </select>
                            </div>

                            <!-- Dropdown Bulan -->
                            <div
                                x-show="filterType === 'harian' || filterType === 'mingguan' || filterType === 'bulanan'">
                                <label for="month" class="block text-sm font-medium text-gray-700">Bulan</label>
                                <select name="month" id="month" x-model="selectedMonth"
                                    :disabled="loadingMonths || availableMonths.length === 0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100">
                                    <template x-if="loadingMonths">
                                        <option>Loading...</option>
                                    </template>
                                    <template x-if="!loadingMonths && availableMonths.length === 0">
                                        <option>--</option>
                                    </template>
                                    <template x-if="!loadingMonths" x-for="month in availableMonths" :key="month.value">
                                        <option :value="month.value" x-text="month.name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Dropdown Tanggal -->
                            <div x-show="filterType === 'harian'">
                                <label for="day" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <select name="day" id="day" x-model="selectedDay"
                                    :disabled="loadingDays || availableDays.length === 0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100">
                                    <template x-if="loadingDays">
                                        <option>Loading...</option>
                                    </template>
                                    <template x-if="!loadingDays && availableDays.length === 0">
                                        <option>--</option>
                                    </template>
                                    <template x-if="!loadingDays" x-for="day in availableDays" :key="day">
                                        <option :value="day" x-text="parseInt(day)"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Dropdown Minggu -->
                            <div x-show="filterType === 'mingguan'">
                                <label for="week" class="block text-sm font-medium text-gray-700">Minggu Ke</label>
                                <select name="week" id="week" x-model="selectedWeek"
                                    :disabled="loadingWeeks || availableWeeks.length === 0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100">
                                    <template x-if="loadingWeeks">
                                        <option>Loading...</option>
                                    </template>
                                    <template x-if="!loadingWeeks && availableWeeks.length === 0">
                                        <option>--</option>
                                    </template>
                                    <template x-if="!loadingWeeks" x-for="week in availableWeeks" :key="week.value">
                                        <option :value="week.value" x-text="week.name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Dropdown Toko -->
                            <div>
                                <label for="store_id" class="block text-sm font-medium text-gray-700">Toko</label>
                                <select name="store_id" id="store_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Semua Toko</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id_store }}" {{ request('store_id') == $store->id_store ? 'selected' : '' }}>
                                            {{ $store->store_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dropdown Metode Pembayaran -->
                            <div>
                                <label for="payment_method_id" class="block text-sm font-medium text-gray-700">Metode
                                    Bayar</label>
                                <select name="payment_method_id" id="payment_method_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Semua Metode</option>
                                    @foreach ($paymentMethods as $method)
                                        <option value="{{ $method->id_payment_method }}" {{ request('payment_method_id') == $method->id_payment_method ? 'selected' : '' }}>
                                            {{ $method->method_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tombol Filter -->
                            <button type="submit"
                                class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex-shrink-0 justify-center shadow-sm">
                                <svg class="w-4 h-4 mr-1 inline-block" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                Terapkan
                            </button>
                        </form>
                    </div>
                </details>
            </div>

            <!-- Summary Cards -->
            <div class="bg-gradient-to-br from-gray-50 to-indigo-100 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Ringkasan Keuangan {{ ucfirst($filterType) }}</h3>
                            <p class="text-sm text-gray-600">{{ $reportTitleDate }}</p>
                        </div>
                    </div>

                    {{-- Baris 1: Transaksi, Cash, QRIS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 mb-3">
                        {{-- Card: Transaksi --}}
                        <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-2 mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <p class="text-[9px] sm:text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Transaksi</p>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-gray-800">{{ $totalTrxCount }}</h3>
                            <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5">Total nota/struk periode ini</p>
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
                                    <span class="text-[10px] font-normal text-gray-400">Rp </span>{{ number_format($totalCash, 0, ',', '.') }}
                                </h3>
                                <span class="text-[9px] font-semibold text-green-500 bg-green-50 border border-green-100 rounded-full px-1.5 py-0.5">({{ $totalCashCount }} trx)</span>
                            </div>
                            <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5">Pendapatan cash periode ini</p>
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
                                    <span class="text-[10px] font-normal text-gray-400">Rp </span>{{ number_format($totalQris, 0, ',', '.') }}
                                </h3>
                                <span class="text-[9px] font-semibold text-purple-500 bg-purple-50 border border-purple-100 rounded-full px-1.5 py-0.5">({{ $totalQrisCount }} trx)</span>
                            </div>
                            <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5">Masuk ke rekening</p>
                            <div class="absolute right-0 top-0 h-full w-12 bg-gradient-to-l from-purple-50/60 to-transparent"></div>
                        </div>
                    </div>

                    {{-- Baris 2: Penjualan Produk, Total Pengeluaran, Hasil Cash Kasir, Total Pemasukan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 sm:gap-4 mb-3">
                        {{-- Card: Penjualan Produk --}}
                        <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-2 mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <p class="text-[9px] sm:text-[10px] font-bold text-amber-600 uppercase tracking-wider">Penjualan Produk</p>
                            </div>
                            <div class="flex items-baseline gap-1.5 flex-wrap">
                                <h3 class="text-sm sm:text-xl font-extrabold text-gray-800">
                                    <span class="text-[10px] font-normal text-gray-400">Rp </span>{{ number_format($totalProductSales, 0, ',', '.') }}
                                </h3>
                                <span class="text-[9px] font-semibold text-amber-600 bg-amber-50 border border-amber-100 rounded-full px-1.5 py-0.5">({{ $totalProductSalesQty }} item)</span>
                            </div>
                            <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5">Produk terjual periode ini</p>
                            <div class="absolute right-0 top-0 h-full w-12 bg-gradient-to-l from-amber-50/60 to-transparent"></div>
                        </div>

                        {{-- Card: Total Pengeluaran --}}
                        <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-2 mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-[9px] sm:text-[10px] font-bold text-red-500 uppercase tracking-wider">Total Pengeluaran</p>
                            </div>
                            <div class="flex items-baseline gap-1.5 flex-wrap">
                                <h3 class="text-sm sm:text-xl font-extrabold text-red-600">
                                    <span class="text-[10px] font-normal text-gray-400">Rp </span>{{ number_format($totalExpenditure, 0, ',', '.') }}
                                </h3>
                            </div>
                            <p class="text-[9px] sm:text-xs text-gray-400 mt-0.5">Bon & tips periode ini</p>
                            @if($totalTips > 0)
                            <p class="text-[9px] text-orange-400 mt-0.5">Tips: Rp {{ number_format($totalTips, 0, ',', '.') }}</p>
                            @endif
                            <div class="mt-2">
                                <button @click.prevent="openExpenditureModal()" class="text-[10px] font-medium text-red-500 hover:text-red-700">Lihat Rincian →</button>
                            </div>
                            <div class="absolute right-0 top-0 h-full w-12 bg-gradient-to-l from-red-50/60 to-transparent"></div>
                        </div>

                        {{-- Card: Hasil Cash Kasir --}}
                        <div class="bg-gradient-to-br from-indigo-500 to-violet-600 p-3 sm:p-4 rounded-xl shadow-md relative overflow-hidden group hover:shadow-lg transition-shadow">
                            <div class="flex items-center gap-2 mb-1.5">
                                <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <p class="text-[9px] sm:text-[10px] font-bold text-indigo-100 uppercase tracking-wider">Hasil Cash Kasir</p>
                            </div>
                            <h3 class="text-sm sm:text-xl font-extrabold text-white">
                                <span class="text-[10px] font-normal text-indigo-200">Rp </span>{{ number_format($hasilCashKasir, 0, ',', '.') }}
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
                                <span class="text-[10px] font-normal text-emerald-200">Rp </span>{{ number_format($totalIncome, 0, ',', '.') }}
                            </h3>
                            <p class="text-[9px] sm:text-xs text-emerald-200 mt-0.5">Cash + QRIS</p>
                            <div class="mt-1">
                                <button @click.prevent="openIncomeModal()" class="text-[10px] font-medium text-emerald-100 hover:text-white">Lihat Rincian →</button>
                            </div>
                            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full"></div>
                            <div class="absolute -right-1 -top-4 w-14 h-14 bg-white/5 rounded-full"></div>
                        </div>
                    </div>

                    {{-- Baris 3: Laba/Rugi Bersih (full width) --}}
                    <div class="bg-gradient-to-br {{ $netProfitLoss >= 0 ? 'from-blue-500 to-cyan-600' : 'from-orange-500 to-red-600' }} p-3 sm:p-4 rounded-xl shadow-md relative overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $netProfitLoss >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-[10px] font-bold {{ $netProfitLoss >= 0 ? 'text-blue-100' : 'text-orange-100' }} uppercase tracking-wider">Laba/Rugi Bersih</p>
                                    <div class="flex items-baseline gap-2 flex-wrap">
                                        <h3 class="text-lg sm:text-2xl font-extrabold text-white leading-none mt-1 sm:mt-0">
                                            <span class="text-[10px] font-normal {{ $netProfitLoss >= 0 ? 'text-blue-200' : 'text-orange-200' }}">Rp </span>{{ number_format(abs($netProfitLoss), 0, ',', '.') }}
                                        </h3>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/20 text-white">{{ $netProfitLoss >= 0 ? 'LABA' : 'RUGI' }}</span>
                                    </div>
                                    <p class="text-[9px] {{ $netProfitLoss >= 0 ? 'text-blue-200' : 'text-orange-200' }} mt-0.5">Total Pemasukan − Total Pengeluaran</p>
                                </div>
                            </div>
                            <button @click.prevent="openProfitLossModal()" class="text-[10px] sm:text-xs font-bold text-white/80 hover:text-white bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition-colors flex-shrink-0 self-start sm:self-center">
                                Lihat Rincian →
                            </button>
                        </div>
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full"></div>
                        <div class="absolute right-16 -top-6 w-16 h-16 bg-white/5 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Employee Details Section -->
            @if ($employeesDetails->isNotEmpty())
                <h3 class="text-base sm:text-xl font-black text-gray-800 mt-8 mb-4 px-4 sm:px-1">Rincian Aktivitas per Karyawan</h3>
                @foreach ($employeesDetails as $empData)
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900">
                            <!-- Header Karyawan -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 border-b border-gray-200 pb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-indigo-700 font-black text-sm">{{ strtoupper(substr($empData['employee']->employee_name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-gray-800">{{ $empData['employee']->employee_name }}</h3>
                                        <span class="text-xs text-gray-500">{{ $empData['employee']->store->store_name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                {{-- Mini Summary Badges --}}
                                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap sm:justify-end mt-2 sm:mt-0">
                                    <div class="bg-white rounded px-2 py-1 border border-gray-200 text-center" title="Jumlah sesi layanan yang dilakukan capster ini (1 transaksi bisa melibatkan 2 capster)">
                                        <p class="text-[8px] font-semibold text-gray-400 uppercase">Sesi Potong</p>
                                        <p class="text-sm font-extrabold text-indigo-600">{{ $empData['total_trx'] }}</p>
                                    </div>
                                    @if($empData['cash_count'] > 0)
                                    <div class="bg-green-50 rounded px-2 py-1 border border-green-200 text-center">
                                        <p class="text-[8px] font-semibold text-green-500 uppercase">Cash</p>
                                        <p class="text-sm font-extrabold text-green-600">{{ $empData['cash_count'] }}x</p>
                                    </div>
                                    @endif
                                    @if($empData['qris_count'] > 0)
                                    <div class="bg-purple-50 rounded px-2 py-1 border border-purple-200 text-center">
                                        <p class="text-[8px] font-semibold text-purple-500 uppercase">QRIS</p>
                                        <p class="text-sm font-extrabold text-purple-600">{{ $empData['qris_count'] }}x</p>
                                    </div>
                                    @endif
                                    @if($empData['total_product_qty'] > 0)
                                    <div class="bg-white rounded px-2 py-1 border border-amber-200 text-center">
                                        <p class="text-[8px] font-semibold text-amber-500 uppercase">Produk</p>
                                        <p class="text-sm font-extrabold text-amber-600">{{ $empData['total_product_qty'] }}</p>
                                    </div>
                                    @endif
                                    @if($empData['total_food_qty'] > 0)
                                    <div class="bg-white rounded px-2 py-1 border border-orange-200 text-center">
                                        <p class="text-[8px] font-semibold text-orange-500 uppercase">Makanan</p>
                                        <p class="text-sm font-extrabold text-orange-600">{{ $empData['total_food_qty'] }}</p>
                                    </div>
                                    @endif
                                    @if($empData['total_tips'] > 0)
                                    <div class="bg-white rounded px-2 py-1 border border-green-200 text-center">
                                        <p class="text-[8px] font-semibold text-green-500 uppercase">Tip</p>
                                        <p class="text-xs font-bold text-green-600"><span class="text-[9px] font-normal text-gray-400">Rp</span> {{ number_format($empData['total_tips'], 0, ',', '.') }}</p>
                                    </div>
                                    @endif
                                    @if($empData['total_expenses'] > 0)
                                    <div class="bg-white rounded px-2 py-1 border border-red-200 text-center">
                                        <p class="text-[8px] font-semibold text-red-400 uppercase">Keluar</p>
                                        <p class="text-xs font-bold text-red-600">Rp {{ number_format($empData['total_expenses'], 0, ',', '.') }}</p>
                                    </div>
                                    @endif
                                    <div class="bg-white rounded px-2 py-1 border border-gray-200 text-center">
                                        <p class="text-[8px] font-semibold text-gray-400 uppercase">Total</p>
                                        <p class="text-xs font-bold text-gray-800"><span class="text-[9px] font-normal text-gray-400">Rp</span> {{ number_format($empData['total_amount'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel Riwayat Transaksi -->
                            @if ($empData['transactions']->isNotEmpty())
                                <h4 class="text-md font-medium mb-2 text-gray-700">Riwayat Transaksi</h4>
                                <div class="hidden md:block overflow-x-auto mb-6 rounded-md border">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    No</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Tanggal</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Total</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Tips</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Metode</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($empData['transactions'] as $index => $transaction)
                                                <tr class="hover:bg-indigo-50">
                                                    <td class="px-4 py-2 text-xs text-gray-500">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">
                                                        {{ $transaction->transaction_date->format('d M Y H:i') }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap font-semibold text-gray-800 text-xs">Rp
                                                        {{ number_format($transaction->display_amount ?? $transaction->total_amount, 0, ',', '.') }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-xs">
                                                        @if(($transaction->display_tips ?? $transaction->tips ?? 0) > 0)
                                                            <span class="text-green-600 font-semibold">Rp {{ number_format($transaction->display_tips ?? $transaction->tips, 0, ',', '.') }}</span>
                                                        @else
                                                            <span class="text-gray-400">Rp 0</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 whitespace-nowrap">
                                                        @php $mn = $transaction->paymentMethod->method_name ?? 'N/A'; @endphp
                                                        <span class="text-xs font-bold px-2 py-0.5 rounded-full
                                                            {{ $mn === 'Cash' ? 'bg-green-100 text-green-700' : ($mn === 'Qris' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600') }}">{{ $mn }}</span>
                                                    </td>
                                                    <td
                                                        class="px-4 py-2 whitespace-nowrap text-left font-medium flex items-center gap-2">
                                                        <!-- Tombol Lihat Detail -->
                                                        <button
                                                            @click.prevent="$dispatch('open-transaction-modal', {{ $transaction->id_transaction }})"
                                                            class="text-xs text-indigo-600 hover:text-indigo-900 font-bold transition-all hover:scale-110 active:scale-95">Detail</button>

                                                        <span class="text-gray-200">|</span>

                                                        <!-- Tombol Edit -->
                                                        <a href="{{ route('admin.transactions.edit', $transaction->id_transaction) }}"
                                                            class="text-xs text-amber-600 hover:text-amber-900 font-bold transition-all hover:scale-110 active:scale-95">Edit</a>

                                                        <span class="text-gray-200">|</span>

                                                        <!-- Tombol Hapus (Trigger Modal) -->
                                                        <button type="button"
                                                            @click="$dispatch('open-modal', 'delete-transaction-{{ $transaction->id_transaction }}')"
                                                            class="text-xs text-red-600 hover:text-red-900 font-bold transition-all hover:scale-110 active:scale-95">
                                                            Hapus
                                                        </button>

                                                        {{-- Modal Konfirmasi Hapus Transaksi (Desktop) --}}
                                                        <x-modal name="delete-transaction-{{ $transaction->id_transaction }}" focusable>
                                                            <div class="p-6">
                                                                <div class="flex items-center gap-4 mb-4">
                                                                    <div
                                                                        class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0">
                                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <h2 class="text-lg font-black text-gray-900">Konfirmasi Hapus
                                                                            Transaksi</h2>
                                                                        <p class="text-sm text-gray-500 font-medium">Apakah Anda yakin
                                                                            ingin menghapus Transaksi
                                                                            #{{ $transaction->id_transaction }}? Stok produk/makanan
                                                                            akan dikembalikan.</p>
                                                                    </div>
                                                                </div>
                                                                <form method="post"
                                                                    action="{{ route('admin.transactions.destroy', $transaction->id_transaction) }}"
                                                                    class="flex justify-end gap-3">
                                                                    @csrf @method('delete')
                                                                    <x-secondary-button x-on:click="$dispatch('close')"
                                                                        class="rounded-xl font-bold">Batal</x-secondary-button>
                                                                    <x-danger-button
                                                                        class="rounded-xl font-black bg-red-600 hover:bg-red-700">Hapus
                                                                        Transaksi</x-danger-button>
                                                                </form>
                                                            </div>
                                                        </x-modal>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if ($empData['transactions']->sum('tips') > 0)
                                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
                                            <span class="text-sm font-bold text-gray-700">Total Tips: <span class="text-green-600">Rp {{ number_format($empData['transactions']->sum('tips'), 0, ',', '.') }}</span></span>
                                        </div>
                                    @endif
                                </div>
                                <!-- Mobile View: Mini Table -->
                                <div class="block md:hidden mb-4">
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
                                        @foreach ($empData['transactions'] as $index => $transaction)
                                        @php $mn = $transaction->paymentMethod->method_name ?? 'N/A'; @endphp
                                        <div class="grid grid-cols-12 px-3 py-2 items-center gap-0.5 hover:bg-indigo-50/40 transition">
                                            <div class="col-span-1 text-[9px] text-gray-400">{{ $index + 1 }}</div>
                                            <div class="col-span-3">
                                                <span class="text-[10px] font-semibold text-gray-700 leading-tight block">{{ $transaction->transaction_date->format('H:i') }}</span>
                                                <span class="text-[8px] text-gray-400 leading-tight block">{{ $transaction->transaction_date->format('d M') }}</span>
                                            </div>
                                            <div class="col-span-3">
                                                <span class="text-[10px] font-bold text-gray-800">Rp {{ number_format($transaction->display_amount ?? $transaction->total_amount, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="col-span-2">
                                                @if(($transaction->display_tips ?? $transaction->tips ?? 0) > 0)
                                                    <span class="text-[9px] font-semibold text-green-600">{{ number_format($transaction->display_tips ?? $transaction->tips, 0, ',', '.') }}</span>
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
                                            <div class="col-span-1 flex justify-end">
                                                {{-- Dropdown Aksi --}}
                                                <div x-data="{ open: false }" class="relative">
                                                    <button @click="open = !open" class="p-1 bg-white border border-slate-200 rounded text-gray-500 hover:text-indigo-600 transition">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
                                                    </button>
                                                    <div x-show="open" @click.outside="open = false"
                                                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                                        class="absolute right-0 bottom-7 z-20 bg-white border border-gray-200 rounded-xl shadow-lg py-1 w-28" style="display:none;">
                                                        <button @click.prevent="open=false; $dispatch('open-transaction-modal', {{ $transaction->id_transaction }})"
                                                            class="w-full text-left px-3 py-1.5 text-[11px] font-semibold text-indigo-600 hover:bg-indigo-50 flex items-center gap-1.5">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            Detail
                                                        </button>
                                                        <a href="{{ route('admin.transactions.edit', $transaction->id_transaction) }}"
                                                            class="w-full text-left px-3 py-1.5 text-[11px] font-semibold text-amber-600 hover:bg-amber-50 flex items-center gap-1.5">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                            Edit
                                                        </a>
                                                        <button @click="open=false; $dispatch('open-modal', 'delete-mob2-{{ $transaction->id_transaction }}')"
                                                            class="w-full text-left px-3 py-1.5 text-[11px] font-semibold text-red-600 hover:bg-red-50 flex items-center gap-1.5">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                                {{-- Modal Konfirmasi Hapus (Mobile v2) --}}
                                                <x-modal name="delete-mob2-{{ $transaction->id_transaction }}" focusable>
                                                    <div class="p-6">
                                                        <div class="flex items-center gap-4 mb-4">
                                                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                            </div>
                                                            <div>
                                                                <h2 class="text-lg font-black text-gray-900">Konfirmasi Hapus</h2>
                                                                <p class="text-sm text-gray-500 font-medium">Hapus Transaksi #{{ $transaction->id_transaction }}? Stok akan dikembalikan.</p>
                                                            </div>
                                                        </div>
                                                        <form method="post" action="{{ route('admin.transactions.destroy', $transaction->id_transaction) }}" class="flex justify-end gap-3">
                                                            @csrf @method('delete')
                                                            <x-secondary-button x-on:click="$dispatch('close')" class="rounded-xl font-bold">Batal</x-secondary-button>
                                                            <x-danger-button class="rounded-xl font-black bg-red-600 hover:bg-red-700">Hapus</x-danger-button>
                                                        </form>
                                                    </div>
                                                </x-modal>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @if ($empData['transactions']->sum('tips') > 0)
                                    <div class="px-3 py-2 bg-green-50 border-t border-green-100 flex justify-between">
                                        <span class="text-[10px] font-bold text-gray-500">Total Tips</span>
                                        <span class="text-[10px] font-black text-green-600">Rp {{ number_format($empData['transactions']->sum('tips'), 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                </div>

                            @else
                                <p class="text-sm text-gray-500 mb-6 italic">Tidak ada transaksi untuk karyawan ini pada periode
                                    terpilih.</p>
                            @endif

                            <!-- Tabel Riwayat Pengeluaran -->
                            @if ($empData['expenses']->isNotEmpty())
                                <h4 class="text-md font-medium mb-2 text-gray-700">Riwayat Pengeluaran</h4>
                                <div class="hidden md:block overflow-x-auto rounded-md border">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    No</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Tanggal</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Keterangan</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Jumlah</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($empData['expenses'] as $index => $expense)
                                                <tr class="hover:bg-red-50">
                                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap">
                                                        {{ $expense->expense_date->format('d M Y H:i') }}</td>
                                                    <td class="px-4 py-2">{{ $expense->description }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-red-600 font-medium">- Rp
                                                        {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                                    <td
                                                        class="px-4 py-2 whitespace-nowrap text-left font-medium flex items-center gap-2">
                                                        <!-- Tombol Lihat Detail -->
                                                        <button @click.prevent="$dispatch('open-expense-modal', {{ $expense->id_expense }})"
                                                            class="text-xs text-indigo-600 hover:text-indigo-900 font-bold transition-all">Detail</button>

                                                        <span class="text-gray-200">|</span>

                                                        <!-- Tombol Edit -->
                                                        <a href="{{ route('admin.expenses.edit', $expense->id_expense) }}?back_url={{ urlencode(request()->fullUrl()) }}"
                                                            class="text-xs text-amber-600 hover:text-amber-900 font-bold transition-all">Edit</a>

                                                        <span class="text-gray-200">|</span>

                                                        <!-- Tombol Hapus (Trigger Modal) -->
                                                        <button type="button"
                                                            @click="$dispatch('open-modal', 'delete-expense-{{ $expense->id_expense }}')"
                                                            class="text-xs text-red-600 hover:text-red-900 font-bold transition-all">
                                                            Hapus
                                                        </button>

                                                        {{-- Modal Konfirmasi Hapus Pengeluaran (Desktop) --}}
                                                        <x-modal name="delete-expense-{{ $expense->id_expense }}" focusable>
                                                            <div class="p-6 text-left">
                                                                <div class="flex items-center gap-4 mb-4">
                                                                    <div
                                                                        class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0">
                                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <h2 class="text-lg font-black text-gray-900">Konfirmasi Hapus
                                                                            Pengeluaran</h2>
                                                                        <p class="text-sm text-gray-500 font-medium">Apakah Anda yakin
                                                                            ingin menghapus data pengeluaran ini?</p>
                                                                    </div>
                                                                </div>
                                                                <form method="post"
                                                                    action="{{ route('admin.expenses.destroy', $expense->id_expense) }}"
                                                                    class="flex justify-end gap-3">
                                                                    @csrf @method('delete')
                                                                    <input type="hidden" name="back_url" value="{{ request()->fullUrl() }}">
                                                                    <x-secondary-button x-on:click="$dispatch('close')"
                                                                        class="rounded-xl font-bold">Batal</x-secondary-button>
                                                                    <x-danger-button
                                                                        class="rounded-xl font-black bg-red-600 hover:bg-red-700">Hapus
                                                                        Data</x-danger-button>
                                                                </form>
                                                            </div>
                                                        </x-modal>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
                                        <span class="text-sm font-bold text-gray-700">Total Pengeluaran: <span class="text-red-600">Rp {{ number_format($empData['expenses']->sum('amount'), 0, ',', '.') }}</span></span>
                                    </div>
                                </div>
                                <!-- Mobile View: Mini List (Pengeluaran) -->
                                <div class="block md:hidden mb-4 border border-gray-100 rounded-xl overflow-hidden mt-4">
                                    <div class="px-3 pt-3 pb-2 bg-gray-50 border-b border-gray-100">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Riwayat Pengeluaran</p>
                                    </div>
                                    <div class="divide-y divide-gray-100 bg-white">
                                        @foreach ($empData['expenses'] as $expense)
                                        <div class="px-3 py-2.5 flex justify-between items-center hover:bg-red-50/40 transition">
                                            <div class="flex-1 pr-2">
                                                <div class="text-xs font-semibold text-gray-700 line-clamp-1">{{ $expense->description }}</div>
                                                <div class="text-[9px] text-gray-400">{{ $expense->expense_date->format('d M Y H:i') }} WIB</div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[11px] font-black text-red-600">-Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                                
                                                {{-- Dropdown Aksi --}}
                                                <div x-data="{ open: false }" class="relative">
                                                    <button @click="open = !open" class="p-1 bg-white border border-slate-200 rounded text-gray-500 hover:text-red-600 transition flex-shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
                                                    </button>
                                                    <div x-show="open" @click.outside="open = false"
                                                        x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                                        class="absolute right-0 bottom-8 z-20 bg-white border border-gray-200 rounded-xl shadow-lg py-1 w-24" style="display:none;">
                                                        <button @click.prevent="open=false; $dispatch('open-expense-modal', {{ $expense->id_expense }})"
                                                            class="w-full text-left px-3 py-1.5 text-[11px] font-semibold text-indigo-600 hover:bg-indigo-50">
                                                            Detail
                                                        </button>
                                                        <a href="{{ route('admin.expenses.edit', $expense->id_expense) }}?back_url={{ urlencode(request()->fullUrl()) }}"
                                                            class="block w-full text-left px-3 py-1.5 text-[11px] font-semibold text-amber-600 hover:bg-amber-50">
                                                            Edit
                                                        </a>
                                                        <button @click="open=false; $dispatch('open-modal', 'delete-exp-mob-{{ $expense->id_expense }}')"
                                                            class="w-full text-left px-3 py-1.5 text-[11px] font-semibold text-red-600 hover:bg-red-50">
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                                {{-- Modal Konfirmasi Hapus (Mobile) --}}
                                                <x-modal name="delete-exp-mob-{{ $expense->id_expense }}" focusable>
                                                    <div class="p-6">
                                                        <div class="flex items-center gap-4 mb-4">
                                                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                            </div>
                                                            <div>
                                                                <h2 class="text-lg font-black text-gray-900">Konfirmasi Hapus</h2>
                                                                <p class="text-sm text-gray-500 font-medium">Hapus Pengeluaran ini?</p>
                                                            </div>
                                                        </div>
                                                        <form method="post" action="{{ route('admin.expenses.destroy', $expense->id_expense) }}" class="flex justify-end gap-3">
                                                            @csrf @method('delete')
                                                            <input type="hidden" name="back_url" value="{{ request()->fullUrl() }}">
                                                            <x-secondary-button x-on:click="$dispatch('close')" class="rounded-xl font-bold">Batal</x-secondary-button>
                                                            <x-danger-button class="rounded-xl font-black bg-red-600 hover:bg-red-700">Hapus</x-danger-button>
                                                        </form>
                                                    </div>
                                                </x-modal>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="px-3 py-2.5 bg-red-50 border-t border-red-100 flex justify-between items-center">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Pengeluaran</span>
                                        <span class="text-[11px] font-black text-red-600">Rp {{ number_format($empData['expenses']->sum('amount'), 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">Tidak ada pengeluaran untuk karyawan ini pada periode
                                    terpilih.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500 italic">
                        Tidak ada aktivitas karyawan (transaksi/pengeluaran) pada periode yang dipilih.
                    </div>
                </div>
            @endif

        </div>

        <!-- Modal Detail Pemasukan -->
        <div x-show="showIncomeModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
            style="display: none;" @keydown.escape.window="showIncomeModal = false">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
                <div x-show="showIncomeModal" @click="showIncomeModal = false"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showIncomeModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                    <div class="flex justify-between items-center pb-3 border-b">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Detail Laporan Pemasukan
                            </h3>
                            <p class="text-sm text-gray-500"
                                x-text="loadingIncomeDetails ? 'Loading...' : (incomeData ? incomeData.period : '')">
                            </p>
                        </div>
                        <button @click="showIncomeModal = false" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-4 max-h-[70vh] overflow-y-auto">
                        <!-- Spinner Loading -->
                        <div x-show="loadingIncomeDetails" class="text-center py-10">
                            <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Mengambil data...</p>
                        </div>

                        <!-- Konten jika data sudah ada -->
                        <div x-show="!loadingIncomeDetails && incomeData" class="space-y-6">

                            <!-- Ringkasan Item -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                    <p class="text-sm font-medium text-blue-700">Pendapatan Layanan</p>
                                    <p class="mt-1 text-2xl font-bold text-blue-900"
                                        x-text="'Rp ' + formatCurrency(incomeData?.services.reduce((sum, item) => sum + item.total, 0) || 0)">
                                    </p>
                                    <p class="text-xs text-gray-500"
                                        x-text="(incomeData?.services.length || 0) + ' Jenis Layanan'"></p>
                                </div>
                                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                    <p class="text-sm font-medium text-green-700">Pendapatan Produk</p>
                                    <p class="mt-1 text-2xl font-bold text-green-900"
                                        x-text="'Rp ' + formatCurrency(incomeData?.products.reduce((sum, item) => sum + item.total, 0) || 0)">
                                    </p>
                                    <p class="text-xs text-gray-500"
                                        x-text="(incomeData?.products.length || 0) + ' Jenis Produk'"></p>
                                </div>
                                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <p class="text-sm font-medium text-yellow-700">Pendapatan Makanan/Minuman</p>
                                    <p class="mt-1 text-2xl font-bold text-yellow-900"
                                        x-text="'Rp ' + formatCurrency(incomeData?.foods.reduce((sum, item) => sum + item.total, 0) || 0)">
                                    </p>
                                    <p class="text-xs text-gray-500"
                                        x-text="(incomeData?.foods.length || 0) + ' Jenis Item'"></p>
                                </div>
                            </div>

                            <details open>
                                <summary class="text-md font-semibold text-gray-700 cursor-pointer">
                                    Detail Layanan (<span x-text="incomeData?.services.length || 0"></span>)
                                </summary>
                                <div class="overflow-x-auto rounded-md border mt-2">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Layanan</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Transaksi</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Kuantitas</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <template x-for="item in incomeData?.services" :key="item.name">
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-2" x-text="item.name"></td>
                                                    <td class="px-4 py-2" x-text="item.transactions"></td>
                                                    <td class="px-4 py-2" x-text="item.quantity"></td>
                                                    <td class="px-4 py-2 font-medium"
                                                        x-text="'Rp ' + formatCurrency(item.total)"></td>
                                                </tr>
                                            </template>
                                            <template x-if="!incomeData?.services || incomeData.services.length === 0">
                                                <tr>
                                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500 italic">
                                                        Tidak ada pendapatan layanan.</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </details>

                            <details>
                                <summary class="text-md font-semibold text-gray-700 cursor-pointer">
                                    Detail Produk (<span x-text="incomeData?.products.length || 0"></span>)
                                </summary>
                                <div class="overflow-x-auto rounded-md border mt-2">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Produk</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Transaksi</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Kuantitas</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <template x-for="item in incomeData?.products" :key="item.name">
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-2" x-text="item.name"></td>
                                                    <td class="px-4 py-2" x-text="item.transactions"></td>
                                                    <td class="px-4 py-2" x-text="item.quantity"></td>
                                                    <td class="px-4 py-2 font-medium"
                                                        x-text="'Rp ' + formatCurrency(item.total)"></td>
                                                </tr>
                                            </template>
                                            <template x-if="!incomeData?.products || incomeData.products.length === 0">
                                                <tr>
                                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500 italic">
                                                        Tidak ada pendapatan produk.</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </details>

                            <details>
                                <summary class="text-md font-semibold text-gray-700 cursor-pointer">
                                    Detail Makanan/Minuman (<span x-text="incomeData?.foods.length || 0"></span>)
                                </summary>
                                <div class="overflow-x-auto rounded-md border mt-2">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Item</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Transaksi</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Kuantitas</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-500">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <template x-for="item in incomeData?.foods" :key="item.name">
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-2" x-text="item.name"></td>
                                                    <td class="px-4 py-2" x-text="item.transactions"></td>
                                                    <td class="px-4 py-2" x-text="item.quantity"></td>
                                                    <td class="px-4 py-2 font-medium"
                                                        x-text="'Rp ' + formatCurrency(item.total)"></td>
                                                </tr>
                                            </template>
                                            <template x-if="!incomeData?.foods || incomeData.foods.length === 0">
                                                <tr>
                                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500 italic">
                                                        Tidak ada pendapatan makanan/minuman.</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </details>

                            <div class="border-t pt-4 mt-6 flex justify-end">
                                <div class="text-lg font-bold text-gray-800">
                                    Total Pendapatan: <span class="text-green-700"
                                        x-text="'Rp ' + formatCurrency(incomeData?.total_income || 0)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Pengeluaran (Ringkasan) -->
        <div x-show="showExpenditureModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title-expenditure" role="dialog" aria-modal="true" style="display: none;"
            @keydown.escape.window="showExpenditureModal = false">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
                <div x-show="showExpenditureModal" @click="showExpenditureModal = false"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showExpenditureModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                    <div class="flex justify-between items-center pb-3 border-b">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title-expenditure">
                                Detail Laporan Pengeluaran
                            </h3>
                            <p class="text-sm text-gray-500"
                                x-text="loadingExpenditureDetails ? 'Loading...' : (expenditureData ? expenditureData.period : '')">
                            </p>
                        </div>
                        <button @click="showExpenditureModal = false" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-4 max-h-[70vh] overflow-y-auto">
                        <!-- Tampilkan Spinner saat Loading -->
                        <div x-show="loadingExpenditureDetails" class="text-center py-10">
                            <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Mengambil data...</p>
                        </div>

                        <!-- Tampilkan Konten jika data sudah ada -->
                        <div x-show="!loadingExpenditureDetails && expenditureData" class="space-y-4">

                            <!-- Ringkasan Total Pengeluaran -->
                            <div class="p-4 bg-red-100 rounded-lg shadow-inner border border-red-200">
                                <p class="text-sm font-medium text-red-700">Total Pengeluaran (Bon + Tips)</p>
                                <p class="mt-1 text-3xl font-bold text-red-900"
                                    x-text="'Rp ' + formatCurrency(expenditureData?.total_expenditure || 0)"></p>
                            </div>

                            <div class="overflow-x-auto rounded-md border mt-2">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left font-medium text-gray-500">Tanggal</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-500">Keterangan</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-500">Karyawan</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-500">Toko</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-500">Pencatat</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-500">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-for="item in expenditureData?.expenditures"
                                            :key="item.date + item.description">
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-2 whitespace-nowrap" x-text="item.date"></td>
                                                <td class="px-4 py-2" x-text="item.description"></td>
                                                <td class="px-4 py-2" x-text="item.employee_name"></td>
                                                <td class="px-4 py-2" x-text="item.store_name"></td>
                                                <td class="px-4 py-2" x-text="item.recorded_by"></td>
                                                <td class="px-4 py-2 font-medium text-red-600"
                                                    x-text="'- Rp ' + formatCurrency(item.amount)"></td>
                                            </tr>
                                        </template>
                                        <template
                                            x-if="!expenditureData?.expenditures || expenditureData.expenditures.length === 0">
                                            <tr>
                                                <td colspan="6" class="px-4 py-2 text-center text-gray-500 italic">Tidak
                                                    ada rincian pengeluaran.</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Laba/Rugi -->
        <div x-show="showProfitLossModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title-profitloss" role="dialog" aria-modal="true" style="display: none;"
            @keydown.escape.window="showProfitLossModal = false">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
                <div x-show="showProfitLossModal" @click="showProfitLossModal = false"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showProfitLossModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                    <div class="flex justify-between items-center pb-3 border-b">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title-profitloss">
                                Detail Laba/Rugi Bersih
                            </h3>
                            <p class="text-sm text-gray-500"
                                x-text="loadingProfitLossDetails ? 'Loading...' : (profitLossData ? profitLossData.period : '')">
                            </p>
                        </div>
                        <button @click="showProfitLossModal = false" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-4">
                        <!-- Tampilkan Spinner saat Loading -->
                        <div x-show="loadingProfitLossDetails" class="text-center py-10">
                            <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Menghitung...</p>
                        </div>

                        <!-- Tampilkan Konten jika data sudah ada -->
                        <div x-show="!loadingProfitLossDetails && profitLossData" class="space-y-4">

                            <!-- Ringkasan Total -->
                            <div :class="profitLossData?.net_profit_loss >= 0 ? 'bg-green-100 border-green-300' : 'bg-red-100 border-red-300'"
                                class="p-4 rounded-lg border text-center">
                                <p class="text-sm font-medium"
                                    :class="profitLossData?.net_profit_loss >= 0 ? 'text-green-700' : 'text-red-700'">
                                    Total Bersih (Laba/Rugi)</p>
                                <p class="mt-1 text-3xl font-bold"
                                    :class="profitLossData?.net_profit_loss >= 0 ? 'text-green-900' : 'text-red-900'"
                                    x-text="'Rp ' + formatCurrency(Math.abs(profitLossData?.net_profit_loss || 0))"></p>
                                <p class="text-xs font-semibold"
                                    :class="profitLossData?.net_profit_loss >= 0 ? 'text-green-600' : 'text-red-600'"
                                    x-text="'(' + profitLossData?.status + ')'"></p>
                            </div>

                            <!-- Rincian Perhitungan -->
                            <div class="border rounded-md divide-y">
                                <div class="flex justify-between items-center p-4">
                                    <span class="text-gray-700">Total Pemasukan</span>
                                    <span class="font-semibold text-green-700"
                                        x-text="'Rp ' + formatCurrency(profitLossData?.total_income || 0)"></span>
                                </div>
                                <div class="flex justify-between items-center p-4">
                                    <span class="text-gray-700">Total Pengeluaran</span>
                                    <span class="font-semibold text-red-700"
                                        x-text="'- Rp ' + formatCurrency(profitLossData?.total_expenditure || 0)"></span>
                                </div>
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-b-md">
                                    <span class="font-bold text-gray-900">Laba/Rugi Bersih</span>
                                    <span class="font-bold text-lg"
                                        :class="profitLossData?.net_profit_loss >= 0 ? 'text-green-700' : 'text-red-700'"
                                        x-text="'Rp ' + formatCurrency(profitLossData?.net_profit_loss || 0)"></span>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Transaksi (Centered) -->
        <div x-data="{ show: false }"
             x-on:open-modal.window="$event.detail == 'transaction-detail-modal' ? show = true : null"
             x-on:close-modal.window="$event.detail == 'transaction-detail-modal' ? show = false : null"
             x-on:keydown.escape.window="show = false"
             style="display: none;"
             x-show="show"
             class="fixed inset-0 z-50"
             aria-modal="true" role="dialog">
             
            <!-- Background Overlay -->
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" 
                 x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0"
                 @click="show = false"></div>
                 
            <!-- Modal Container (Centered) -->
            <div class="absolute inset-0 flex items-center justify-center p-3 sm:p-6 pointer-events-none">
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden pointer-events-auto transform transition-all"
                     x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 flex-shrink-0">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Detail Transaksi</h3>
                            <p class="text-xs text-gray-500 mt-0.5" 
                               x-text="loadingTransactionDetail ? 'Loading...' : (transactionDetailData?.transaction_number ?? ('#' + transactionDetailData?.id)) + ' · ' + transactionDetailData?.date + ' WIB'"></p>
                        </div>
                        <button @click="show = false" class="p-1.5 rounded-lg hover:bg-gray-100 transition text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto max-h-[70vh]">
                        <!-- Spinner Loading -->
                        <div x-show="loadingTransactionDetail" class="flex items-center justify-center py-16">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                <p class="text-xs text-gray-400">Memuat data...</p>
                            </div>
                        </div>

                        <!-- Modal Content -->
                        <div x-show="!loadingTransactionDetail && transactionDetailData" class="flex flex-col h-full">
                            <div class="px-5 py-4 grid grid-cols-2 gap-3 border-b border-gray-100">
                                <div>
                                    <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider">Kasir</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5" x-text="transactionDetailData?.kasir"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider">Capster</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-0.5" x-text="transactionDetailData?.employee_name"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider">Metode Bayar</p>
                                    <p class="text-sm font-semibold mt-0.5" 
                                       :class="{
                                           'text-green-600': transactionDetailData?.payment_method === 'Cash',
                                           'text-purple-600': transactionDetailData?.payment_method === 'Qris',
                                           'text-blue-600': transactionDetailData?.payment_method === 'Transfer',
                                           'text-gray-800': !['Cash', 'Qris', 'Transfer'].includes(transactionDetailData?.payment_method)
                                       }"
                                       x-text="transactionDetailData?.payment_method"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-wider">Tips</p>
                                    <p class="text-sm font-semibold mt-0.5" 
                                       :class="transactionDetailData?.tips > 0 ? 'text-green-600' : 'text-gray-400'"
                                       x-text="'Rp ' + formatCurrency(transactionDetailData?.tips || 0)"></p>
                                </div>
                            </div>

                            <!-- Item Lists -->
                            <div class="divide-y divide-gray-100">
                                <!-- Layanan -->
                                <template x-if="transactionDetailData?.services?.length > 0">
                                    <div>
                                        <div class="px-5 py-1.5 bg-indigo-50/60">
                                            <p class="text-[9px] font-bold text-indigo-600 uppercase tracking-wider">Layanan</p>
                                        </div>
                                        <template x-for="item in transactionDetailData?.services" :key="item.name">
                                            <div class="px-5 py-2.5 flex items-center justify-between gap-3 hover:bg-indigo-50/30 transition">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span class="text-indigo-400 flex-shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/></svg>
                                                    </span>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-semibold text-gray-800 truncate" x-text="item.name"></p>
                                                        <template x-if="item.employee_name">
                                                            <p class="text-[10px] text-gray-400" x-text="'by ' + item.employee_name"></p>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="text-right flex-shrink-0">
                                                    <p class="text-[10px] text-gray-500" x-text="item.quantity + 'x · Rp ' + formatCurrency(item.price_at_sale)"></p>
                                                    <p class="text-xs font-bold text-gray-900" x-text="'Rp ' + formatCurrency(item.subtotal)"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Produk -->
                                <template x-if="transactionDetailData?.products?.length > 0">
                                    <div>
                                        <div class="px-5 py-1.5 bg-amber-50/60">
                                            <p class="text-[9px] font-bold text-amber-600 uppercase tracking-wider">Produk</p>
                                        </div>
                                        <template x-for="item in transactionDetailData?.products" :key="item.name">
                                            <div class="px-5 py-2.5 flex items-center justify-between gap-3 hover:bg-amber-50/30 transition">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span class="text-amber-400 flex-shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                                    </span>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-semibold text-gray-800 truncate" x-text="item.name"></p>
                                                        <template x-if="item.employee_name">
                                                            <p class="text-[10px] text-gray-400" x-text="'by ' + item.employee_name"></p>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="text-right flex-shrink-0">
                                                    <p class="text-[10px] text-gray-500" x-text="item.quantity + 'x · Rp ' + formatCurrency(item.price_at_sale)"></p>
                                                    <p class="text-xs font-bold text-gray-900" x-text="'Rp ' + formatCurrency(item.subtotal)"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Makanan -->
                                <template x-if="transactionDetailData?.foods?.length > 0">
                                    <div>
                                        <div class="px-5 py-1.5 bg-orange-50/60">
                                            <p class="text-[9px] font-bold text-orange-600 uppercase tracking-wider">Makanan & Minuman</p>
                                        </div>
                                        <template x-for="item in transactionDetailData?.foods" :key="item.name">
                                            <div class="px-5 py-2.5 flex items-center justify-between gap-3 hover:bg-orange-50/30 transition">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span class="text-orange-400 flex-shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8zM6 1v3M10 1v3M14 1v3"/></svg>
                                                    </span>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-semibold text-gray-800 truncate" x-text="item.name"></p>
                                                        <template x-if="item.employee_name">
                                                            <p class="text-[10px] text-gray-400" x-text="'by ' + item.employee_name"></p>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="text-right flex-shrink-0">
                                                    <p class="text-[10px] text-gray-500" x-text="item.quantity + 'x · Rp ' + formatCurrency(item.price_at_sale)"></p>
                                                    <p class="text-xs font-bold text-gray-900" x-text="'Rp ' + formatCurrency(item.subtotal)"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <!-- Footer Total -->
                            <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between mt-auto">
                                <p class="text-xs font-semibold text-gray-600">Total Bayar</p>
                                <p class="text-base font-extrabold text-gray-900" x-text="'Rp ' + formatCurrency(transactionDetailData?.total_amount || 0)"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div x-show="!loadingTransactionDetail && transactionDetailData" class="px-5 py-3 border-t border-gray-100 flex items-center justify-end gap-2 bg-gray-50">
                        <button @click="show = false" class="px-4 py-2 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition shadow-sm">
                            Tutup
                        </button>
                        <button @click="window.open('{{ url('/pos/struk') }}/' + transactionDetailData.id, '_blank', 'width=400,height=600')" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition shadow-sm flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Struk
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <!-- Modal Detail Pengeluaran Individual -->
    <x-modal name="expense-detail-modal" maxWidth="2xl">
        <div class="p-6">
            <div class="flex justify-between items-center pb-3 border-b">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title-expense">
                        Detail Pengeluaran
                    </h3>
                    <p class="text-sm text-gray-500"
                        x-text="loadingExpenseDetail ? 'Loading...' : expenseDetailData?.date">
                    </p>
                </div>
                <button @click="$dispatch('close-modal', 'expense-detail-modal')" type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-4 max-h-[70vh] overflow-y-auto">
                <!-- Tampilkan Spinner saat Loading -->
                <div x-show="loadingExpenseDetail" class="text-center py-10">
                    <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Mengambil data...</p>
                </div>

                <!-- Tampilkan Konten jika data sudah ada -->
                <div x-show="!loadingExpenseDetail && expenseDetailData" class="space-y-4">

                    <!-- Info Kartu -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 rounded-lg border text-center">
                            <p class="text-xs font-medium text-gray-500">Toko</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900"
                                x-text="expenseDetailData?.store_name"></p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg border text-center">
                            <p class="text-xs font-medium text-gray-500">Karyawan</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900"
                                x-text="expenseDetailData?.employee_name"></p>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-md font-semibold text-gray-800 border-b pb-1 mb-2">Keterangan</h4>
                        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-md"
                            x-text="expenseDetailData?.description"></p>
                    </div>

                    <div class="border-t pt-4 mt-6 flex justify-end">
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Total Pengeluaran</p>
                            <p class="text-2xl font-bold text-red-600"
                                x-text="'- Rp ' + formatCurrency(expenseDetailData?.amount || 0)"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-modal>
    </div>
</x-app-layout>