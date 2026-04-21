<x-app-layout>
    <div class="py-6 sm:py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-md mx-auto px-3 sm:px-6 lg:px-8"> 
            
            {{-- Alert Section --}}
            @if(session('error'))
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl flex items-start gap-2.5 sm:gap-3 shadow-sm">
                    <div class="shrink-0 p-1 bg-red-100 rounded-full">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-bold text-red-800">Akses Ditolak</p>
                        <p class="text-[10px] sm:text-xs text-red-600 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:shadow-2xl rounded-2xl sm:rounded-3xl border border-gray-100">
                <div class="p-5 sm:p-8">
                    
                    {{-- Header Ringkas --}}
                    <div class="text-center mb-8 sm:mb-10">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 shadow-lg shadow-indigo-100">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Pilih Capster Utama</h3>
                        <p class="text-xs sm:text-sm text-gray-400 mt-1">Siapa yang melayani transaksi ini?</p>
                    </div>

                    @if ($employees->isEmpty())
                        <div class="py-10 text-center">
                            <p class="text-xs sm:text-sm text-gray-400 italic">Belum ada capster terdaftar.</p>
                        </div>
                    @else
                        <div class="space-y-3 sm:space-y-4">
                            @foreach ($employees as $employee)
                                @php
                                    $hasCheckedIn = $employee->hasCheckedInToday();
                                    $canSelect = $isAdmin || $hasCheckedIn;
                                    $cardClass = $hasCheckedIn 
                                        ? 'bg-white border-gray-100 hover:border-indigo-500 hover:shadow-xl hover:-translate-y-0.5' 
                                        : ($isAdmin ? 'bg-amber-50/30 border-dashed border-amber-200 hover:border-amber-400' : 'bg-gray-50 border-gray-100 opacity-60');
                                @endphp

                                <a href="{{ $canSelect ? route('pos.transaction', ['store' => $storeId, 'employee' => $employee->id_employee]) : '#' }}"
                                   @if(!$canSelect) onclick="event.preventDefault();" @endif
                                   class="group relative flex items-center p-3.5 sm:p-4 border-2 rounded-xl sm:rounded-2xl transition-all duration-300 {{ $cardClass }} {{ !$canSelect ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                                    
                                    {{-- Avatar --}}
                                    <div class="relative shrink-0 mr-3 sm:mr-4">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center font-black text-base sm:text-lg shadow-sm {{ $hasCheckedIn ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                            {{ strtoupper(substr($employee->employee_name, 0, 1)) }}
                                        </div>
                                        {{-- Dot Status --}}
                                        <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 sm:w-4 sm:h-4 rounded-full border-2 border-white {{ $hasCheckedIn ? 'bg-green-500' : 'bg-amber-400' }}"></span>
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5 sm:gap-2">
                                            <span class="text-sm sm:text-base font-bold text-gray-900 group-hover:text-indigo-600 transition-colors truncate">
                                                {{ $employee->employee_name }}
                                            </span>
                                            @if(!$hasCheckedIn && $isAdmin)
                                                <span class="px-1.5 py-0.5 bg-gray-800 text-white text-[8px] sm:text-[9px] font-black rounded-md uppercase tracking-tighter shrink-0">Override</span>
                                            @endif
                                        </div>
                                        <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $employee->position }}</p>
                                    </div>

                                    {{-- Status Badge --}}
                                    <div class="shrink-0 ml-2">
                                        @if($hasCheckedIn)
                                            <div class="px-2.5 py-1 bg-green-50 text-green-600 text-[9px] sm:text-[10px] font-black rounded-lg border border-green-100 uppercase">Ready</div>
                                        @else
                                            <div class="px-2.5 py-1 bg-amber-50 text-amber-600 text-[9px] sm:text-[10px] font-black rounded-lg border border-amber-100 uppercase">Off</div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Footer Action --}}
                        <div class="mt-8 sm:mt-10 pt-6 border-t border-gray-50 flex flex-col items-center">
                            @if(Auth::user()->role === 'kasir')
                                <a href="{{ route('presence.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-6 py-3.5 bg-indigo-50 text-indigo-700 text-sm font-bold rounded-2xl hover:bg-indigo-100 transition shadow-sm border border-indigo-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Buka Halaman Presensi
                                </a>
                            @endif
                            <p class="text-[9px] sm:text-[10px] text-gray-400 mt-4 text-center px-4 leading-relaxed uppercase font-bold tracking-widest opacity-60 italic">Pastikan Capster Sudah Absen Sebelum Melayani</p>
                        </div>
                    @endif
                    
                </div>
            </div>

            {{-- Back Link --}}
            <div class="mt-6 sm:mt-8 text-center pb-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-gray-400 hover:text-indigo-600 transition tracking-tight">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    KEMBALI KE DASHBOARD
                </a>
            </div>

        </div>
    </div>
</x-app-layout>