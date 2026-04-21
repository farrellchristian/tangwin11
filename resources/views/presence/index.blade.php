<x-app-layout>
    {{-- Gunakan min-h agar flexbox bisa menyesuaikan isi, overflow hanya diatur untuk desktop jika perlu --}}
    <div class="min-h-[calc(100vh-65px)] lg:h-[calc(100vh-65px)] bg-gray-50 lg:overflow-hidden flex flex-col" x-data="presenceClock()" x-init="startClock()">
        
        {{-- Container utama --}}
        <div class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:px-8 flex flex-col min-h-0 text-gray-900">
            
            {{-- Header Ringkas --}}
            <div class="flex justify-between items-center mb-4 sm:mb-6 shrink-0 pt-2 lg:pt-0">
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Presensi Karyawan</h2>
                    <p class="text-xs sm:text-sm text-gray-500">Check-in kehadiran Anda hari ini.</p>
                </div>
                <span class="px-2 py-1 sm:px-3 sm:py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-[10px] sm:text-xs font-bold shadow-sm flex items-center gap-1.5 sm:gap-2">
                    <svg class="w-3 h-3 sm:w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    {{ $store->store_name }}
                </span>
            </div>

            {{-- Content Grid (Isi Utama) --}}
            <div class="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 min-h-0">
                
                {{-- KOLOM KIRI: Jam & Info --}}
                <div class="flex flex-col gap-4 sm:gap-6 h-full lg:overflow-y-auto pr-0 lg:pr-2 custom-scrollbar">
                    
                    {{-- Clock Card --}}
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl shadow-lg p-6 sm:p-8 text-white relative overflow-hidden shrink-0 flex flex-col justify-center min-h-[180px] sm:min-h-[250px]">
                        <div class="absolute top-0 right-0 -mr-12 -mt-12 w-48 h-48 sm:w-64 sm:h-64 rounded-full bg-white opacity-10 blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-12 -mb-12 w-32 h-32 sm:w-48 sm:h-48 rounded-full bg-indigo-400 opacity-20 blur-2xl"></div>

                        <div class="relative z-10">
                            <p class="text-indigo-100 font-medium text-sm sm:text-lg mb-0.5 sm:mb-1 opacity-90" x-text="currentDate"></p>
                            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tighter mb-2 sm:mb-4 leading-none" x-text="currentTime"></h1>
                            
                            <div class="flex items-center gap-2 sm:gap-3 mt-2 sm:mt-4">
                                <div class="p-1.5 sm:p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[8px] sm:text-[10px] text-indigo-200 uppercase font-bold tracking-wider">Waktu Server</p>
                                    <p class="text-[10px] sm:text-xs font-semibold">WIB (Indonesia Barat)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Info Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 flex-1">
                        <h3 class="font-bold text-gray-800 mb-3 sm:mb-4 flex items-center text-xs sm:text-sm uppercase tracking-wide">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Info Presensi
                        </h3>
                        <ul class="space-y-3 sm:space-y-4 text-xs sm:text-sm text-gray-600">
                            <li class="flex gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                                <p>Status kehadiran tercatat otomatis saat tombol ditekan.</p>
                            </li>
                            <li class="flex gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                                <p>Keterlambatan dihitung berdasarkan jadwal shift toko.</p>
                            </li>
                            <li class="flex gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                                <p>Hubungi Admin jika salah pilih nama atau jadwal tidak sesuai.</p>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- KOLOM KANAN: Form --}}
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8 flex flex-col justify-center h-full">
                    
                    <div class="text-center mb-6 sm:mb-8">
                        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-indigo-50 mb-3 sm:mb-4 ring-4 ring-indigo-50">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">Form Check-In</h3>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Pilih nama Anda untuk melakukan absen masuk.</p>
                    </div>

                    {{-- NOTIFIKASI --}}
                    <div class="space-y-2 mb-4 sm:mb-6">
                        @if (session('late'))
                            <div class="p-3 bg-orange-50 border border-orange-200 rounded-lg flex items-center text-xs text-orange-700">
                                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <span class="font-semibold">{{ session('late') }}</span>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="p-3 bg-red-50 border border-red-200 rounded-lg flex items-center text-xs text-red-700">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span class="font-semibold">{{ session('error') }}</span>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('presence.check-in') }}" class="space-y-4 sm:space-y-5">
                        @csrf
                        <div>
                            <label for="id_employee" class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Karyawan</label>
                            <div class="relative text-gray-900">
                                <select id="id_employee" name="id_employee" class="block w-full pl-3 pr-10 py-3 text-sm sm:text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl shadow-sm bg-gray-50 hover:bg-white transition-colors cursor-pointer" required>
                                    <option value="" class="text-gray-900">-- Pilih Nama Anda --</option>
                                    @forelse ($employees as $employee)
                                        @php
                                            $logStatus = $todayLogs[$employee->id_employee] ?? null;
                                            $isDisabled = $logStatus !== null; 
                                        @endphp
                                        <option value="{{ $employee->id_employee }}" {{ $isDisabled ? 'disabled' : '' }} class="text-gray-900">
                                            {{ $employee->employee_name }} 
                                            @if ($isDisabled) (✅ Sudah) @endif
                                        </option>
                                    @empty
                                        <option value="" disabled class="text-gray-900">Tidak ada data</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full flex justify-center py-3.5 sm:py-4 px-4 border border-transparent rounded-xl shadow-lg text-sm sm:text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                            Submit Kehadiran
                        </button>
                    </form>

                    <div class="mt-8 sm:mt-auto pt-6 sm:pt-8 text-center pb-4 lg:pb-0">
                         <a href="{{ route('dashboard') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-400 hover:text-indigo-600 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Dashboard
                        </a>
                        <p class="text-[10px] text-gray-300 mt-3 sm:mt-4">© {{ date('Y') }} {{ config('app.name') }}</p>
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

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 20px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #d1d5db; }
    </style>
</x-app-layout>