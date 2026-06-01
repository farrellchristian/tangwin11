<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight truncate">
            {{ __('Tambah Toko Baru') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.stores.index') }}"
                    class="inline-flex items-center text-sm text-gray-600 hover:text-indigo-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-4 h-4 mr-1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                <div class="p-4 sm:p-6 text-gray-900">

                    <form method="POST" action="{{ route('admin.stores.store') }}">
                        @csrf

                        <div>
                            <label for="store_name" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                            <input type="text" name="store_name" id="store_name" value="{{ old('store_name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required autofocus>
                            @error('store_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="mt-6 border-t pt-4">
                            <h4 class="text-md font-medium text-gray-800">Pengaturan Presensi (Opsional)</h4>
                            <p class="text-xs text-gray-500 mb-2">Gunakan ini jika Anda ingin membatasi presensi hanya
                                dari WiFi toko.</p>

                            <div class="mt-4">
                                <label for="store_ip_address" class="block text-sm font-medium text-gray-700">IP Address Toko (Opsional)</label>
                                <div class="flex mt-1 shadow-sm rounded-md">
                                    <input type="text" name="store_ip_address" id="store_ip_address"
                                        value="{{ old('store_ip_address') }}"
                                        class="block w-full rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Contoh: 192.168.1.1">
                                    <button type="button" onclick="document.getElementById('store_ip_address').value = '{{ request()->ip() }}'"
                                        class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        <span>Deteksi IP</span>
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak pakai. Bisa diisi beberapa IP
                                    dipisah koma (cth: 192.168.1.1, 10.0.0.1)</p>
                                @error('store_ip_address')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="enable_ip_validation"
                                    class="block text-sm font-medium text-gray-700">Aktifkan Validasi IP?</label>
                                <select id="enable_ip_validation" name="enable_ip_validation"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="0" {{ old('enable_ip_validation', '0') == '0' ? 'selected' : '' }}>
                                        Tidak (Default)</option>
                                    <option value="1" {{ old('enable_ip_validation') == '1' ? 'selected' : '' }}>Ya,
                                        Aktifkan</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Jika "Ya", karyawan hanya bisa presensi jika IP
                                    mereka cocok dengan IP Toko.</p>
                                @error('enable_ip_validation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.stores.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Toko
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>