<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.presence-recap.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Presensi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-12">
        <div class="max-w-3xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                <div class="p-5 sm:p-8">
                    
                    <div class="mb-6 sm:mb-8">
                        <h3 class="text-lg font-bold text-gray-900">Koreksi Data Kehadiran</h3>
                        <p class="text-[11px] sm:text-sm text-gray-500 mt-1">Gunakan form ini untuk memperbaiki kesalahan input presensi. Status keterlambatan akan dihitung ulang secara otomatis.</p>
                    </div>

                    <form action="{{ route('admin.presence-recap.update', $log->id_presence_log) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Input Karyawan --}}
                        <div class="space-y-2">
                            <x-input-label for="id_employee" value="Karyawan" class="text-gray-700 font-bold" />
                            <select name="id_employee" id="id_employee" class="block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id_employee }}" {{ $log->id_employee == $employee->id_employee ? 'selected' : '' }}>
                                        {{ $employee->employee_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_employee')" />
                        </div>

                        {{-- Input Jadwal --}}
                        <div class="space-y-2">
                            <x-input-label for="id_presence_schedule" value="Tautkan ke Jadwal (Opsional)" class="text-gray-700 font-bold" />
                            <select name="id_presence_schedule" id="id_presence_schedule" class="block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition">
                                <option value="">Tanpa Jadwal (Selalu Tepat Waktu)</option>
                                @foreach($schedules as $sch)
                                    <option value="{{ $sch->id_presence_schedule }}" {{ $log->id_presence_schedule == $sch->id_presence_schedule ? 'selected' : '' }}>
                                        Shift: {{ \Carbon\Carbon::parse($sch->jam_check_in)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->jam_check_out)->format('H:i') }} 
                                        @if($sch->late_threshold > 0) (Toleransi: {{ $sch->late_threshold }} mnt) @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400">Pilih jadwal yang sesuai agar sistem bisa menghitung status keterlambatan.</p>
                            <x-input-error :messages="$errors->get('id_presence_schedule')" />
                        </div>

                        {{-- Input Waktu --}}
                        <div class="space-y-2">
                            <x-input-label for="check_in_time" value="Waktu Check-In" class="text-gray-700 font-bold" />
                            <input type="datetime-local" name="check_in_time" id="check_in_time" 
                                value="{{ \Carbon\Carbon::parse($log->check_in_time)->format('Y-m-d\TH:i') }}"
                                class="block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition" />
                            <x-input-error :messages="$errors->get('check_in_time')" />
                        </div>

                        {{-- Input Catatan --}}
                        <div class="space-y-2">
                            <x-input-label for="notes" value="Catatan / Alasan Koreksi" class="text-gray-700 font-bold" />
                            <textarea name="notes" id="notes" rows="3" class="block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition" placeholder="Contoh: Koreksi jam karena gangguan WiFi toko">{{ $log->notes }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" />
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-6 flex flex-col sm:flex-row items-center justify-end gap-3">
                            <a href="{{ route('admin.presence-recap.index') }}" class="w-full sm:w-auto text-center px-6 py-3 sm:py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition order-2 sm:order-1">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-10 py-3 sm:py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition transform active:scale-95 order-1 sm:order-2">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            {{-- Info Box --}}
            <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-2xl flex gap-3 items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-bold text-blue-800">Tips Koreksi</h4>
                    <p class="text-xs text-blue-600 mt-0.5 leading-relaxed">
                        Sistem akan membandingkan <strong>Waktu Check-In</strong> dengan <strong>Jadwal</strong> yang dipilih. 
                        Jika jadwal memiliki nilai Toleransi 0, maka jam berapapun setelah jam masuk akan dianggap terlambat.
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
