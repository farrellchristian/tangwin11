<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kasir / Point of Sale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Konten dasar, bisa diisi nanti --}}
                    <p>Memuat pilihan toko...</p>

                    {{-- Alpine.js: Muncul otomatis jika isAdmin true --}}
                    <div x-data="{ showModal: {{ $isAdmin ? 'true' : 'false' }} }"
                         x-show="showModal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50 overflow-y-auto"
                         aria-labelledby="modal-title"
                         role="dialog"
                         aria-modal="true"
                         style="display: none;" {{-- Hindari flicker --}}
                         >
                        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="showModal"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                                 aria-hidden="true"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div x-show="showModal"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 class="inline-block w-full max-w-sm p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                        Pilih Toko
                                    </h3>
                                    {{-- Tombol close, sementara tidak bisa ditutup --}}
                                    {{-- <button @click="showModal = false" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button> --}}
                                </div>

                                <div class="mt-4 space-y-2">
                                    @forelse ($stores as $store)
                                        {{-- Link ke langkah selanjutnya (Pilih Karyawan) --}}
                                        {{-- Kita perlu definisikan route 'pos.select-employee' nanti --}}
                                        <a href="{{ route('pos.select-employee', ['store' => $store->id_store]) }}"
                                           class="block w-full px-4 py-3 text-center text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                            {{ $store->store_name }}
                                        </a>
                                    @empty
                                        <p class="text-center text-gray-500">Tidak ada toko tersedia.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div> </div>
            </div>
        </div>
    </div>
</x-app-layout>