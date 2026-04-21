<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Transaksi #') }}{{ $transaction->id_transaction }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Informasi Transaksi') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Perbarui data transaksi seperti Karyawan penanggung jawab, metode pembayaran, tips, dan waktu transaksi.') }}
                    </p>
                </header>

                <form method="POST" action="{{ route('admin.transactions.update', $transaction->id_transaction) }}" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Pilih Toko (Disabled - Info Only) --}}
                        <div>
                            <x-input-label value="Toko (Read Only)" />
                            <div class="mt-1 w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm text-gray-500 sm:text-sm">
                                {{ $transaction->store->store_name }}
                            </div>
                        </div>

                        {{-- Tanggal Transaksi --}}
                        <div>
                            <x-input-label for="transaction_date" value="Waktu Transaksi" />
                            <x-text-input id="transaction_date" name="transaction_date" type="datetime-local" class="mt-1 block w-full" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d\TH:i')) }}" required />
                            <x-input-error :messages="$errors->get('transaction_date')" class="mt-2" />
                        </div>

                        {{-- Karyawan Utama --}}
                        <div>
                            <x-input-label for="id_employee_primary" value="Karyawan (PIC Utama)" />
                            <select id="id_employee_primary" name="id_employee_primary" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id_employee }}" {{ old('id_employee_primary', $transaction->id_employee_primary) == $employee->id_employee ? 'selected' : '' }}>
                                        {{ $employee->employee_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_employee_primary')" class="mt-2" />
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div>
                            <x-input-label for="id_payment_method" value="Metode Pembayaran" />
                            <select id="id_payment_method" name="id_payment_method" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id_payment_method }}" {{ old('id_payment_method', $transaction->id_payment_method) == $method->id_payment_method ? 'selected' : '' }}>
                                        {{ $method->method_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('id_payment_method')" class="mt-2" />
                        </div>

                        {{-- Status Transaksi --}}
                        <div>
                            <x-input-label for="status" value="Status Pembayaran" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="Paid" {{ old('status', $transaction->status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="Unpaid" {{ old('status', $transaction->status) == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="Pending" {{ old('status', $transaction->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Cancelled" {{ old('status', $transaction->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        {{-- Tips --}}
                        <div>
                            <x-input-label for="tips" value="Tips Karyawan (Rp)" />
                            <x-text-input id="tips" name="tips" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('tips', $transaction->tips) }}" required />
                            <x-input-error :messages="$errors->get('tips')" class="mt-2" />
                        </div>

                    </div>

                    {{-- Catatan --}}
                    <div>
                        <x-input-label for="notes" value="Catatan Tambahan" />
                        <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $transaction->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="mt-2 text-[11px] text-gray-500 italic">
                        * Catatan: Mengubah jumlah tips akan otomatis menghitung ulang Total Transaksi.
                    </div>

                    <div class="flex items-center gap-4 mt-4">
                        <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                        <a href="{{ route('admin.reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            {{ __('Batal') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
