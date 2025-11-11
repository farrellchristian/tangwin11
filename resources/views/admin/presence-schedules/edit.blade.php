    <x-app-layout>
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mr-4">
                    {{ __('Edit Jadwal Presensi') }}
                </h2>

                <!-- Tombol Kembali -->
                <a href="{{ route('admin.presence-schedules.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2 sm:mt-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        
                        <!-- Form -->
                        <form method="POST" action="{{ route('admin.presence-schedules.update', $schedule->id_presence_schedule) }}">
                            @csrf
                            @method('PUT') {{-- Method untuk update --}}

                            <!-- Pilihan Toko -->
                            <div>
                                <label for="id_store" class="block text-sm font-medium text-gray-700">Toko</label>
                                <select id="id_store" name="id_store" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Pilih Toko</option>
                                    @foreach ($stores as $store)
                                        {{-- Isi dengan data lama --}}
                                        <option value="{{ $store->id_store }}" {{ old('id_store', $schedule->id_store) == $store->id_store ? 'selected' : '' }}>
                                            {{ $store->store_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_store')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hari dalam Seminggu -->
                            <div class="mt-4">
                                <label for="day_of_week" class="block text-sm font-medium text-gray-700">Hari</label>
                                <select id="day_of_week" name="day_of_week" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Pilih Hari</option>
                                    @foreach ($daysOfWeek as $dayValue => $dayName)
                                        {{-- Isi dengan data lama --}}
                                        <option value="{{ $dayValue }}" {{ old('day_of_week', $schedule->day_of_week) == $dayValue ? 'selected' : '' }}>
                                            {{ $dayName }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('day_of_week')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jam Check-In -->
                            <div class="mt-4">
                                <label for="jam_check_in" class="block text-sm font-medium text-gray-700">Jam Masuk (Check-in)</label>
                                {{-- Isi dengan data lama, format H:i --}}
                                <input type="time" name="jam_check_in" id="jam_check_in" value="{{ old('jam_check_in', \Carbon\Carbon::parse($schedule->jam_check_in)->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                @error('jam_check_in')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Jam Check-Out -->
                            <div class="mt-4">
                                <label for="jam_check_out" class="block text-sm font-medium text-gray-700">Jam Pulang (Check-out)</label>
                                {{-- Isi dengan data lama, format H:i --}}
                                <input type="time" name="jam_check_out" id="jam_check_out" value="{{ old('jam_check_out', \Carbon\Carbon::parse($schedule->jam_check_out)->format('H:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                @error('jam_check_out')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Aktif -->
                            <div class="mt-4">
                                <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="is_active" name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    {{-- Isi dengan data lama --}}
                                    <option value="1" {{ old('is_active', $schedule->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active', $schedule->is_active) == 0 ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                                @error('is_active')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tombol Submit -->
                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('admin.presence-schedules.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                    Batal
                                </a>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Perbarui Jadwal
                                </button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>