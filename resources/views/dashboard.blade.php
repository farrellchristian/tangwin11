<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{-- Cek apakah role user yang login adalah 'admin' --}}
                @if (Auth::user()->role == 'admin')
                    <p>Halo, {{ Auth::user()->name }}. Anda login sebagai **ADMIN**.</p>
                    <p>Toko Anda: {{ Auth::user()->store->store_name }}</p>

                @else
                    <p>Halo, {{ Auth::user()->name }}. Anda login sebagai KASIR.</p>
                    <p>Anda bertugas di toko: {{ Auth::user()->store->store_name }}</p>
                @endif
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
