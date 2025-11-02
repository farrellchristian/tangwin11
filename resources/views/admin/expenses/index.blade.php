<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mr-4">
                {{ __('Riwayat Pengeluaran & Limit Karyawan') }}
            </h2>
            {{-- Tombol Kembali bisa ditambahkan jika perlu --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200"
                     x-data="{
                         filterType: '{{ old('filter_type', $filterType) }}',
                         selectedYear: '{{ old('year', $selectedYear) }}', // Ambil dari controller
                         selectedMonth: '{{ old('month', $selectedMonth) }}',
                         selectedDay: '{{ old('day', $selectedDay) }}',
                         selectedWeek: '{{ old('week', $selectedWeek) }}',

                         // Data dinamis dari API/Controller
                         availableMonths: [],
                         availableDays: [],
                         availableWeeks: {{ Js::from($weeksForDropdown ?? []) }}, // Diisi saat load awal

                         // Loading states
                         loadingMonths: false,
                         loadingDays: false,
                         loadingWeeks: false,

                         // Fungsi Fetch API
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
                                     // Panggil fetch berikutnya SETELAH bulan selesai di-load/reset
                                     this.fetchDays();
                                     this.fetchWeeks();
                                 })
                                 .catch(err => {
                                     console.error('Error fetching months:', err);
                                     this.availableMonths = []; // Kosongkan jika error
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
                                      this.availableDays = []; // Kosongkan jika error
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
                                      this.availableWeeks = []; // Kosongkan jika error
                                      this.selectedWeek = '';
                                  })
                                 .finally(() => this.loadingWeeks = false);
                         },

                         // Helper untuk menampilkan loading
                         isLoading(type) {
                              if (type === 'month') return this.loadingMonths;
                              if (type === 'day') return this.loadingDays;
                              if (type === 'week') return this.loadingWeeks;
                              return false;
                         }
                     }"
                     x-init="
                         fetchMonths(); // Ambil bulan saat halaman load

                         $watch('selectedYear', value => {
                             fetchMonths(); // Ambil bulan, hari, minggu baru jika tahun berubah
                         });
                         $watch('selectedMonth', value => {
                             fetchDays(); // Ambil tanggal baru jika bulan berubah
                             fetchWeeks(); // Ambil minggu baru jika bulan berubah
                         });
                          $watch('filterType', value => {
                              // Ambil ulang data yg relevan jika tipe filter berubah
                              if (value === 'harian') fetchDays();
                              else this.availableDays = []; // Kosongkan jika bukan harian

                              if (value === 'mingguan') fetchWeeks();
                              else this.availableWeeks = []; // Kosongkan jika bukan mingguan
                          });
                     ">
                    <h3 class="text-lg font-semibold mb-4">Filter Riwayat Pengeluaran</h3>
                    <form method="GET" action="{{ route('admin.expenses.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4 items-end">

                        {{-- Tipe Filter --}}
                        <div>
                            <label for="filter_type" class="block text-sm font-medium text-gray-700">Tipe Filter</label>
                            <select name="filter_type" id="filter_type" x-model="filterType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="harian">Harian</option>
                                <option value="mingguan">Mingguan</option>
                                <option value="bulanan">Bulanan</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>

                        {{-- Filter Tahun (Dinamis dari Controller) --}}
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700">Tahun</label>
                            <select name="year" id="year" x-model="selectedYear" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @forelse ($availableYears as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @empty
                                     <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                @endforelse
                            </select>
                        </div>

                        {{-- Filter Bulan (Dinamis dari API) --}}
                        <div x-show="filterType === 'harian' || filterType === 'mingguan' || filterType === 'bulanan'">
                            <label for="month" class="block text-sm font-medium text-gray-700">Bulan</label>
                            <select name="month" id="month" x-model="selectedMonth" :disabled="loadingMonths" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100">
                                <template x-if="loadingMonths"><option value="">Loading...</option></template>
                                <template x-if="!loadingMonths && availableMonths.length === 0"><option value="">Tidak ada data</option></template>
                                <template x-if="!loadingMonths" x-for="month in availableMonths" :key="month.value">
                                    <option :value="month.value" x-text="month.name"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Filter Tanggal (Dinamis dari API) --}}
                        <div x-show="filterType === 'harian'">
                            <label for="day" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <select name="day" id="day" x-model="selectedDay" :disabled="loadingDays || availableDays.length === 0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100">
                                 <template x-if="loadingDays"><option value="">Loading...</option></template>
                                 <template x-if="!loadingDays && availableDays.length === 0"><option value="">Tidak ada data</option></template>
                                 <template x-if="!loadingDays" x-for="day in availableDays" :key="day">
                                    <option :value="day" x-text="parseInt(day)"></option>
                                </template>
                            </select>
                        </div>

                         {{-- Filter Minggu (Dinamis dari API) --}}
                        <div x-show="filterType === 'mingguan'">
                            <label for="week" class="block text-sm font-medium text-gray-700">Minggu Ke</label>
                            <select name="week" id="week" x-model="selectedWeek" :disabled="loadingWeeks || availableWeeks.length === 0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100">
                                 <template x-if="loadingWeeks"><option value="">Loading...</option></template>
                                 <template x-if="!loadingWeeks && availableWeeks.length === 0"><option value="">Tidak ada data</option></template>
                                 <template x-if="!loadingWeeks" x-for="week in availableWeeks" :key="week.value">
                                    <option :value="week.value" x-text="week.name"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Filter Toko (Selalu Tampil) --}}
                        <div>
                            <label for="store_id" class="block text-sm font-medium text-gray-700">Pilih Toko</label>
                            <select name="store_id" id="store_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Semua Toko</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id_store }}" {{ request('store_id') == $store->id_store ? 'selected' : '' }}>
                                        {{ $store->store_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Terapkan Filter --}}
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 flex-shrink-0 justify-center">
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1 inline-block">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                             </svg>
                            Terapkan Filter
                        </button>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
             @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Riwayat Pengeluaran</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toko</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diinput Oleh</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($expenses as $expense)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $expense->expense_date->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4"><div class="text-sm text-gray-900">{{ $expense->description }}</div></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">- Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $expense->employee->employee_name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $expense->store->store_name ?? 'N/A' }}</span></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $expense->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- Link Edit (Menggunakan Primary Key Baru) --}}
                                            <a href="{{ route('admin.expenses.edit', $expense->id_expense) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                            {{-- Form Delete (Soft Delete) --}}
                                            <form action="{{ route('admin.expenses.destroy', $expense->id_expense) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin ingin menghapus data pengeluaran ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Hapus</button> {{-- Ganti teks jadi Hapus (Soft Delete) --}}
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data pengeluaran untuk periode ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $expenses->links() }}</div>
                </div>
            </div>

             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Pengaturan Limit Pengeluaran Harian Karyawan</h3>
                     <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                             <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Karyawan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toko</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Limit Harian Saat Ini</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Set Limit Baru (Rp)</th>
                                </tr>
                            </thead>
                             <tbody class="bg-white divide-y divide-gray-200" x-data="limitSettingData()">
                                @forelse ($employees as $employee)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $employee->employee_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->store->store_name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->daily_expense_limit !== null ? 'Rp ' . number_format($employee->daily_expense_limit, 0, ',', '.') : 'Tidak ada limit' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <form @submit.prevent="updateLimit({{ $employee->id_employee }})">
                                                <div class="flex items-center space-x-2">
                                                    <input type="number"
                                                           x-model="limits[{{ $employee->id_employee }}]"
                                                           placeholder="{{ $employee->daily_expense_limit ?? 'Kosongkan jika tanpa limit' }}"
                                                           min="0" step="1000"
                                                           class="block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                    <button type="submit"
                                                            class="px-3 py-1 bg-indigo-600 text-white rounded-md text-xs font-medium hover:bg-indigo-700 disabled:opacity-50"
                                                            :disabled="loading">
                                                        <span x-show="!loading">Simpan</span>
                                                        <span x-show="loading">Menyimpan...</span>
                                                    </button>
                                                </div>
                                                <p x-show="errors[{{ $employee->id_employee }}]" x-text="errors[{{ $employee->id_employee }}]" class="text-xs text-red-600 mt-1"></p>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data karyawan aktif.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Script Alpine.js untuk Update Limit --}}
    <script>
        function limitSettingData() { /* ... kode update limit ... */
             return {
                limits: {}, errors: {}, loading: false,
                updateLimit(employeeId) {
                    this.loading = true; this.errors[employeeId] = '';
                    const newLimit = this.limits[employeeId];
                    fetch(`/admin/employees/${employeeId}/update-limit`, {
                        method: 'PUT',
                        headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                        body: JSON.stringify({ daily_expense_limit: newLimit === '' || newLimit === undefined || newLimit === null ? null : newLimit }) // Kirim null jika kosong/undefined
                    })
                    .then(response => { if (!response.ok) { return response.json().then(err => { throw err; }); } return response.json(); })
                    .then(data => { if (data.success) { alert('Limit berhasil diperbarui!'); window.location.reload(); } else { this.errors[employeeId] = data.message || 'Gagal.'; }})
                    .catch(error => { console.error('Error:', error); let errorMsg = 'Error.'; if (error?.errors?.daily_expense_limit) { errorMsg = error.errors.daily_expense_limit.join(', '); } else if (error?.message) { errorMsg = error.message; } this.errors[employeeId] = errorMsg; })
                    .finally(() => { this.loading = false; });
                }
            }
        }
    </script>
</x-app-layout>