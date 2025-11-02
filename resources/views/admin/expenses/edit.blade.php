<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mr-4">
                {{ __('Edit Data Pengeluaran') }} #{{ $expense->id_expense }}
            </h2>

            <a href="{{ route('admin.expenses.index', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mt-2 sm:mt-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Riwayat
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('admin.expenses.update', $expense->id_expense) }}">
                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <label for="id_store" class="block text-sm font-medium text-gray-700">Toko</label>
                            <select id="id_store" name="id_store" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-100" disabled> {{-- Dibuat disabled, toko seharusnya tidak diubah --}}
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id_store }}" {{ old('id_store', $expense->id_store) == $store->id_store ? 'selected' : '' }}>
                                        {{ $store->store_name }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Kirim ID toko tersembunyi karena select disabled --}}
                             <input type="hidden" name="id_store" value="{{ $expense->id_store }}">
                            @error('id_store') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-4">
                            <label for="id_employee" class="block text-sm font-medium text-gray-700">Karyawan</label>
                            <select id="id_employee" name="id_employee" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Pilih Karyawan</option>
                                {{-- Hanya tampilkan karyawan dari toko expense ini --}}
                                @foreach ($employees->where('id_store', $expense->id_store) as $employee)
                                    <option value="{{ $employee->id_employee }}" {{ old('id_employee', $expense->id_employee) == $employee->id_employee ? 'selected' : '' }}>
                                        {{ $employee->employee_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_employee') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-4">
                            <label for="expense_date" class="block text-sm font-medium text-gray-700">Tanggal Pengeluaran</label>
                            {{-- Gunakan datetime-local untuk input tanggal & waktu --}}
                            <input type="datetime-local" name="expense_date" id="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            @error('expense_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                         <div class="mt-4">
                            <label for="id_user" class="block text-sm font-medium text-gray-700">Diinput Oleh</label>
                            <select id="id_user" name="id_user" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Pilih User</option>
                                {{-- Tampilkan user admin atau kasir dari toko terkait --}}
                                @foreach ($users->filter(fn($u) => $u->role === 'admin' || $u->id_store === $expense->id_store) as $user)
                                    <option value="{{ $user->id }}" {{ old('id_user', $expense->id_user) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->role }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_user') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>


                        <div class="mt-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Keterangan Pengeluaran</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('description', $expense->description) }}</textarea>
                            @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-4"
                             x-data="{
                                 formattedAmount: '{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : number_format($expense->amount, 0, ',', '.') }}',
                                 rawAmount: '{{ old('amount', $expense->amount) }}',
                                 formatNumber() { /* ... fungsi format ... */
                                     let raw = this.formattedAmount.replace(/[^0-9]/g, '');
                                     this.rawAmount = raw;
                                     this.formattedAmount = new Intl.NumberFormat('id-ID').format(raw) || '';
                                 }
                             }"
                             x-init="formatNumber()">
                            <label for="amount_display" class="block text-sm font-medium text-gray-700">Jumlah Pengeluaran</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><span class="text-gray-500 sm:text-sm">Rp</span></div>
                                <input type="text" id="amount_display" x-model="formattedAmount" @input="formatNumber" class="block w-full rounded-md border-gray-300 pl-10 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0" required inputmode="numeric">
                                <input type="hidden" name="amount" id="amount" x-model="rawAmount">
                            </div>
                            @error('amount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.expenses.index', request()->query()) }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Perbarui Pengeluaran
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>