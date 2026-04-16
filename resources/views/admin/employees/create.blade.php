<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight truncate">
            {{ __('Tambah Karyawan Baru') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-indigo-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-1.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                <div class="p-4 sm:p-6 text-gray-900">

                    {{-- Tambahkan enctype="multipart/form-data" jika ingin upload foto --}}
                    <form method="POST" action="{{ route('admin.employees.store') }}">
                        @csrf

                        <div>
                            <label for="id_store" class="block text-sm font-medium text-gray-700">Toko Penempatan</label>
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
                            <label for="employee_name" class="block text-sm font-medium text-gray-700">Nama Karyawan</label>
                            <input type="text" name="employee_name" id="employee_name" value="{{ old('employee_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('employee_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="position" class="block text-sm font-medium text-gray-700">Jabatan</label>
                            <input type="text" name="position" id="position" value="{{ old('position') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required placeholder="Contoh: Capster, Kasir">
                            @error('position')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon (Opsional)</label>
                            <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="08...">
                            @error('phone_number')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="join_date" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                            <input type="date" name="join_date" id="join_date" value="{{ old('join_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('join_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Input Foto (jika diperlukan nanti) --}}
                        {{-- <div class="mt-4">
                            <label for="photo_path" class="block text-sm font-medium text-gray-700">Foto (Opsional)</label>
                            <input type="file" name="photo_path" id="photo_path" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('photo_path')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div> --}}


                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.employees.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Karyawan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>