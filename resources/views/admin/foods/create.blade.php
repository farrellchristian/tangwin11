<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight truncate">
            {{ __('Tambah Makanan/Minuman Baru') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.foods.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-indigo-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-1.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                <div class="p-4 sm:p-6 text-gray-900">

                    <form method="POST" action="{{ route('admin.foods.store') }}">
                        @csrf

                        <div>
                            <label for="id_store" class="block text-sm font-medium text-gray-700">Toko</label>
                            <select id="id_store" name="id_store" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Pilih Toko</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id_store }}" {{ old('id_store') == $store->id_store ? 'selected' : '' }}>
                                        {{ $store->store_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_store')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="food_name" class="block text-sm font-medium text-gray-700">Nama Makanan/Minuman</label>
                            <input type="text" name="food_name" id="food_name" value="{{ old('food_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('food_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4"
                             x-data="{
                                 formattedPrice: '{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}',
                                 rawPrice: '{{ old('price', '') }}',
                                 formatNumber() {
                                     let raw = this.formattedPrice.replace(/[^0-9]/g, '');
                                     this.rawPrice = raw;
                                     this.formattedPrice = new Intl.NumberFormat('id-ID').format(raw) || '';
                                 }
                             }"
                             x-init="formatNumber()"
                        >
                            <label for="price_display" class="block text-sm font-medium text-gray-700">Harga</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                  <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="text"
                                       id="price_display"
                                       x-model="formattedPrice"
                                       @input="formatNumber"
                                       class="block w-full rounded-md border-gray-300 pl-10 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="0"
                                       required
                                       inputmode="numeric">
                                <input type="hidden" name="price" id="price" x-model="rawPrice">
                            </div>
                            @error('price')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="stock_available" class="block text-sm font-medium text-gray-700">Stok Tersedia</label>
                            <input type="number" name="stock_available" id="stock_available" value="{{ old('stock_available', 0) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required min="0">
                            @error('stock_available')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.foods.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Makanan/Minuman
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>