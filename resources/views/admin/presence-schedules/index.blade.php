<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Setting Presensi') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

            @php
            $daysMap = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $todayDayOfWeek = \Carbon\Carbon::now()->dayOfWeek;
            @endphp

            {{-- 1. HEADER STATISTIK — 2-col mobile, 3-col desktop --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6 mb-4 sm:mb-8 mt-2 sm:mt-6">

                {{-- Card 1: Jadwal Aktif Hari Ini (spans full width on mobile) --}}
                <div class="col-span-2 md:col-span-1 relative overflow-hidden bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-4 sm:p-6 text-white shadow-lg group">
                    <div class="absolute right-0 top-0 h-32 w-32 bg-white/10 rounded-full -mr-10 -mt-10 blur-2xl group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-3 sm:mb-4">
                            <div class="p-1.5 sm:p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-indigo-100">Shift Hari Ini</span>
                        </div>
                        <div class="flex items-end gap-2">
                            <h3 class="text-3xl sm:text-4xl font-black">{{ $activeTodayCount }}</h3>
                            <span class="text-xs sm:text-sm font-medium text-indigo-200 mb-0.5 sm:mb-1">Jadwal</span>
                        </div>
                        <p class="text-[10px] sm:text-xs text-indigo-200 mt-1 sm:mt-2">Untuk hari {{ \Carbon\Carbon::now()->translatedFormat('l') }}</p>
                    </div>
                </div>

                {{-- Card 2: Total Jadwal Tersimpan --}}
                <div class="relative overflow-hidden bg-white rounded-2xl p-4 sm:p-6 border border-slate-100 shadow-sm group hover:shadow-md transition-all">
                    <div class="absolute right-0 top-0 h-24 w-24 bg-blue-50 rounded-full -mr-8 -mt-8 group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-3 sm:mb-4">
                            <div class="p-1.5 sm:p-2 bg-blue-50 rounded-lg text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 -960 960 960" fill="currentColor">
                                    <path d="M212.31-100Q182-100 161-121q-21-21-21-51.31v-535.38Q140-738 161-759q21-21 51.31-21h55.38v-84.61h61.54V-780h303.08v-84.61h60V-780h55.38Q778-780 799-759q21 21 21 51.31v535.38Q820-142 799-121q-21 21-51.31 21H212.31Zm0-60h535.38q4.62 0 8.46-3.85 3.85-3.84 3.85-8.46v-375.38H200v375.38q0 4.62 3.85 8.46 3.84 3.85 8.46 3.85ZM200-607.69h560v-100q0-4.62-3.85-8.46-3.84-3.85-8.46-3.85H212.31q-4.62 0-8.46 3.85-3.85 3.84-3.85 8.46v100Zm0 0V-720v112.31Zm280 210.77q-14.69 0-25.04-10.35-10.34-10.34-10.34-25.04 0-14.69 10.34-25.04 10.35-10.34 25.04-10.34t25.04 10.34q10.34 10.35 10.34 25.04 0 14.7-10.34 25.04-10.35 10.35-25.04 10.35Zm-160 0q-14.69 0-25.04-10.35-10.34-10.34-10.34-25.04 0-14.69 10.34-25.04 10.35-10.34 25.04-10.34t25.04 10.34q10.34 10.35 10.34 25.04 0 14.7-10.34 25.04-10.35 10.35-25.04 10.35Zm320 0q-14.69 0-25.04-10.35-10.34-10.34-10.34-25.04 0-14.69 10.34-25.04 10.35-10.34 25.04-10.34t25.04 10.34q10.34 10.35 10.34 25.04 0 14.7-10.34 25.04-10.35 10.35-25.04 10.35ZM480-240q-14.69 0-25.04-10.35-10.34-10.34-10.34-25.03 0-14.7 10.34-25.04 10.35-10.35 25.04-10.35t25.04 10.35q10.34 10.34 10.34 25.04 0 14.69-10.34 25.03Q494.69-240 480-240Zm-160 0q-14.69 0-25.04-10.35-10.34-10.34-10.34-25.03 0-14.7 10.34-25.04 10.35-10.35 25.04-10.35t25.04 10.35q10.34 10.34 10.34 25.04 0 14.69-10.34 25.03Q334.69-240 320-240Zm320 0q-14.69 0-25.04-10.35-10.34-10.34-10.34-25.03 0-14.7 10.34-25.04 10.35-10.35 25.04-10.35t25.04 10.35q10.34 10.34 10.34 25.04 0 14.69-10.34 25.03Q654.69-240 640-240Z" />
                                </svg>
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-slate-400">Total Master</span>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-slate-800">{{ $schedules->total() }}</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 mt-1 sm:mt-2">Jadwal tersimpan</p>
                    </div>
                </div>

                {{-- Card 3: Pintasan Cepat --}}
                <div class="relative overflow-hidden bg-white rounded-2xl p-4 sm:p-6 border border-slate-100 shadow-sm flex flex-col justify-center items-start">
                    <h4 class="font-bold text-slate-800 text-sm sm:text-base mb-1 sm:mb-2">Kelola Jadwal</h4>
                    <p class="text-[10px] sm:text-xs text-slate-500 mb-3 sm:mb-4">Atur jam masuk & pulang.</p>
                    <a href="{{ route('admin.presence-schedules.create') }}" class="w-full inline-flex justify-center items-center px-3 sm:px-4 py-2.5 sm:py-3 bg-slate-900 border border-transparent rounded-xl font-semibold text-[10px] sm:text-xs text-white uppercase tracking-widest hover:bg-slate-800 transition shadow-lg shadow-slate-900/20">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Jadwal
                    </a>
                </div>
            </div>

            {{-- 2. TOOLBAR FILTER --}}
            <div class="bg-white p-3 sm:p-4 rounded-xl shadow-sm border border-slate-100 mb-4 sm:mb-6 flex flex-col md:flex-row justify-between items-center gap-3 sm:gap-4">
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <div class="bg-slate-100 p-2 rounded-lg text-slate-500">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">Filter Data:</span>
                </div>

                <form method="GET" action="{{ route('admin.presence-schedules.index') }}" class="flex items-center gap-2 w-full md:w-auto flex-1 md:justify-end">
                    <div class="relative w-full md:w-64">
                        <select name="store_id" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2.5 text-sm border-slate-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg bg-slate-50 hover:bg-white transition cursor-pointer">
                            <option value="">Semua Toko</option>
                            @foreach ($stores as $store)
                            <option value="{{ $store->id_store }}" {{ $selectedStoreId == $store->id_store ? 'selected' : '' }}>
                                {{ $store->store_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            {{-- Alert Messages --}}
            


            {{-- 3. DATA LIST --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

                {{-- Table Header --}}
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between sm:items-center gap-2 sm:gap-0 bg-slate-50/30">
                    <div>
                        <h3 class="font-bold text-slate-800 text-base sm:text-lg">Daftar Jadwal</h3>
                        <p class="text-[10px] sm:text-xs text-slate-500">Mengatur jam kerja karyawan per cabang.</p>
                    </div>
                    {{-- Indikator Hari Ini --}}
                    <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-[10px] sm:text-xs font-bold text-indigo-700 self-start sm:self-auto">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        {{ \Carbon\Carbon::now()->translatedFormat('l') }}
                    </span>
                </div>

                {{-- ===== MOBILE: CARD LIST (visible < md) ===== --}}
                <div class="block md:hidden divide-y divide-slate-100">
                    @forelse ($schedules as $schedule)
                    @php
                    $isToday = ($schedule->day_of_week == $todayDayOfWeek);
                    $start = \Carbon\Carbon::parse($schedule->jam_check_in);
                    $end = \Carbon\Carbon::parse($schedule->jam_check_out);
                    $diffInMinutes = $start->diffInMinutes($end);
                    if ($diffInMinutes < 60) {
                        $durationString = $diffInMinutes . ' Mnt';
                    } else {
                        $hours = floor($diffInMinutes / 60);
                        $mins = $diffInMinutes % 60;
                        $durationString = $mins == 0 ? $hours . ' Jam' : $hours . 'J ' . $mins . 'M';
                    }
                    @endphp
                    <div class="p-4 space-y-3 {{ $isToday ? 'bg-indigo-50/30' : '' }}">

                        {{-- Row 1: Store + Day Badge --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="h-9 w-9 rounded-xl {{ $isToday ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-500' }} font-bold text-sm flex items-center justify-center flex-shrink-0 border {{ $isToday ? 'border-indigo-200' : 'border-slate-200' }}">
                                    {{ substr($schedule->store->store_name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-800">{{ $schedule->store->store_name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-slate-400">ID: #{{ $schedule->id_presence_schedule }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                @if($isToday)
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-indigo-600 text-white">NOW</span>
                                @endif
                                <span class="px-2 py-1 rounded-lg text-[11px] font-bold {{ $isToday ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ $daysMap[$schedule->day_of_week] ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        {{-- Row 2: Work Hours & Details --}}
                        <div class="bg-slate-50 rounded-xl p-3 grid grid-cols-3 gap-2">
                            {{-- Jam Masuk --}}
                            <div class="text-center">
                                <p class="text-[9px] text-slate-400 uppercase font-bold tracking-wider mb-1">Masuk</p>
                                <p class="text-sm font-bold text-slate-800 bg-white px-2 py-1.5 rounded-lg border border-slate-200">
                                    {{ $start->format('H:i') }}
                                </p>
                            </div>
                            {{-- Jam Pulang --}}
                            <div class="text-center">
                                <p class="text-[9px] text-slate-400 uppercase font-bold tracking-wider mb-1">Pulang</p>
                                <p class="text-sm font-bold text-slate-800 bg-white px-2 py-1.5 rounded-lg border border-slate-200">
                                    {{ $end->format('H:i') }}
                                </p>
                            </div>
                            {{-- Durasi --}}
                            <div class="text-center">
                                <p class="text-[9px] text-slate-400 uppercase font-bold tracking-wider mb-1">Durasi</p>
                                <p class="text-sm font-bold text-indigo-600 bg-indigo-50 px-2 py-1.5 rounded-lg border border-indigo-100">
                                    {{ $durationString }}
                                </p>
                            </div>
                        </div>

                        {{-- Row 3: Meta Info + Actions --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 flex-wrap">
                                {{-- Toleransi Badge --}}
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg border border-orange-100">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Toleransi {{ $schedule->late_threshold }}m
                                </span>
                                {{-- Status Badge --}}
                                @if ($schedule->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Aktif
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold bg-slate-50 text-slate-500 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Non-Aktif
                                </span>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.presence-schedules.edit', $schedule->id_presence_schedule) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-indigo-600 hover:border-indigo-200 transition active:scale-95" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.presence-schedules.destroy', $schedule->id_presence_schedule) }}" method="POST" onsubmit="return confirm('Yakin hapus jadwal ini?');">
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
                    <div class="py-14 flex flex-col items-center justify-center text-slate-400 px-4">
                        <div class="bg-slate-50 p-4 rounded-full mb-3">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-slate-900 font-bold">Belum Ada Jadwal</h3>
                        <p class="text-slate-500 text-sm mt-1 max-w-xs text-center">
                            {{ $selectedStoreId ? 'Toko ini belum memiliki jadwal tetap.' : 'Silakan pilih toko atau buat jadwal baru.' }}
                        </p>
                        <a href="{{ route('admin.presence-schedules.create') }}" class="mt-4 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
                            + Buat Jadwal Baru
                        </a>
                    </div>
                    @endforelse
                </div>

                {{-- ===== DESKTOP: TABLE (visible >= md) ===== --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400">
                            <tr>
                                <th class="px-6 py-4">Toko & ID</th>
                                <th class="px-6 py-4">Hari Shift</th>
                                <th class="px-6 py-4">Jam Kerja</th>
                                <th class="px-6 py-4">Toleransi</th>
                                <th class="px-6 py-4">Total Jam</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($schedules as $schedule)
                            @php
                            $isToday = ($schedule->day_of_week == $todayDayOfWeek);
                            $start = \Carbon\Carbon::parse($schedule->jam_check_in);
                            $end = \Carbon\Carbon::parse($schedule->jam_check_out);
                            $duration = $start->diffInHours($end);
                            @endphp
                            <tr class="hover:bg-slate-50 transition duration-150 {{ $isToday ? 'bg-indigo-50/30' : '' }}">

                                {{-- Kolom 1: Toko --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold shadow-sm border border-slate-200">
                                            {{ substr($schedule->store->store_name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $schedule->store->store_name ?? 'N/A' }}</p>
                                            <p class="text-xs text-slate-400">ID: #{{ $schedule->id_presence_schedule }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom 2: Hari --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 rounded-lg {{ $isToday ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-400' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-semibold {{ $isToday ? 'text-indigo-700' : 'text-slate-700' }}">
                                            {{ $daysMap[$schedule->day_of_week] ?? 'N/A' }}
                                        </span>
                                        @if($isToday)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-600 text-white">NOW</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Kolom 3: Jam Kerja --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3 text-sm">
                                        <div class="flex flex-col items-center">
                                            <span class="text-xs text-slate-400 font-medium uppercase">Masuk</span>
                                            <span class="font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded">
                                                {{ \Carbon\Carbon::parse($schedule->jam_check_in)->format('H:i') }}
                                            </span>
                                        </div>
                                        <span class="text-slate-300">→</span>
                                        <div class="flex flex-col items-center">
                                            <span class="text-xs text-slate-400 font-medium uppercase">Pulang</span>
                                            <span class="font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded">
                                                {{ \Carbon\Carbon::parse($schedule->jam_check_out)->format('H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom 3b: Toleransi --}}
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-slate-600 bg-orange-50 px-2 py-1 rounded border border-orange-100">
                                        {{ $schedule->late_threshold }} Mnt
                                    </span>
                                </td>

                                {{-- Kolom 4: Durasi --}}
                                <td class="px-6 py-4">
                                    @php
                                    $diffInMinutes = $start->diffInMinutes($end);
                                    if ($diffInMinutes < 60) {
                                        $durationString=$diffInMinutes . ' Mnt' ;
                                        } else {
                                        $hours=floor($diffInMinutes / 60);
                                        $mins=$diffInMinutes % 60;

                                        if ($mins==0) {
                                        $durationString=$hours . ' J' ;
                                        } else {
                                        $durationString=$hours . ' J ' . $mins . ' Mnt' ;
                                        }
                                        }
                                        @endphp

                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $durationString }}
                                        </span>
                                </td>

                                {{-- Kolom 5: Status --}}
                                <td class="px-6 py-4 text-center">
                                    @if ($schedule->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Aktif
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Non-Aktif
                                    </span>
                                    @endif
                                </td>

                                {{-- Kolom 6: Opsi --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.presence-schedules.edit', $schedule->id_presence_schedule) }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-indigo-600 hover:border-indigo-200 hover:shadow-sm transition" title="Edit Jadwal">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.presence-schedules.destroy', $schedule->id_presence_schedule) }}" method="POST" onsubmit="return confirm('Yakin hapus jadwal ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-red-600 hover:border-red-200 hover:shadow-sm transition" title="Hapus Jadwal">
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
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-slate-50 p-4 rounded-full mb-3">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-slate-900 font-bold">Belum Ada Jadwal</h3>
                                        <p class="text-slate-500 text-sm mt-1 max-w-xs">
                                            {{ $selectedStoreId ? 'Toko ini belum memiliki jadwal tetap.' : 'Silakan pilih toko atau buat jadwal baru.' }}
                                        </p>
                                        <a href="{{ route('admin.presence-schedules.create') }}" class="mt-4 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
                                            + Buat Jadwal Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-100 bg-slate-50">
                    {{ $schedules->links() }}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>