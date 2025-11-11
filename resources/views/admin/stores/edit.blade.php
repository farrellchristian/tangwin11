<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mr-4">
                {{ __('Edit Toko') }}: {{ $store->store_name }}
            </h2>

            <!-- Tombol Kembali -->
            <a href="{{ route('admin.stores.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2 sm:mt-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Manajemen Toko
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Form -->
                    <form method="POST" action="{{ route('admin.stores.update', $store->id_store) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nama Toko -->
                        <div>
                            <label for="store_name" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                            <input type="text" name="store_name" id="store_name" value="{{ old('store_name', $store->store_name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm 
                                          {{ strtolower($store->store_name) === 'office' ? 'bg-gray-100 cursor-not-allowed' : '' }}" 
                                   {{ strtolower($store->store_name) === 'office' ? 'readonly' : '' }} required autofocus>
                            @if(strtolower($store->store_name) === 'office')
                                <p class="mt-1 text-xs text-gray-500">Toko "Office" tidak bisa diubah namanya.</p>
                            @endif
                            @error('store_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Status Aktif -->
                        <div class="mt-4">
                            <label for="is_active" class="block text-sm font-medium text-gray-700">Status Toko</label>
                            <select id="is_active" name="is_active" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm
                                          {{ strtolower($store->store_name) === 'office' ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                    {{ strtolower($store->store_name) === 'office' ? 'disabled' : '' }}>
                                <option value="1" {{ old('is_active', $store->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $store->is_active) == 0 ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                             @if(strtolower($store->store_name) === 'office')
                                <input type="hidden" name="is_active" value="1" /> {{-- Pastikan 'Office' selalu submit '1' (aktif) --}}
                                <p class="mt-1 text-xs text-gray-500">Toko "Office" tidak bisa dinonaktifkan.</p>
                            @endif
                            @error('is_active')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6 border-t pt-4">
                             <h4 class="text-md font-medium text-gray-800">Pengaturan Presensi (Opsional)</h4>
                             <p class="text-xs text-gray-500 mb-2">Gunakan ini jika Anda ingin membatasi presensi hanya dari WiFi toko.</p>

                             <!-- IP Address Toko -->
                             <div class="mt-4">
                                <label for="store_ip_address" class="block text-sm font-medium text-gray-700">IP Address Toko (Opsional)</label>
                                {{-- Isi value dengan data lama: old('...', $store->...) --}}
                                <input type="text" name="store_ip_address" id="store_ip_address" value="{{ old('store_ip_address', $store->store_ip_address) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: 192.168.1.1">
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak pakai. Bisa diisi beberapa IP dipisah koma (cth: 192.168.1.1, 10.0.0.1)</p>
                                @error('store_ip_address')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                             <!-- Aktifkan Validasi IP -->
                            <div class="mt-4">
                                <label for="enable_ip_validation" class="block text-sm font-medium text-gray-700">Aktifkan Validasi IP?</label>
                                <select id="enable_ip_validation" name="enable_ip_validation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    {{-- Isi value dengan data lama: old('...', $store->...) --}}
                                    <option value="0" {{ old('enable_ip_validation', $store->enable_ip_validation) == 0 ? 'selected' : '' }}>Tidak (Default)</option>
                                    <option value="1" {{ old('enable_ip_validation', $store->enable_ip_validation) == 1 ? 'selected' : '' }}>Ya, Aktifkan</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Jika "Ya", karyawan hanya bisa presensi jika IP mereka cocok dengan IP Toko.</p>
                                @error('enable_ip_validation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.stores.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Perbarui Toko
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>