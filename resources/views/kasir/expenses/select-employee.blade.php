<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Pengeluaran - Pilih Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Tampilkan Pesan Sukses Jika Ada --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-medium text-center mb-4">Pilih Karyawan yang Melakukan Pengeluaran:</h3>

                    @if ($employees->isEmpty())
                        <p class="text-center text-gray-500">Tidak ada karyawan aktif yang terdaftar untuk toko ini.</p>
                    @else
                        <div class="space-y-3">
                            @foreach ($employees as $employee)
                                {{-- Link ke halaman form input pengeluaran --}}
                                <a href="{{ route('kasir.expenses.create', ['employee' => $employee->id_employee]) }}"
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