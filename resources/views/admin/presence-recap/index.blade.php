<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekap Presensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                
                {{-- Kartu 1: Total Log Presensi --}}
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Log Presensi</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ number_format($summaryStats['totalLogs']) }}
                    </p>
                    <span class="text-xs text-gray-500">Total entri dalam filter</span>
                </div>

                {{-- Kartu 2: Karyawan Unik Hadir --}}
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Karyawan Unik Hadir</h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ number_format($summaryStats['uniqueEmployees']) }}
                    </p>
                    <span class="text-xs text-gray-500">Jumlah karyawan yang absen</span>
                </div>

                {{-- Kartu 3: Total Terlambat --}}
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Terlambat</h4>
                    <p class="mt-2 text-3xl font-bold {{ $summaryStats['totalLate'] > 0 ? 'text-orange-600' : 'text-gray-900' }}">
                        {{ number_format($summaryStats['totalLate']) }}
                    </p>
                    <span class="text-xs text-gray-500">Total kejadian terlambat</span>
                </div>

                {{-- Kartu 4: Total Menit Terlambat --}}
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Menit Terlambat</h4>
                    <p class="mt-2 text-3xl font-bold {{ $summaryStats['totalMinutesLate'] > 0 ? 'text-red-600' : 'text-gray-900' }}">
                        {{ number_format($summaryStats['totalMinutesLate']) }}
                    </p>
                    <span class="text-xs text-gray-500">Akumulasi menit terlambat</span>
                </div>

            </div>

            {{-- FORM FILTER GACOR --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg"
                x-data="{
                    selectedStore: '{{ $filters['store_id'] }}',
                    selectedEmployee: '{{ $filters['employee_id'] }}',
                    employees: {{ json_encode($employees) }}, // Ambil daftar karyawan awal dari PHP

                    async fetchEmployees() {
                        if (!this.selectedStore) {
                            this.employees = []; // Kosongkan jika 'Semua Toko' dipilih
                            this.selectedEmployee = ''; // Reset pilihan karyawan
                            return;
                        }
                        
                        try {
                            const response = await fetch(`/admin/employees/by-store/${this.selectedStore}`);
                            if (!response.ok) throw new Error('Network response was not ok');
                            const data = await response.json();
                            this.employees = data; // Update daftar karyawan
                            
                            // Jika karyawan yang dipilih sebelumnya tidak ada di daftar baru, reset
                            if (!this.employees.find(emp => emp.id_employee == this.selectedEmployee)) {
                                this.selectedEmployee = '';
                            }
                        } catch (error) {
                            console.error('Error fetching employees:', error);
                            this.employees = []; // Kosongkan jika ada error
                        }
                    }
                }">
                <form method="GET" action="{{ route('admin.presence-recap.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    
                    {{-- Filter Toko --}}
                    <div>
                        <label for="store_id" class="block text-sm font-medium text-gray-700">Toko</label>
                        <select id="store_id" name="store_id" 
                                x-model="selectedStore" {{-- Ikat nilainya ke selectedStore --}}
                                @change="fetchEmployees()" {{-- Panggil fungsi saat berubah --}}
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            
                            <option value="">Semua Toko</opt    ion>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id_store }}"> {{-- 'selected' dikontrol Alpine --}}
                                    {{ $store->store_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Karyawan --}}
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700">Karyawan</label>
                        <select id="employee_id" name="employee_id" 
                                x-model="selectedEmployee" {{-- Ikat nilainya ke selectedEmployee --}}
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            
                            <option value="">Semua Karyawan</option>
                            {{-- Loop dinamis menggunakan data dari Alpine.js --}}
                            <template x-for="employee in employees" :key="employee.id_employee">
                                <option :value="employee.id_employee" x-text="employee.employee_name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Filter Tanggal Mulai --}}
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                        <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    {{-- Filter Tanggal Selesai --}}
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                        <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    {{-- Tombol Filter --}}
                    <div class="self-end">
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Filter
                        </button>
                    </div>

                </form>
            </div>

            {{-- TABEL HASIL REKAP --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Hasil Rekap</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toko</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal Masuk</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($logs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $log->employee->employee_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->store->store_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{-- Format tanggal 'gacor' --}}
                                            {{ $log->check_in_time->isoFormat('DD MMM YYYY, HH:mm') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->check_out_time ? $log->check_out_time->isoFormat('HH:mm') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->schedule ? \Carbon\Carbon::parse($log->schedule->jam_check_in)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($log->status == 'Tepat Waktu')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $log->status }}
                                                </span>
                                            @elseif ($log->status == 'Terlambat')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                    {{ $log->status }}
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ $log->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->notes }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            Tidak ada data rekap untuk filter ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Link Pagination --}}
                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>