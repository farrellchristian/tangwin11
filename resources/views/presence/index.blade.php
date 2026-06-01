<x-app-layout>
    <div class="min-h-[calc(100vh-65px)] lg:h-[calc(100vh-65px)] bg-gray-50 lg:overflow-hidden flex flex-col"
        x-data="presencePage()" x-init="startClock()">

        <div class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:px-8 flex flex-col min-h-0 text-gray-900">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-4 shrink-0 pt-3 lg:pt-0 pl-12 lg:pl-0">
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Presensi Karyawan</h2>
                    <p class="text-xs sm:text-sm text-gray-500">Check-in kehadiran Anda hari ini.</p>
                </div>
                <span
                    class="px-3 py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    {{ $store->store_name }}
                </span>
            </div>

            {{-- Content Area --}}
            <div class="flex-1 flex flex-col lg:flex-row gap-4 sm:gap-6 min-h-0">

                {{-- KOLOM KIRI / UTAMA: Jam, Notif, Form Karyawan --}}
                <div class="flex-1 flex flex-col gap-4 sm:gap-5 min-h-0">

                    {{-- Ultra-Compact Clock Banner --}}
                    <div class="bg-indigo-600 rounded-xl px-4 py-2.5 text-white flex justify-between items-center shadow-sm shrink-0">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex items-baseline gap-2">
                                <span class="text-xl sm:text-2xl font-black tracking-tight" x-text="currentTime"></span>
                                <span class="text-xs text-indigo-200 font-medium hidden sm:inline" x-text="currentDate"></span>
                            </div>
                        </div>
                        <div class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider">
                            Waktu Server WIB
                        </div>
                    </div>

                    {{-- NOTIFIKASI --}}
                    <div class="space-y-2 shrink-0">
                        @if (session('late'))
                            <div class="p-3.5 bg-orange-50 border border-orange-200 rounded-xl flex items-start gap-3 text-sm text-orange-800 shadow-sm">
                                <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="font-bold">{{ session('late') }}</span>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="p-3.5 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3 text-sm text-red-800 shadow-sm">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-bold">{{ session('error') }}</span>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="p-3.5 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3 text-sm text-green-800 shadow-sm">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-bold">{{ session('success') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Form Pilih Karyawan --}}
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 flex flex-col min-h-[400px] flex-1 overflow-hidden">
                        {{-- Header Form & Search --}}
                        <div class="px-5 pt-5 pb-4 border-b border-gray-100 shrink-0 bg-gray-50/50">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center ring-4 ring-white shadow-sm">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Pilih Nama Anda</h3>
                                    <p class="text-xs font-medium text-gray-500">Ketuk kartu nama Anda untuk absen masuk.</p>
                                </div>
                            </div>

                            <div class="relative shadow-sm rounded-xl">
                                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" x-model="search" placeholder="Cari nama karyawan..."
                                    class="w-full pl-10 pr-4 py-3 text-sm border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white transition-all outline-none font-medium" />
                            </div>
                        </div>

                        {{-- Daftar Karyawan --}}
                        <form method="POST" action="{{ route('presence.check-in') }}" class="flex-1 flex flex-col min-h-0 bg-white">
                            @csrf
                            <input type="hidden" name="id_employee" x-model="selectedEmployee" />

                            <div class="flex-1 overflow-y-auto p-5 custom-scrollbar">
                                @php $employees_data = $employees->toArray(); @endphp

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4"
                                    x-data="{ employees: {{ json_encode($employees_data) }}, todayLogs: {{ json_encode($todayLogs) }} }">

                                    <template x-for="emp in employees.filter(e => e.employee_name.toLowerCase().includes(search.toLowerCase()))" :key="emp.id_employee">
                                        <div @click="!todayLogs[emp.id_employee] && (selectedEmployee = selectedEmployee == emp.id_employee ? null : emp.id_employee)"
                                            :class="{
                                                'border-indigo-500 bg-indigo-50/80 shadow-md ring-2 ring-indigo-500/30 scale-[1.02] z-10': selectedEmployee == emp.id_employee,
                                                'border-gray-200 hover:border-indigo-300 hover:bg-gray-50 hover:shadow-sm cursor-pointer': !todayLogs[emp.id_employee],
                                                'bg-gray-50/80 border-gray-100 opacity-60 cursor-not-allowed': todayLogs[emp.id_employee]
                                            }"
                                            class="relative flex items-center gap-3.5 p-4 rounded-xl border-2 transition-all duration-200 select-none">

                                            {{-- Avatar --}}
                                            <div :class="todayLogs[emp.id_employee] ? 'bg-green-100 text-green-600' : (selectedEmployee == emp.id_employee ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'bg-gray-100 text-gray-500')"
                                                class="w-12 h-12 rounded-full flex items-center justify-center font-black text-lg flex-shrink-0 transition-all duration-200">
                                                <span x-show="!todayLogs[emp.id_employee]" x-text="emp.employee_name.charAt(0).toUpperCase()"></span>
                                                <svg x-show="todayLogs[emp.id_employee]" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>

                                            {{-- Info --}}
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-extrabold text-gray-900 truncate" x-text="emp.employee_name"></p>
                                                <p class="text-[10px] font-bold uppercase tracking-widest mt-0.5"
                                                    :class="todayLogs[emp.id_employee] ? 'text-green-600' : 'text-gray-400'"
                                                    x-text="todayLogs[emp.id_employee] ? 'Sudah Hadir' : 'CAPSTER'">
                                                </p>
                                            </div>

                                            {{-- Indicator check --}}
                                            <div x-show="!todayLogs[emp.id_employee]"
                                                :class="selectedEmployee == emp.id_employee ? 'bg-indigo-600 border-indigo-600' : 'bg-white border-gray-300'"
                                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all duration-200">
                                                <svg x-show="selectedEmployee == emp.id_employee" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Empty state search --}}
                                    <template x-if="employees.filter(e => e.employee_name.toLowerCase().includes(search.toLowerCase())).length === 0">
                                        <div class="col-span-full py-16 flex flex-col items-center justify-center text-gray-400">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-gray-700">Karyawan tidak ditemukan</p>
                                            <p class="text-xs text-gray-500 mt-1">Coba cari dengan kata kunci lain</p>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="p-5 border-t border-gray-100 shrink-0 bg-gray-50/50">
                                <button type="submit"
                                    :disabled="!selectedEmployee"
                                    :class="selectedEmployee
                                        ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-xl shadow-indigo-200 hover:-translate-y-0.5 cursor-pointer ring-4 ring-indigo-50'
                                        : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                                    class="w-full sm:w-auto sm:min-w-[250px] mx-auto flex justify-center items-center gap-2 py-3.5 px-6 rounded-xl text-sm font-bold transition-all duration-300 transform active:translate-y-0">
                                    <svg x-show="selectedEmployee" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="selectedEmployee ? 'Submit Kehadiran' : 'Pilih nama Anda di atas'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN / BAWAH: Info Presensi --}}
                <div class="lg:w-80 xl:w-96 flex flex-col gap-4 shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex-1">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="font-extrabold text-gray-900 text-sm tracking-wide uppercase">Petunjuk Presensi</h3>
                        </div>
                        
                        <div class="space-y-5">
                            <div class="flex gap-3 items-start">
                                <div class="w-6 h-6 rounded-full bg-indigo-50 flex items-center justify-center shrink-0 mt-0.5">
                                    <span class="text-xs font-black text-indigo-600">1</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 mb-0.5">Pilih Nama</p>
                                    <p class="text-xs text-gray-500 leading-relaxed">Cari dan ketuk kartu nama Anda pada daftar di samping.</p>
                                </div>
                            </div>
                            <div class="flex gap-3 items-start">
                                <div class="w-6 h-6 rounded-full bg-indigo-50 flex items-center justify-center shrink-0 mt-0.5">
                                    <span class="text-xs font-black text-indigo-600">2</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 mb-0.5">Waktu Otomatis</p>
                                    <p class="text-xs text-gray-500 leading-relaxed">Jam kehadiran Anda akan dicatat sesuai dengan waktu server saat ini.</p>
                                </div>
                            </div>
                            <div class="flex gap-3 items-start">
                                <div class="w-6 h-6 rounded-full bg-green-50 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 mb-0.5">Indikator Hadir</p>
                                    <p class="text-xs text-gray-500 leading-relaxed">Karyawan yang sudah presensi hari ini akan ditandai dengan ikon centang hijau.</p>
                                </div>
                            </div>
                            <div class="flex gap-3 items-start">
                                <div class="w-6 h-6 rounded-full bg-red-50 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 mb-0.5">Bantuan</p>
                                    <p class="text-xs text-gray-500 leading-relaxed">Hubungi Admin jika Anda salah memilih nama atau jadwal tidak sesuai.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col gap-3">
                            <a href="{{ route('dashboard') }}" class="w-full flex justify-center items-center gap-2 px-4 py-2.5 bg-white border-2 border-gray-200 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function presencePage() {
            return {
                currentTime: '',
                currentDate: '',
                search: '',
                selectedEmployee: null,
                startClock() {
                    this.updateTime();
                    setInterval(() => { this.updateTime(); }, 1000);
                },
                updateTime() {
                    this.currentTime = new Date().toLocaleTimeString('id-ID', {
                        hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
                    }).replace(/\./g, ':');
                    this.currentDate = new Date().toLocaleDateString('id-ID', {
                        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                    });
                }
            }
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 20px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #d1d5db; }
    </style>
</x-app-layout>