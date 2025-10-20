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

                <a href="#" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-8 h-8 mr-4 text-indigo-600">
                                <path d="M112.96-223.38v-62.16q0-27.34 13.88-46.54 13.89-19.2 38.1-30.92 51.02-24.54 101.41-39.02 50.38-14.48 121.19-14.48t120.94 14.48q50.14 14.48 101.68 39.03 24.11 11.87 37.97 31.02Q662-312.82 662-285.54v62.16H112.96Zm628.31 0v-63.39q0-34.27-14.21-64.16-14.21-29.89-39.06-51.26 27.73 6.38 53.73 16.4 25.99 10.01 52 22.98 24.89 12.73 39.29 32.63 14.4 19.89 14.4 43.41v63.39H741.27ZM387.54-504q-48.45 0-82.38-33.93-33.93-33.93-33.93-82.38 0-48.45 33.93-82.38 33.93-33.93 82.38-33.93 48.45 0 82.38 33.93 33.93 33.93 33.93 82.38 0 48.45-33.93 82.38Q435.99-504 387.54-504Zm279.69-116.66q0 48.03-33.93 82.1-33.92 34.06-82.38 34.06-1.8 0-4.84-.42t-5-.93q20.22-23.97 30.88-53.34 10.66-29.37 10.66-61.46 0-31.83-11.37-60.73-11.37-28.89-30.17-54 2.5-1.04 5.11-1.14 2.62-.1 4.73-.1 48.46 0 82.38 34.06 33.93 34.07 33.93 81.9ZM149.88-260.31h475.2v-25.13q0-14.36-7.04-24.87-7.04-10.5-25.39-20.5-44.7-23.81-93.42-36.29-48.73-12.48-111.52-12.48-62.96 0-111.85 12.48-48.9 12.48-93.55 36.29-18.73 10-25.58 20.58-6.85 10.57-6.85 24.69v25.23Zm237.6-280.61q32.94 0 56.19-23.19t23.25-56.14q0-32.94-23.19-56.19-23.18-23.25-56.13-23.25t-56.2 23.19q-23.25 23.18-23.25 56.13t23.19 56.2q23.19 23.25 56.14 23.25Zm.06 280.61Zm0-360Z"/>
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
                                <path d="M627.69-127.88q-7.77 0-13.02-5.26t-5.25-13.04q0-7.78 5.25-13.2 5.25-5.43 13.02-5.43h110.96v-52.31H627.69q-7.77 0-13.02-5.25-5.25-5.26-5.25-13.04 0-7.78 5.25-13.21 5.25-5.42 13.02-5.42h110.96v-52.31H627.69q-7.77 0-13.02-5.25-5.25-5.26-5.25-13.04 0-7.78 5.25-13.21 5.25-5.42 13.02-5.42h110.96v-52.31H627.69q-7.77 0-13.02-5.25-5.25-5.26-5.25-13.04 0-7.78 5.25-13.21 5.25-5.42 13.02-5.42h110.96v-52.31H627.69q-7.77 0-13.02-5.26-5.25-5.25-5.25-13.03 0-7.79 5.25-13.21 5.25-5.42 13.02-5.42h110.96v-52.31H627.69q-7.77 0-13.02-5.26-5.25-5.25-5.25-13.04 0-7.78 5.25-13.2 5.25-5.42 13.02-5.42h129.23q24.7 0 41.68 20.82 16.98 20.83 16.98 45.52v358.47q0 24.69-16.98 41.48-16.98 16.79-41.68 16.79H627.69ZM352.31-358.65q66.61 0 114.17-65.37 47.56-65.36 47.56-155.98 0-90.62-47.56-156.17-47.56-65.56-114.17-65.56-66.62 0-113.98 65.56-47.37 65.55-47.37 156.17 0 90.62 47.37 155.98 47.36 65.37 113.98 65.37Zm0 230.77q-29.08 0-45.96-21.7-16.89-21.69-13.97-49.11l14.97-130.27q-66.2-19.58-109.75-89.17-43.56-69.6-43.56-161.87 0-107.72 57.72-183.19 57.72-75.46 140.37-75.46 82.64 0 140.74 75.46 58.09 75.47 58.09 183.19 0 92.27-43.75 161.87-43.75 69.59-109.56 89.17l14.58 130.27q3.31 27.42-14.32 49.11-17.63 21.7-45.6 21.7Z"/>
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
                                <path d="M598.65-646.46h158.43-158.43Zm-395.57 0H566.15h-34.11 11.23-340.19Zm15.11-36.92h523.35l-53.23-65.08q-3.85-3.85-8.85-6.16-5-2.3-10.38-2.3H290.07q-5.39 0-10.39 2.3-5 2.31-8.84 6.16l-52.65 65.08Zm180.46 243.69L480-480.31l81.73 40.62v-206.77H398.65v206.77Zm160.62 273.54H229.54q-26.16 0-44.77-18.44-18.62-18.44-18.62-44.33v-427.93q0-10.07 3.68-20.07 3.67-10 10.52-18.46l61.62-75.54q8.26-10.83 20.63-16.88 12.36-6.05 27.19-6.05h378.88q14.83 0 27.48 5.96 12.66 5.96 21.43 17.04l62.46 77q6.46 8.78 10.13 18.93 3.68 10.15 3.68 20.38v149.5q-7-1.38-14.71-1.63-7.7-.25-15.14-.25h-7.08v-139.54H598.65v211.11q-8.54 6.88-18.08 18.34-9.53 11.47-14.42 21.39l-86.15-43-118.27 59.39v-267.23H203.08v418.77q0 10.77 6.92 17.69 6.92 6.92 17.69 6.92h315.58q2.96 9.91 7.15 19.01 4.2 9.11 8.85 17.92Zm185.19 30v-120h-120v-36.93h120v-120h36.92v120h120v36.93h-120v120h-36.92Z"/>
                            </svg>

                            <div>
                                <h3 class="text-lg font-semibold">Manajemen Produk</h3>
                                <p class="text-gray-500">Atur daftar produk (pomade, powder) dan stok.</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="#" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="w-8 h-8 mr-4 text-indigo-600">
                                <path d="M577.15-377.85q-26.61-51.77-87.03-69.57-60.41-17.81-125.24-17.81-64.46 0-125.94 17.81-61.48 17.8-86.56 69.57h424.77Zm-472.07 36.93q0-84.89 81.3-123.06 81.31-38.17 178.39-38.17 97.08 0 178.38 38.17 81.31 38.17 81.31 123.06H105.08Zm0 126.88v-36.92h519.38v36.92H105.08Zm596.3 127.89v-36.93h57.85l55.39-538.46H459.08l-4.77-36.92h181.54v-158.77h36.92v158.77h182.15l-58.7 576.67q-2.16 15.99-14.05 25.82-11.88 9.82-28.17 9.82h-52.62Zm0-36.93h57.85-57.85Zm-571.5 36.93q-10.65 0-17.73-7.08-7.07-7.08-7.07-17.54v-12.31h519.38v12.31q0 10.46-7.08 17.54-7.07 7.08-17.72 7.08H129.88Zm234.89-291.7Z"/>
                            </svg>

                            <div>
                                <h3 class="text-lg font-semibold">Manajemen Makanan</h3>
                                <p class="text-gray-500">Atur daftar F&B (kopi, snack) dan stok.</p>
                            </div>
                        </div>
                    </div>
                </a>

            </div> </div>
    </div>
</x-app-layout>