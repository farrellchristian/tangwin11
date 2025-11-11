<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Informasi & Manajemen Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <p class="font-semibold">Selamat Datang di Pusat Manajemen Data.</p>
                    <p class="text-gray-600">Dari sini, Anda dapat mengelola semua data master untuk barbershop, termasuk karyawan, layanan, produk, dan makanan/minuman.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('admin.users.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            {{-- Ikon Baru (Google Material Symbol - account_circle) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-8 h-8 mr-4 text-indigo-600">
                                <path d="M720-60v-120H600v-60h120v-120h60v120h120v60H780v120h-60ZM104.62-170v-240H60v-60l43.08-200h573.84L720-470v60h-44.62v125.38h-59.99V-410H444.62v240h-340Zm60-60h220v-180h-220v180Zm-43.39-240h537.54-537.54Zm-18.15-260v-60h573.84v60H103.08Zm18.15 260h537.54l-30.54-140H151.77l-30.54 140Z"/>
                            </svg>

                            <div>
                                <h3 class="text-lg font-semibold">Manajemen Akun Kasir & Toko</h3>
                                <p class="text-gray-500">Tambah, edit, dan non-aktifkan akun login kasir & toko.</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.employees.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-8 h-8 mr-4 text-indigo-600">
                                <path d="M503.85-494.31q25.53-27.77 37.77-63.77 12.23-36 12.23-74.23 0-38.23-12.23-74.23-12.24-36-37.77-63.77 52.69 6.08 87.5 45.5 34.8 39.43 34.8 92.5 0 53.08-34.8 92.5-34.81 39.42-87.5 45.5Zm210 306.62v-93.85q0-32.71-13.31-62.24t-37.77-50.68q46 15.31 84.69 41.31t38.69 71.61v93.85h-72.3ZM786.15-450v-80h-80v-60h80v-80h60v80h80v60h-80v80h-60Zm-452.3-42.31q-57.75 0-98.88-41.12-41.12-41.13-41.12-98.88 0-57.75 41.12-98.87 41.13-41.13 98.88-41.13 57.75 0 98.87 41.13 41.13 41.12 41.13 98.87 0 57.75-41.13 98.88-41.12 41.12-98.87 41.12Zm-300 304.62v-88.93q0-29.38 15.96-54.42 15.96-25.04 42.65-38.5 59.31-29.07 119.66-43.61 60.34-14.54 121.73-14.54 61.38 0 121.73 14.54 60.34 14.54 119.65 43.61 26.69 13.46 42.65 38.5 15.97 25.04 15.97 54.42v88.93h-600Zm300-364.62q33 0 56.5-23.5t23.5-56.5q0-33-23.5-56.5t-56.5-23.5q-33 0-56.5 23.5t-23.5 56.5q0 33 23.5 56.5t56.5 23.5Zm-240 304.62h480v-28.93q0-12.15-7.04-22.5-7.04-10.34-19.12-16.88-51.69-25.46-105.41-38.58-53.73-13.11-108.43-13.11-54.71 0-108.43 13.11Q171.69-341.46 120-316q-12.08 6.54-19.11 16.88-7.04 10.35-7.04 22.5v28.93Zm240-384.62Zm0 384.62Z"/>
                            </svg>

                            <div>
                                <h3 class="text-lg font-semibold">Manajemen Karyawan</h3>
                                <p class="text-gray-500">Tambah, edit, dan non-aktifkan data capster.</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.services.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-8 h-8 mr-4 text-indigo-600">
                                <path d="M633.85-83.08q-12.75 0-21.38-8.63-8.62-8.63-8.62-21.38 0-12.76 8.62-21.37 8.63-8.62 21.38-8.62h114.61v-44.61H633.85q-12.75 0-21.38-8.63-8.62-8.63-8.62-21.39 0-12.75 8.62-21.37 8.63-8.61 21.38-8.61h114.61v-44.62H633.85q-12.75 0-21.38-8.63-8.62-8.63-8.62-21.38 0-12.76 8.62-21.37 8.63-8.62 21.38-8.62h114.61v-44.61H633.85q-12.75 0-21.38-8.63-8.62-8.63-8.62-21.39 0-12.75 8.62-21.37 8.63-8.61 21.38-8.61h114.61v-44.62H633.85q-12.75 0-21.38-8.63-8.62-8.62-8.62-21.38t8.62-21.37q8.63-8.62 21.38-8.62h114.61v-44.61H633.85q-12.75 0-21.38-8.63-8.62-8.63-8.62-21.38 0-12.76 8.62-21.37 8.63-8.62 21.38-8.62h144.61q29.15 0 49.58 22.35 20.42 22.34 20.42 51.5v439.22q0 29.15-20.42 49.58-20.43 20.42-49.58 20.42H633.85ZM336.16-360q66 0 113-65t47-155q0-90-47-155t-113-65q-66 0-113 65t-47 155q0 90 47 155t113 65Zm0 276.92q-39.16 0-63.62-29.15-24.46-29.16-19.38-67.54l15.23-134.46q-66.85-26.85-109.54-99.27-42.69-72.42-42.69-166.5 0-116.67 64.14-198.33Q244.44-860 336.06-860q91.63 0 155.86 81.67 64.23 81.66 64.23 198.33 0 94.08-42.69 166.5t-109.54 99.27l15.23 134.46q5.46 38.38-19.46 67.54-24.93 29.15-63.53 29.15Z"/>
                            </svg>

                            <div>
                                <h3 class="text-lg font-semibold">Manajemen Layanan</h3>
                                <p class="text-gray-500">Atur daftar layanan (hair cut, shaving) dan harga.</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.products.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-8 h-8 mr-4 text-indigo-600">
                                <path d="M620-643.85h140-140Zm-420 0h349.92-25.69 13.23H200Zm15.62-59.99H744l-43.62-51.93q-1.92-1.92-4.42-3.08-2.5-1.15-5.19-1.15H268.85q-2.69 0-5.2 1.15-2.5 1.16-4.42 3.08l-43.61 51.93ZM400-445.77l80-40 80 40v-198.08H400v198.08ZM571.61-140h-359.3q-29.83 0-51.07-21.24Q140-182.48 140-212.31v-467.46q0-12.65 4.12-24.4 4.11-11.75 12.34-21.6l56.16-67.92q9.84-12.7 24.61-19.5Q252-820 268.52-820h422.19q16.52 0 31.43 6.81 14.92 6.8 24.86 19.5L803.54-725q8.23 10.01 12.34 21.83 4.12 11.82 4.12 24.56v173.3q-13.62-4.69-28.23-7.15-14.62-2.46-29.46-2.46H760v-128.93H620v179.54q-23.08 13.85-41.58 35.08-18.5 21.23-28.5 45.92L480-418.08l-140 70v-295.77H200v431.54q0 5.39 3.46 8.85t8.85 3.46h325.15q5.79 17.1 14.47 31.97 8.68 14.87 19.68 28.03Zm160.31 11.92v-120h-120v-60h120v-120h60v120h120v60h-120v120h-60Z"/>
                            </svg>

                            <div>
                                <h3 class="text-lg font-semibold">Manajemen Produk</h3>
                                <p class="text-gray-500">Atur daftar produk (pomade, powder) dan stok.</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.foods.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-8 h-8 mr-4 text-indigo-600">
                                <path d="M554.16-412.31q-29.31-49.61-85.95-67.11t-117.06-17.5q-60.23 0-117.34 17.5-57.12 17.5-85.66 67.11h406.01Zm-485.31 60q0-98.23 86.77-151.42 86.77-53.19 195.53-53.19 108.77 0 195.54 53.19t86.77 151.42H68.85Zm0 146.16v-60h564.61v60H68.85ZM713.46-60v-60h56l56-552.31H454.23l-7.69-60h192.31v-160h59.99v160h192.31l-62.92 625.08q-2.62 20.77-18.15 34Q794.54-60 773.77-60h-60.31Zm0-60h56-56Zm-612.3 60q-13.74 0-23.02-9.29-9.29-9.29-9.29-23.02V-120h564.61v27.69q0 13.73-9.29 23.02T601.15-60H101.16Zm249.99-352.31Z"/>
                            </svg>

                            <div>
                                <h3 class="text-lg font-semibold">Manajemen Makanan</h3>
                                <p class="text-gray-500">Atur daftar F&B (kopi, snack) dan stok.</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.payment-methods.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center">
                        {{-- Ikon Baru (Google Material Symbol - credit card) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-8 h-8 mr-4 text-indigo-600">
                            <path d="M550-451.54q-41.92 0-70.96-29.04Q450-509.62 450-551.54q0-41.92 29.04-70.96 29.04-29.04 70.96-29.04 41.92 0 70.96 29.04Q650-593.46 650-551.54q0 41.92-29.04 70.96-29.04 29.04-70.96 29.04ZM286.15-327.69q-29.82 0-51.06-21.24-21.24-21.24-21.24-51.07v-303.08q0-29.82 21.24-51.06 21.24-21.24 51.06-21.24h527.69q29.83 0 51.07 21.24 21.24 21.24 21.24 51.06V-400q0 29.83-21.24 51.07-21.24 21.24-51.07 21.24H286.15Zm60-60h407.7q0-29.92 21.24-51.12Q796.33-460 826.15-460v-183.08q-29.92 0-51.11-21.24-21.19-21.24-21.19-51.06h-407.7q0 29.92-21.24 51.11-21.24 21.19-51.06 21.19V-460q29.92 0 51.11 21.24 21.19 21.24 21.19 51.07Zm420.77 200H146.16q-29.83 0-51.07-21.24Q73.85-230.17 73.85-260v-396.15h60V-260q0 4.61 3.84 8.46 3.85 3.85 8.47 3.85h620.76v60Zm-480.77-200h-12.3V-715.38h12.3q-5 0-8.65 3.65-3.65 3.65-3.65 8.65V-400q0 5 3.65 8.65 3.65 3.66 8.65 3.66Z"/>
                        </svg>

                        <div>
                            <h3 class="text-lg font-semibold">Metode Pembayaran</h3>
                            <p class="text-gray-500">Atur metode pembayaran (Cash, QRIS, dll.).</p>
                        </div>
                    </div>
                </div>
            </a>

            </div> </div>
    </div>
</x-app-layout>