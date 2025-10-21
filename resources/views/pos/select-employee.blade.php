<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pilih Capster Utama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8"> {{-- Max width md agar tidak terlalu lebar --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-medium text-center mb-4">Pilih Capster yang Melayani:</h3>

                    @if ($employees->isEmpty())
                        <p class="text-center text-gray-500">Tidak ada capster aktif yang terdaftar untuk toko ini.</p>
                        
                        {{-- Tampilkan link HANYA jika yang login adalah Admin --}}
                        @if (Auth::user()->role === 'admin') 
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.employees.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    Kelola Karyawan
                                </a>
                            </div>
                        @endif

                    @else
                        <div class="space-y-3">
                            @foreach ($employees as $employee)
                                {{-- Link ke halaman transaksi (perlu route baru nanti) --}}
                                {{-- Contoh: route('pos.transaction', ['store' => $storeId, 'employee' => $employee->id_employee]) --}}
                                <a href="{{ route('pos.transaction', ['store' => $storeId, 'employee' => $employee->id_employee]) }}"
                                   class="block w-full px-4 py-3 text-center border border-gray-300 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ $employee->employee_name }} 
                                    <span class="text-sm text-gray-500">({{ $employee->position }})</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>