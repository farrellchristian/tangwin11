<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight truncate">
            {{ __('Manajemen Toko') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- === BLOK TAB NAVIGASI === --}}
            <div class="mb-4">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <a href="{{ route('admin.users.index') }}" 
                           class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Akun Login Kasir
                        </a>

                        <a href="{{ route('admin.stores.index') }}" 
                           class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" 
                           aria-current="page">
                            Profil Toko
                        </a>
                    </nav>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-500">Kelola daftar toko dan status operasional cabang.</p>
            </div>
            {{-- === AKHIR BLOK TAB BARU === --}}

            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.stores.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                    {{ __('+ Tambah Toko') }}
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
             @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    
                    {{-- Tampilan Desktop (Tabel) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Toko</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Dibuat</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($stores as $store)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $store->store_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($store->is_active)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                  Aktif
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                  Non-Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $store->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.stores.edit', $store->id_store) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            
                                            {{-- Form Nonaktifkan (Destroy) --}}
                                            @if ($store->is_active && strtolower($store->store_name) !== 'office')
                                                <form action="{{ route('admin.stores.destroy', $store->id_store) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan toko ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Nonaktifkan</button>
                                                </form>
                                            @elseif (strtolower($store->store_name) === 'office')
                                                 <span class="text-gray-400 ml-4">(Default)</span>
                                            @else
                                                <span class="text-gray-400 ml-4">Dinonaktifkan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            Data toko belum tersedia.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Tampilan Mobile (Card) --}}
                    <div class="block md:hidden space-y-4">
                        @forelse ($stores as $store)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm flex flex-col space-y-3">
                                <div class="flex justify-between items-start">
                                    <div class="pr-2">
                                        <h3 class="text-base font-semibold text-gray-900 truncate">{{ $store->store_name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $store->created_at->format('d M Y') }}</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @if ($store->is_active)
                                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                              Aktif
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                              Non-Aktif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="pt-3 border-t border-gray-100 flex justify-end space-x-2">
                                    <a href="{{ route('admin.stores.edit', $store->id_store) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 text-sm font-medium rounded-md hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </a>
                                    
                                    @if ($store->is_active && strtolower($store->store_name) !== 'office')
                                        <form action="{{ route('admin.stores.destroy', $store->id_store) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan toko ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 text-sm font-medium rounded-md hover:bg-red-100 transition-colors">
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @elseif (strtolower($store->store_name) === 'office')
                                         <span class="px-3 py-1.5 text-gray-400 text-sm">(Default)</span>
                                    @else
                                        <span class="px-3 py-1.5 text-gray-400 text-sm">Dinonaktifkan</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-500 text-sm border border-gray-200 rounded-lg bg-gray-50">
                                Data toko belum tersedia.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $stores->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>