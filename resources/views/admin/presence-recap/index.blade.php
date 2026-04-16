<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekap Presensi') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            {{-- STATISTIK — 2x2 Grid di Mobile, 4 kolom di Desktop --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

                {{-- Kartu 1: Total Log Presensi --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Total Log</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-0.5">{{ number_format($summaryStats['totalLogs']) }}</h3>
                        <span class="text-[10px] text-gray-400 hidden sm:inline">Total entri dalam filter</span>
                    </div>
                    <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                    </div>
                </div>

                {{-- Kartu 2: Karyawan Unik Hadir --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Karyawan</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-0.5">{{ number_format($summaryStats['uniqueEmployees']) }}</h3>
                        <span class="text-[10px] text-gray-400 hidden sm:inline">Unik yang absen</span>
                    </div>
                    <div class="p-2.5 bg-blue-50 rounded-xl text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                </div>

                {{-- Kartu 3: Total Terlambat --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Terlambat</p>
                        <h3 class="text-2xl font-bold {{ $summaryStats['totalLate'] > 0 ? 'text-orange-500' : 'text-gray-800' }} mt-0.5">{{ number_format($summaryStats['totalLate']) }}</h3>
                        <span class="text-[10px] text-gray-400 hidden sm:inline">Total kejadian</span>
                    </div>
                    <div class="p-2.5 bg-orange-50 rounded-xl text-orange-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                </div>

                {{-- Kartu 4: Total Menit Terlambat --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Menit Telat</p>
                        <h3 class="text-2xl font-bold {{ $summaryStats['totalMinutesLate'] > 0 ? 'text-red-500' : 'text-gray-800' }} mt-0.5">{{ number_format($summaryStats['totalMinutesLate']) }}</h3>
                        <span class="text-[10px] text-gray-400 hidden sm:inline">Akumulasi menit</span>
                    </div>
                    <div class="p-2.5 bg-red-50 rounded-xl text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>

            </div>

            {{-- FORM FILTER --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
                x-data="{
                    open: false,
                    selectedStore: '{{ $filters['store_id'] }}',
                    selectedEmployee: '{{ $filters['employee_id'] }}',
                    employees: {{ json_encode($employees) }},

                    async fetchEmployees() {
                        if (!this.selectedStore) {
                            this.employees = [];
                            this.selectedEmployee = '';
                            return;
                        }
                        
                        try {
                            const response = await fetch(`/admin/employees/by-store/${this.selectedStore}`);
                            if (!response.ok) throw new Error('Network response was not ok');
                            const data = await response.json();
                            this.employees = data;
                            
                            if (!this.employees.find(emp => emp.id_employee == this.selectedEmployee)) {
                                this.selectedEmployee = '';
                            }
                        } catch (error) {
                            console.error('Error fetching employees:', error);
                            this.employees = [];
                        }
                    }
                }">

                {{-- Filter Header (clickable on mobile) --}}
                <button @click="open = !open" type="button" class="w-full flex items-center justify-between p-4 md:hidden">
                    <div class="flex items-center gap-2">
                        <div class="bg-slate-100 p-2 rounded-lg text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Filter Data</span>
                        @if($filters['store_id'] || $filters['employee_id'] || $filters['date_from'] || $filters['date_to'])
                        <span class="px-1.5 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold">Aktif</span>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                {{-- Filter Content --}}
                <div class="md:block" :class="open ? 'block' : 'hidden'" x-cloak>
                    <form method="GET" action="{{ route('admin.presence-recap.index') }}" class="p-4 pt-0 md:pt-4 space-y-3 md:space-y-0 md:grid md:grid-cols-5 md:gap-4">

                        {{-- Filter Toko --}}
                        <div>
                            <label for="store_id" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Toko</label>
                            <select id="store_id" name="store_id"
                                x-model="selectedStore"
                                @change="fetchEmployees()"
                                class="block w-full rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-gray-50 hover:bg-white transition">
                                <option value="">Semua Toko</option>
                                @foreach ($stores as $store)
                                <option value="{{ $store->id_store }}">
                                    {{ $store->store_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Karyawan --}}
                        <div>
                            <label for="employee_id" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Karyawan</label>
                            <select id="employee_id" name="employee_id"
                                x-model="selectedEmployee"
                                class="block w-full rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-gray-50 hover:bg-white transition">
                                <option value="">Semua Karyawan</option>
                                <template x-for="employee in employees" :key="employee.id_employee">
                                    <option :value="employee.id_employee" x-text="employee.employee_name"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Filter Tanggal — Side-by-side on mobile --}}
                        <div class="grid grid-cols-2 gap-2 md:contents">
                            <div>
                                <label for="date_from" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Dari</label>
                                <input type="date" id="date_from" name="date_from" value="{{ $filters['date_from'] }}" class="block w-full rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-gray-50 hover:bg-white transition">
                            </div>
                            <div>
                                <label for="date_to" class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Sampai</label>
                                <input type="date" id="date_to" name="date_to" value="{{ $filters['date_to'] }}" class="block w-full rounded-lg border-gray-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 bg-gray-50 hover:bg-white transition">
                            </div>
                        </div>

                        {{-- Tombol Filter --}}
                        <div class="flex gap-2 md:self-end">
                            <button type="submit" class="flex-1 md:w-full inline-flex justify-center items-center gap-1.5 py-2.5 px-4 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('admin.presence-recap.index') }}" class="py-2.5 px-4 bg-white border border-gray-200 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-50 transition md:hidden">
                                Reset
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            {{-- HASIL REKAP --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">

                {{-- Section Header --}}
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 bg-gray-50/30 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-800 text-base sm:text-lg">Hasil Rekap</h3>
                        <p class="text-[10px] sm:text-xs text-slate-500">Data kehadiran karyawan.</p>
                    </div>
                    <span class="text-[10px] sm:text-xs text-gray-400 font-medium">{{ $logs->total() }} data</span>
                </div>

                {{-- ===== MOBILE: CARD LIST (visible < md) ===== --}}
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse ($logs as $log)
                    <div class="p-4 space-y-3">

                        {{-- Row 1: Employee + Status --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 font-bold text-sm flex items-center justify-center flex-shrink-0">
                                    {{ substr($log->employee->employee_name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $log->employee->employee_name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-gray-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"></path>
                                        </svg>
                                        {{ $log->store->store_name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Status Badge --}}
                            @if ($log->status == 'Tepat Waktu')
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-green-100 text-green-700 border border-green-200">
                                ✓ Tepat Waktu
                            </span>
                            @elseif ($log->status == 'Terlambat')
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-orange-100 text-orange-700 border border-orange-200">
                                ⚠ Terlambat
                            </span>
                            @else
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                {{ $log->status }}
                            </span>
                            @endif
                        </div>

                        {{-- Row 2: Check-in & Schedule Details --}}
                        <div class="bg-gray-50 rounded-xl p-3 grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[9px] text-gray-400 uppercase font-bold tracking-wider mb-1">Check-in</p>
                                <p class="text-sm font-bold text-gray-800">{{ $log->check_in_time->format('H:i') }}</p>
                                <p class="text-[10px] text-gray-400">{{ $log->check_in_time->translatedFormat('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-400 uppercase font-bold tracking-wider mb-1">Jadwal Masuk</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $log->schedule ? \Carbon\Carbon::parse($log->schedule->jam_check_in)->format('H:i') : '-' }}
                                </p>
                                <p class="text-[10px] text-gray-400">Waktu seharusnya</p>
                            </div>
                        </div>

                        {{-- Row 3: Notes (only if exists) --}}
                        @if($log->notes)
                        <div class="flex items-start gap-2 px-1">
                            <svg class="w-3.5 h-3.5 text-gray-300 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            <p class="text-xs text-gray-500 italic">{{ $log->notes }}</p>
                        </div>
                        @endif

                    </div>
                    @empty
                    <div class="py-14 flex flex-col items-center justify-center text-gray-400 px-4">
                        <div class="bg-gray-50 p-4 rounded-full mb-3">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-900 font-bold">Tidak ada data</h3>
                        <p class="text-gray-500 text-sm mt-1 max-w-xs text-center">Tidak ada data rekap untuk filter ini. Coba ubah filter di atas.</p>
                    </div>
                    @endforelse
                </div>

                {{-- ===== DESKTOP: TABLE (visible >= md) ===== --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-slate-600">
                        <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-400">
                            <tr>
                                <th class="px-6 py-4">Karyawan</th>
                                <th class="px-6 py-4">Toko</th>
                                <th class="px-6 py-4">Check-in</th>
                                <th class="px-6 py-4">Jadwal Masuk</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 font-bold text-sm flex items-center justify-center flex-shrink-0">
                                            {{ substr($log->employee->employee_name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="font-semibold text-gray-900">{{ $log->employee->employee_name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $log->store->store_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $log->check_in_time->isoFormat('DD MMM YYYY, HH:mm') }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $log->schedule ? \Carbon\Carbon::parse($log->schedule->jam_check_in)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($log->status == 'Tepat Waktu')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        {{ $log->status }}
                                    </span>
                                    @elseif ($log->status == 'Terlambat')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                        {{ $log->status }}
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        {{ $log->status }}
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 max-w-[200px] truncate">
                                    {{ $log->notes ?? '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-14 text-center text-gray-400">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    <p class="text-sm font-medium">Tidak ada data rekap untuk filter ini.</p>
                                    <p class="text-xs mt-1">Coba ubah filter di atas untuk melihat data.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-100 bg-gray-50/30">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>