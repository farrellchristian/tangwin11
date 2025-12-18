<x-app-layout>
    {{-- Layout utama menggunakan h-screen (tinggi layar penuh) dan overflow-hidden (cegah scroll body) --}}
    <div class="h-[calc(100vh-65px)] bg-gray-50 overflow-hidden flex flex-col" x-data="presenceClock()" x-init="startClock()">
        
        {{-- Container utama, padding disesuaikan agar tidak mepet --}}
        <div class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 flex flex-col">
            
            {{-- Header Ringkas --}}
            <div class="flex justify-between items-center mb-6 shrink-0">
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Presensi Karyawan</h2>
                    <p class="text-sm text-gray-500">Check-in kehadiran Anda hari ini.</p>
                </div>
                <span class="px-3 py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    {{ $store->store_name }}
                </span>
            </div>

            {{-- Content Grid (Isi Utama) --}}
            <div class="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-6 min-h-0">
                
                {{-- KOLOM KIRI: Jam & Info (Scrollable jika konten panjang, tapi dalam container ini saja) --}}
                <div class="flex flex-col gap-6 h-full overflow-y-auto pr-2 custom-scrollbar">
                    
                    {{-- Clock Card --}}
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden shrink-0 flex flex-col justify-center min-h-[280px]">
                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10 blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full bg-indigo-400 opacity-20 blur-2xl"></div>

                        <div class="relative z-10">
                            <p class="text-indigo-100 font-medium text-lg mb-1 opacity-90" x-text="currentDate"></p>
                            <h1 class="text-6xl sm:text-7xl font-black tracking-tighter mb-4 leading-none" x-text="currentTime"></h1>
                            
                            <div class="flex items-center gap-3 mt-4">
                                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-indigo-200 uppercase font-bold tracking-wider">Waktu Server</p>
                                    <p class="text-xs font-semibold">WIB (Indonesia Barat)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Info Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex-1">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center text-sm uppercase tracking-wide">
                            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Info Presensi
                        </h3>
                        <ul class="space-y-4 text-sm text-gray-600">
                            <li class="flex gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-2 shrink-0"></div>
                                <p>Status kehadiran tercatat otomatis saat tombol ditekan.</p>
                            </li>
                            <li class="flex gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-2 shrink-0"></div>
                                <p>Keterlambatan dihitung berdasarkan jadwal shift toko.</p>
                            </li>
                            <li class="flex gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-2 shrink-0"></div>
                                <p>Hubungi Admin jika salah pilih nama atau jadwal tidak sesuai.</p>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- KOLOM KANAN: Form (Center Vertically) --}}
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 flex flex-col justify-center h-full">
                    
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-50 mb-4 ring-4 ring-indigo-50">
                            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Form Check-In</h3>
                        <p class="text-sm text-gray-500 mt-1">Pilih nama Anda untuk melakukan absen masuk.</p>
                    </div>

                    {{-- NOTIFIKASI (Compact) --}}
                    <div class="space-y-2 mb-6">
                        @if (session('success'))
                            <div class="p-3 bg-green-50 border border-green-200 rounded-lg flex items-center text-sm text-green-700 animate-pulse">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="font-semibold">{{ session('success') }}</span>
                            </div>
                        @endif
                        @if (session('late'))
                            <div class="p-3 bg-orange-50 border border-orange-200 rounded-lg flex items-center text-sm text-orange-700">
                                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <span class="font-semibold">{{ session('late') }}</span>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="p-3 bg-red-50 border border-red-200 rounded-lg flex items-center text-sm text-red-700">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span class="font-semibold">{{ session('error') }}</span>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('presence.check-in') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label for="id_employee" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Karyawan</label>
                            <div class="relative">
                                <select id="id_employee" name="id_employee" class="block w-full pl-4 pr-10 py-3.5 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl shadow-sm bg-gray-50 hover:bg-white transition-colors cursor-pointer" required>
                                    <option value="">-- Pilih Nama Anda --</option>
                                    @forelse ($employees as $employee)
                                        @php
                                            $logStatus = $todayLogs[$employee->id_employee] ?? null;
                                            $isDisabled = $logStatus !== null; 
                                        @endphp
                                        <option value="{{ $employee->id_employee }}" {{ $isDisabled ? 'disabled' : '' }}>
                                            {{ $employee->employee_name }} 
                                            @if ($isDisabled) (✅ Sudah) @endif
                                        </option>
                                    @empty
                                        <option value="" disabled>Tidak ada data</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                            Submit Kehadiran
                        </button>
                    </form>

                    <div class="mt-auto pt-8 text-center">
                         <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-indigo-600 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Dashboard
                        </a>
                        <p class="text-xs text-gray-300 mt-4">© {{ date('Y') }} {{ config('app.name') }}</p>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        function presenceClock() {
            return {
                currentTime: '',
                currentDate: '',
                startClock() {
                    this.updateTime();
                    setInterval(() => { this.updateTime(); }, 1000);
                },
                updateTime() {
                    this.currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).replace(/\./g, ':');
                    this.currentDate = new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                }
            }
        }
    </script>

    {{-- CSS tambahan untuk scrollbar halus jika konten overflow di layar kecil --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 20px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #d1d5db; }
    </style>
</x-app-layout>