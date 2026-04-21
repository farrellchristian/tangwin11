<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 {{ __('Laporan Keuangan') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
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
                 this.showTransactionDetailModal = true;
                 this.transactionDetailData = null;

                 fetch(`/admin/reports/details/transaction/${transactionId}`)
                     .then(res => res.ok ? res.json() : Promise.reject('Gagal mengambil data transaksi.'))
                     .then(data => {
                         if(data.success) {
                             this.transactionDetailData = data.transaction;
                         } else {
                             alert(data.message || 'Gagal memuat data.');
                             this.showTransactionDetailModal = false;
                         }
                     })
                     .catch(err => {
                         console.error(err);
                         alert(err.message || 'Terjadi kesalahan.');
                         this.showTransactionDetailModal = false;
                     })
                     .finally(() => this.loadingTransactionDetail = false);
             },

             // Fungsi untuk Membuka Modal Detail Pengeluaran Individual
             openExpenseModal(expenseId) {
                 this.loadingExpenseDetail = true;
                 this.showExpenseDetailModal = true;
                 this.expenseDetailData = null;

                 fetch(`/admin/reports/details/expense/${expenseId}`)
                     .then(res => res.ok ? res.json() : Promise.reject('Gagal mengambil data pengeluaran.'))
                     .then(data => {
                         if(data.success) {
                             this.expenseDetailData = data.expense;
                         } else {
                             alert(data.message || 'Gagal memuat data.');
                             this.showExpenseDetailModal = false;
                         }
                     })
                     .catch(err => {
                         console.error(err);
                         alert(err.message || 'Terjadi kesalahan.');
                         this.showExpenseDetailModal = false;
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
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Ringkasan Keuangan
                                {{ ucfirst($filterType) }}</h3>
                            <p class="text-sm text-gray-600">{{ $reportTitleDate }}</p>
                        </div>

                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mt-4">
                        <!-- Total Pemasukan -->
                        <div
                            class="p-3 sm:p-5 bg-white rounded-xl shadow-sm border border-gray-100 border-l-4 border-green-500 flex flex-col justify-between">
                            <div>
                                <p class="text-[11px] sm:text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">
                                    💰 Total Pemasukan</p>
                                <p class="text-xl sm:text-3xl font-black text-gray-800">Rp
                                    {{ number_format($totalIncome, 0, ',', '.') }}</p>
                            </div>
                            <div class="mt-3 text-right">
                                <button @click.prevent="openIncomeModal()"
                                    class="text-xs font-medium text-green-600 hover:text-green-800">
                                    Lihat Rincian &rarr;
                                </button>
                            </div>
                        </div>

                        <!-- Total Pengeluaran -->
                        <div
                            class="p-3 sm:p-5 bg-white rounded-xl shadow-sm border border-gray-100 border-l-4 border-red-500 flex flex-col justify-between">
                            <div>
                                <p class="text-[11px] sm:text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">
                                    💸 Total Pengeluaran</p>
                                <p class="text-xl sm:text-3xl font-black text-gray-800">Rp
                                    {{ number_format($totalExpenditure, 0, ',', '.') }}</p>
                            </div>
                            <div class="mt-3 text-right">
                                <button @click.prevent="openExpenditureModal()"
                                    class="text-xs font-medium text-red-600 hover:text-red-800">
                                    Lihat Rincian &rarr;
                                </button>
                            </div>
                        </div>

                        <!-- Laba / Rugi Bersih -->
                        <div
                            class="p-3 sm:p-5 bg-white rounded-xl shadow-sm border border-gray-100 border-l-4 {{ $netProfitLoss >= 0 ? 'border-blue-500' : 'border-orange-500' }} flex flex-col justify-between">
                            <div>
                                <p class="text-[11px] sm:text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">
                                    📈 Laba/Rugi Bersih</p>
                                <p
                                    class="text-xl sm:text-3xl font-black {{ $netProfitLoss >= 0 ? 'text-blue-700' : 'text-orange-600' }}">
                                    Rp {{ number_format(abs($netProfitLoss), 0, ',', '.') }}</p>
                                <p
                                    class="text-[10px] font-bold {{ $netProfitLoss >= 0 ? 'text-blue-500' : 'text-orange-400' }}">
                                    ({{ $netProfitLoss >= 0 ? 'Laba' : 'Rugi' }})</p>
                            </div>
                            <div class="mt-3 text-right">
                                <button @click.prevent="openProfitLossModal()"
                                    class="text-xs font-medium {{ $netProfitLoss >= 0 ? 'text-blue-600 hover:text-blue-800' : 'text-orange-600 hover:text-orange-800' }}">
                                    Lihat Rincian &rarr;
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Details Section -->
            @if ($employeesDetails->isNotEmpty())
                <h3 class="text-base sm:text-xl font-black text-gray-800 mt-8 mb-4 px-1">Rincian Aktivitas per Karyawan</h3>
                @foreach ($employeesDetails as $empData)
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900">
                            <!-- Header Karyawan -->
                            <div class="flex items-center space-x-3 mb-4 border-b border-gray-200 pb-3">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $empData['employee']->employee_name }}
                                    </h3>
                                    <span
                                        class="text-sm text-gray-500">{{ $empData['employee']->store->store_name ?? 'N/A' }}</span>
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
                                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap">
                                                        {{ $transaction->transaction_date->format('d M Y H:i') }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap font-medium text-gray-800">Rp
                                                        {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap text-gray-600">Rp
                                                        {{ number_format($transaction->tips ?? 0, 0, ',', '.') }}</td>
                                                    <td class="px-4 py-2 whitespace-nowrap">
                                                        {{ $transaction->paymentMethod->method_name ?? 'N/A' }}</td>
                                                    <td
                                                        class="px-4 py-2 whitespace-nowrap text-left font-medium flex items-center gap-2">
                                                        <!-- Tombol Lihat Detail -->
                                                        <button
                                                            @click.prevent="openTransactionModal({{ $transaction->id_transaction }})"
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
                                </div>
                                <!-- Mobile View: Cards -->
                                <!-- Mobile View: Cards -->
                                <div class="block md:hidden space-y-4 mb-6">
                                    @foreach ($empData['transactions'] as $index => $transaction)
                                        <div
                                            class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex flex-col gap-3 hover:shadow-md transition duration-200">
                                            <div class="flex justify-between items-start">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div
                                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">
                                                            {{ $transaction->transaction_date->format('d M Y H:i') }}
                                                        </div>
                                                        <div class="text-sm font-black text-gray-800">
                                                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                                        </div>
                                                        <div
                                                            class="text-[11px] text-gray-500 font-medium bg-gray-50 px-2 py-0.5 rounded-md inline-block mt-1">
                                                            {{ $transaction->paymentMethod->method_name ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($transaction->tips > 0)
                                                    <div
                                                        class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-xl text-[10px] font-black border border-blue-100">
                                                        TIP Rp{{ number_format($transaction->tips, 0, ',', '.') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex gap-2 pt-3 border-t border-gray-50 mt-1">
                                                {{-- Detail --}}
                                                <button @click.prevent="openTransactionModal({{ $transaction->id_transaction }})"
                                                    class="flex-1 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-bold hover:bg-indigo-100 transition-colors border border-indigo-100 items-center justify-center inline-flex gap-1.5 active:scale-95 transform">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Detail
                                                </button>

                                                {{-- Edit --}}
                                                <a href="{{ route('admin.transactions.edit', $transaction->id_transaction) }}"
                                                    class="flex-1 py-2.5 bg-amber-50 text-amber-700 rounded-xl text-xs font-bold hover:bg-amber-100 transition-colors border border-amber-100 items-center justify-center inline-flex gap-1.5 active:scale-95 transform">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>

                                                {{-- Hapus (Modal) --}}
                                                <button type="button"
                                                    @click="$dispatch('open-modal', 'delete-transaction-mobile-{{ $transaction->id_transaction }}')"
                                                    class="flex-1 py-2.5 bg-red-50 text-red-700 rounded-xl text-xs font-bold hover:bg-red-100 transition-colors border border-red-100 items-center justify-center inline-flex gap-1.5 active:scale-95 transform">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus
                                                </button>

                                                {{-- Modal Konfirmasi Hapus Transaksi (Mobile) --}}
                                                <x-modal name="delete-transaction-mobile-{{ $transaction->id_transaction }}" focusable>
                                                    <div class="p-6">
                                                        <div class="flex flex-col items-center text-center gap-4 mb-6">
                                                            <div
                                                                class="w-16 h-16 rounded-3xl bg-red-100 flex items-center justify-center text-red-600 shadow-inner">
                                                                <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <h2 class="text-xl font-black text-gray-900 leading-tight">Hapus
                                                                    Transaksi?</h2>
                                                                <p class="text-sm text-gray-500 font-medium mt-1 italic">Aksi ini akan
                                                                    menghapus Transaksi #{{ $transaction->id_transaction }} dan
                                                                    mengembalikan stok barang.</p>
                                                            </div>
                                                        </div>
                                                        <form method="post"
                                                            action="{{ route('admin.transactions.destroy', $transaction->id_transaction) }}"
                                                            class="flex flex-col gap-3">
                                                            @csrf @method('delete')
                                                            <x-danger-button
                                                                class="w-full py-4 rounded-2xl font-black text-base justify-center shadow-lg shadow-red-500/20 bg-red-600">YA,
                                                                HAPUS DATA</x-danger-button>
                                                            <x-secondary-button x-on:click="$dispatch('close')"
                                                                class="w-full py-4 rounded-2xl font-black text-base justify-center">BATAL</x-secondary-button>
                                                        </form>
                                                    </div>
                                                </x-modal>
                                            </div>
                                        </div>
                                    @endforeach
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
                                                        <button @click.prevent="openExpenseModal({{ $expense->id_expense }})"
                                                            class="text-xs text-indigo-600 hover:text-indigo-900 font-bold transition-all">Detail</button>

                                                        <span class="text-gray-200">|</span>

                                                        <!-- Tombol Edit -->
                                                        <a href="{{ route('admin.expenses.edit', $expense->id_expense) }}"
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
                                </div>
                                <!-- Mobile View: Cards -->
                                <div class="block md:hidden space-y-4">
                                    @foreach ($empData['expenses'] as $index => $expense)
                                        <div
                                            class="bg-white border border-gray-100 shadow-sm rounded-2xl p-4 flex flex-col gap-3 hover:shadow-md transition duration-200">
                                            <div class="flex justify-between items-start">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <div
                                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">
                                                            {{ $expense->expense_date->format('d M Y H:i') }}
                                                        </div>
                                                        <div class="text-[13px] font-bold text-gray-800 line-clamp-2 leading-snug">
                                                            {{ $expense->description }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="bg-red-50 text-red-700 px-2.5 py-1 rounded-xl text-[11px] font-black whitespace-nowrap border border-red-100">
                                                    -Rp{{ number_format($expense->amount, 0, ',', '.') }}
                                                </div>
                                            </div>
                                            <div class="flex gap-2 pt-3 border-t border-gray-50 mt-1">
                                                <button @click.prevent="openExpenseModal({{ $expense->id_expense }})"
                                                    class="flex-1 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl text-xs font-bold hover:bg-indigo-100 transition-colors border border-indigo-100 items-center justify-center inline-flex gap-1.5 active:scale-95 transform">Detail</button>
                                                <a href="{{ route('admin.expenses.edit', $expense->id_expense) }}"
                                                    class="flex-1 py-2.5 bg-amber-50 text-amber-700 rounded-xl text-xs font-bold hover:bg-amber-100 transition-colors border border-amber-100 items-center justify-center inline-flex gap-1.5 active:scale-95 transform text-center">Edit</a>

                                                <button type="button"
                                                    @click="$dispatch('open-modal', 'delete-expense-mobile-{{ $expense->id_expense }}')"
                                                    class="flex-1 py-2.5 bg-red-50 text-red-700 rounded-xl text-xs font-bold hover:bg-red-100 transition-colors border border-red-100 items-center justify-center inline-flex gap-1.5 active:scale-95 transform text-center">Hapus</button>

                                                {{-- Modal Konfirmasi Hapus Pengeluaran (Mobile) --}}
                                                <x-modal name="delete-expense-mobile-{{ $expense->id_expense }}" focusable>
                                                    <div class="p-6 text-left">
                                                        <h2 class="text-xl font-black text-gray-900 leading-tight">Hapus Data?</h2>
                                                        <p class="text-sm text-gray-500 font-medium mt-1 italic">Apakah Anda yakin ingin
                                                            menghapus catatan pengeluaran ini?</p>
                                                        <form method="post"
                                                            action="{{ route('admin.expenses.destroy', $expense->id_expense) }}"
                                                            class="mt-6 flex flex-col gap-3">
                                                            @csrf @method('delete')
                                                            <x-danger-button
                                                                class="w-full py-4 rounded-2xl font-black justify-center bg-red-600 text-base shadow-lg shadow-red-500/20 uppercase tracking-wider">Ya,
                                                                Hapus Data</x-danger-button>
                                                            <x-secondary-button x-on:click="$dispatch('close')"
                                                                class="w-full py-4 rounded-2xl font-black justify-center text-base uppercase tracking-wider">Batal</x-secondary-button>
                                                        </form>
                                                    </div>
                                                </x-modal>
                                            </div>
                                        </div>
                                    @endforeach
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

        <!-- Modal Detail Transaksi -->
        <div x-show="showTransactionDetailModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title-transaction" role="dialog" aria-modal="true" style="display: none;"
            @keydown.escape.window="showTransactionDetailModal = false">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
                <div x-show="showTransactionDetailModal" @click="showTransactionDetailModal = false"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showTransactionDetailModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-flex flex-col w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg max-h-[85vh]">

                    <div class="flex justify-between items-center p-6 border-b shrink-0 bg-white z-10">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title-transaction"
                                x-text="`Detail Transaksi #${transactionDetailData?.id || ''}`">
                            </h3>
                            <p class="text-sm text-gray-500 mt-1"
                                x-text="loadingTransactionDetail ? 'Loading...' : (transactionDetailData ? transactionDetailData.date : '')">
                            </p>
                        </div>
                        <button @click="showTransactionDetailModal = false" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 pb-6 pt-4 overflow-y-auto flex-1 bg-white">
                        <!-- Tampilkan Spinner saat Loading -->
                        <div x-show="loadingTransactionDetail" class="text-center py-10">
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
                        <div x-show="!loadingTransactionDetail && transactionDetailData" class="space-y-6">

                            {{-- Info Ringkasan (Status & Metode) --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="text-xs font-bold text-gray-500 uppercase">Metode Bayar</p>
                                    <p class="text-base font-semibold text-gray-800 mt-1" x-text="transactionDetailData?.payment_method"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <p class="text-xs font-bold text-gray-500 uppercase">Status</p>
                                    <p class="text-base font-semibold mt-1">
                                        <span class="px-2 py-1 rounded text-xs font-semibold uppercase"
                                            :class="transactionDetailData?.status === 'Paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                                            x-text="transactionDetailData?.status"></span>
                                    </p>
                                </div>
                            </div>

                            {{-- Info Lokasi & PIC --}}
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between text-base border-b pb-4 gap-2">
                                <div class="flex items-center gap-2 text-gray-700">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                                    </svg>
                                    <span class="font-medium" x-text="transactionDetailData?.store_name"></span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-700">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="font-medium" x-text="transactionDetailData?.employee_name"></span>
                                </div>
                            </div>

                            {{-- Itemized Sections --}}
                            <div class="space-y-4">
                                {{-- Layanan --}}
                                <div x-show="transactionDetailData?.services && transactionDetailData.services.length > 0">
                                    <h4 class="text-sm font-bold text-gray-700 border-b pb-2 mb-3">Layanan</h4>
                                    <div class="space-y-2 text-base">
                                        <template x-for="item in transactionDetailData?.services" :key="item.name">
                                            <div class="flex justify-between items-start border-b border-gray-50 pb-2">
                                                <div>
                                                    <p class="font-semibold text-gray-800" x-text="item.name"></p>
                                                    <p class="text-sm text-gray-500" x-text="`x${item.quantity} - ${item.employee_name}`"></p>
                                                </div>
                                                <p class="font-semibold text-gray-800" x-text="'Rp ' + formatCurrency(item.subtotal)"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Produk --}}
                                <div x-show="transactionDetailData?.products && transactionDetailData.products.length > 0">
                                    <h4 class="text-sm font-bold text-gray-700 border-b pb-2 mb-3">Produk</h4>
                                    <div class="space-y-2 text-base">
                                        <template x-for="item in transactionDetailData?.products" :key="item.name">
                                            <div class="flex justify-between items-start border-b border-gray-50 pb-2">
                                                <div>
                                                    <p class="font-semibold text-gray-800" x-text="item.name"></p>
                                                    <p class="text-sm text-gray-500" x-text="`${item.quantity} x Rp ${formatCurrency(item.price_at_sale)}`"></p>
                                                </div>
                                                <p class="font-semibold text-gray-800" x-text="'Rp ' + formatCurrency(item.subtotal)"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Makanan/Minuman --}}
                                <div x-show="transactionDetailData?.foods && transactionDetailData.foods.length > 0">
                                    <h4 class="text-sm font-bold text-gray-700 border-b pb-2 mb-3">Makanan & Minuman</h4>
                                    <div class="space-y-2 text-base">
                                        <template x-for="item in transactionDetailData?.foods" :key="item.name">
                                            <div class="flex justify-between items-start border-b border-gray-50 pb-2">
                                                <div>
                                                    <p class="font-semibold text-gray-800" x-text="item.name"></p>
                                                    <p class="text-sm text-gray-500" x-text="`${item.quantity} x Rp ${formatCurrency(item.price_at_sale)}`"></p>
                                                </div>
                                                <p class="font-semibold text-gray-800" x-text="'Rp ' + formatCurrency(item.subtotal)"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Section --}}
                            <div class="mt-6 border-t pt-4 space-y-2 text-base">
                                <div class="flex justify-between text-gray-600" x-show="transactionDetailData?.tips > 0">
                                    <span>Tips Karyawan</span>
                                    <span class="font-medium" x-text="'Rp ' + formatCurrency(transactionDetailData?.tips)"></span>
                                </div>
                                <div class="flex justify-between items-center bg-gray-100 p-4 rounded-lg">
                                    <span class="font-bold text-gray-900 text-lg">Total Transaksi</span>
                                    <span class="font-bold text-indigo-700 text-xl" x-text="'Rp ' + formatCurrency(transactionDetailData?.total_amount)"></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pengeluaran Individual -->
    <div x-show="showExpenseDetailModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-expense" role="dialog" aria-modal="true"
        style="display: none;" @keydown.escape.window="showExpenseDetailModal = false">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
            <div x-show="showExpenseDetailModal" @click="showExpenseDetailModal = false"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showExpenseDetailModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                <div class="flex justify-between items-center pb-3 border-b">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title-expense">
                            <span x-text="`Pengeluaran #${expenseDetailData?.id}`">...</span>
                        </h3>
                        <p class="text-sm text-gray-500"
                            x-text="loadingExpenseDetail ? 'Loading...' : (expenseDetailData ? expenseDetailData.date : '')">
                        </p>
                    </div>
                    <button @click="showExpenseDetailModal = false" type="button"
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
        </div>
    </div>
    </div>
</x-app-layout>