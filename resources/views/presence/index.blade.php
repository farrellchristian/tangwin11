<x-app-layout>
    {{-- Slot untuk Header (Judul Halaman) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Halaman Presensi (Toko: {{ $store->store_name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full sm:max-w-lg mx-auto bg-white shadow-xl overflow-hidden sm:rounded-lg" 
                 x-data="presenceClock()" x-init="startClock()">
                
                {{-- Ini adalah seluruh konten dari card lama kamu --}}
                <div class="px-6 py-8">
                    <div class="text-center mb-6">
                        <div class="inline-block p-3 bg-indigo-100 rounded-full">
                            {{-- Icon Presensi Baru --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-12 h-12 text-indigo-600" fill="currentColor">
                                <path d="M480.23-779.08q105.72 0 199.48 45.5 93.75 45.5 156.37 131.12 6.23 7.84 3.92 14.27-2.31 6.42-7.92 10.65-5.62 4.23-12.64 3.77-7.02-.45-12.29-7.77Q751.38-660.31 664.51-702q-86.86-41.69-184.28-41.69-97 0-182.38 42.07-85.39 42.08-141.77 120.08-5.62 8.23-12.85 8.85-7.23.61-12.85-4-5.65-4.23-6.86-10.58t3.63-13.19q62.39-84.23 155.15-131.42 92.77-47.2 197.93-47.2Zm.02 94q134.21 0 230.63 89.45 96.43 89.45 96.43 221.63 0 49.18-34.83 82.13-34.83 32.95-84.86 32.95-49.85 0-85.77-32.95-35.93-32.95-35.93-82.13 0-33.77-25.06-57.04-25.07-23.27-59.86-23.27-35.1 0-60.4 23.17-25.29 23.16-25.29 57.14 0 98.15 58.27 163.92 58.27 65.77 149.27 91.77 8.05 2.64 10.95 8.8 2.89 6.16.89 13.2-2 6.16-7.23 10.77-5.23 4.62-13.84 2.62-102.47-26-168.08-102.94-65.62-76.95-65.62-188.14 0-49.23 35.62-82.46 35.61-33.23 85.49-33.23 49.87 0 85.07 33.23 35.21 33.23 35.21 82.46 0 33.98 25.65 57.14 25.65 23.17 60.54 23.17 34.88 0 59.65-23.27 24.77-23.27 24.77-57.04 0-116.98-85.88-196.64-85.89-79.67-205.12-79.67t-204.8 79.78q-85.58 79.78-85.58 195.91 0 24.2 5.08 60.49 5.07 36.28 21.69 84.28 2.61 7.85-.31 13.89-2.92 6.04-10.15 9.04-7.23 3-13.77-.14-6.54-3.15-9.16-10.32-15.38-40.16-22.07-78.27-6.7-38.12-6.7-78.35 0-132.18 95.94-221.63 95.94-89.45 229.16-89.45Zm.75-192q63.49 0 124.01 15.5t117.07 44.5q8.61 4.62 9.92 11.04 1.31 6.42-1.31 12.66-2.61 6.23-9.04 9.46-6.42 3.23-14.5-1-52.61-28.16-109.68-42.46-57.07-14.31-116.66-14.31-58.58 0-114.96 14.27-56.39 14.27-108.54 42.5-6.08 3.84-13.12 1.73-7.04-2.12-10.27-8.96-3.23-6.85-1.8-12.77 1.42-5.93 8.26-10.16Q296-845.46 357-861.27q61-15.81 124-15.81Zm.02 289.39q92.21 0 158.25 61.73T705.31-374q0 7.96-4.87 12.83-4.86 4.86-12.82 4.86-6.85 0-12.27-4.86-5.43-4.87-5.43-12.83 0-75.77-56.06-127.04-56.07-51.27-132.85-51.27t-132.24 51.27q-55.46 51.27-55.46 127.01 0 81.8 28.38 138.49 28.39 56.69 82.39 114.39 6 6.07 5.42 13.11-.58 7.04-5.42 11.89-5.23 5.23-12.27 5.42-7.04.19-12.66-5.42-57.84-61.23-89.53-125.93-31.7-64.69-31.7-151.93 0-90.22 65.44-151.95 65.45-61.73 157.66-61.73Zm-1.48 196q7.92 0 12.84 5.31 4.93 5.3 4.93 12.38 0 76.18 54.57 124.94 54.58 48.75 127.74 48.75 7.92 0 18.92-1R 11-1 22.61-3 7.47-1.61 12.81 2.12 5.35 3.73 7.35 11.65 2 7.05-2.62 12.33-4.61 5.29-11.84 7.29-14.54 3.84-27.66 4.92-13.11 1.08-19.57 1.08-87.85 0-152.77-59.46-64.93-59.45-64.93-149.62 0-7.08 4.85-12.38 4.84-5.31 12.77-5.31Z"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-800 mt-3">Sistem Presensi</h1>
                        <p class="text-sm text-gray-500">{{ $store->store_name }}</p>
                    </div>

                    {{-- Jam Digital --}}
                    <div class="text-center my-6">
                        <p class="text-sm text-gray-600" x-text="currentDate"></p>
                        <p class="text-5xl font-bold text-gray-800 tracking-wider" x-text="currentTime"></p>
                    </div>

                    {{-- Form Presensi --}}
                    <form method="POST" action="{{ route('presence.check-in') }}">
                        @csrf
                        
                        {{-- Notifikasi Error/Sukses --}}
                        @if (session('success'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        {{-- NOTIFIKASI BARU UNTUK 'TERLAMBAT' (WARNA ORANYE) --}}
                        @if (session('late'))
                            <div class="mb-4 bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('late') }}</span>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        {{-- Dropdown Karyawan --}}
                        <div>
                            <label for="id_employee" class="block text-sm font-medium text-gray-700">Nama Karyawan:</label>
                            <select id="id_employee" name="id_employee" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">-- Pilih Nama Anda --</option>
                                {{-- 
                                  Catatan: Controller kamu harus mengirimkan variabel $employees 
                                  dan $todayLogs (yang sudah ada di controller-mu) 
                                --}}
                                @forelse ($employees as $employee)
                                    @php
                                        $logStatus = $todayLogs[$employee->id_employee] ?? null;
                                        $isDisabled = $logStatus !== null; 
                                    @endphp
                                    <option value="{{ $employee->id_employee }}" {{ $isDisabled ? 'disabled' : '' }}>
                                        {{ $employee->employee_name }}
                                        @if ($isDisabled)
                                            (Sudah Absen: {{ $logStatus }})
                                        @endif
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada karyawan aktif di toko ini</option>
                                @endforelse
                            </select>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="mt-6">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-50 transition ease-in-out duration-150 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Submit Presensi (Check-in)
                            </button>
                        </div>
                    </form>

                     {{-- Tombol Kembali ke Dashboard --}}
                     <div class="mt-4 text-center">
                        <a href="{{ route('dashboard') }}" class="w-full inline-flex justify-center items-center px-4 py-3 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                            Kembali ke Dashboard
                        </a>
                    </div>
                    
                    <p class="text-center text-xs text-gray-400 mt-8">
                        © {{ date('Y') }} {{ config('app.name', 'Tangwin Cut Studio') }}.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- 
      PENTING: Definisikan fungsi Alpine.js 'presenceClock()' di sini.
      <x-app-layout> sudah memuat library Alpine.js (via app.js), 
      kita hanya perlu menyediakan fungsinya agar 'x-data' bisa menemukannya.
    --}}
    <script>
        function presenceClock() {
            return {
                currentTime: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).replace(/\./g, ':'),
                currentDate: new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }),
                startClock() {
                    setInterval(() => {
                        this.currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).replace(/\./g, ':');
                        this.currentDate = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    }, 1000);
                }
            }
        }
    </script>
</x-app-layout>