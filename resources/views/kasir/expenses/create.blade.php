<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
            {{ __('Input Pengeluaran') }} <span class="hidden sm:inline">untuk {{ $employee->employee_name }}</span>
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Tombol Kembali --}}
            <div class="mb-4">
                <a href="{{ route('kasir.expenses.select-employee') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 shadow-sm rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-1.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-4 sm:p-6 text-gray-900">

                    {{-- Nama Karyawan untuk Mobile --}}
                    <div class="sm:hidden mb-5 pb-3 border-b border-gray-100">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Karyawan</p>
                        <h3 class="text-xl font-extrabold text-indigo-700">{{ $employee->employee_name }}</h3>
                    </div>

                    {{-- Informasi Limit Karyawan --}}
                    @if ($employee->daily_expense_limit !== null)
                        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                            <p class="font-semibold">Informasi Limit Pengeluaran Harian:</p>
                            <p>Limit: Rp {{ number_format($employee->daily_expense_limit, 0, ',', '.') }}</p>
                            <p>Total Pengeluaran Hari Ini: Rp {{ number_format($todayExpenses, 0, ',', '.') }}</p>
                            <p>Sisa Limit Hari Ini: Rp {{ number_format(max(0, $employee->daily_expense_limit - $todayExpenses), 0, ',', '.') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kasir.expenses.store') }}">
                        @csrf
                        {{-- Kirim ID Karyawan tersembunyi --}}
                        <input type="hidden" name="id_employee" value="{{ $employee->id_employee }}">

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Keterangan Pengeluaran</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4"
                             x-data="{
                                 formattedAmount: '{{ old('amount') ? number_format(old('amount'), 0, ',', '.') : '' }}',
                                 rawAmount: '{{ old('amount', '') }}',
                                 formatNumber() {
                                     let raw = this.formattedAmount.replace(/[^0-9]/g, '');
                                     this.rawAmount = raw;
                                     this.formattedAmount = new Intl.NumberFormat('id-ID').format(raw) || '';
                                 }
                             }"
                             x-init="formatNumber()"
                        >
                            <label for="amount_display" class="block text-sm font-medium text-gray-700">Jumlah Pengeluaran</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                  <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="text"
                                       id="amount_display"
                                       x-model="formattedAmount"
                                       @input="formatNumber"
                                       class="block w-full rounded-md border-gray-300 pl-10 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="0"
                                       required
                                       inputmode="numeric">
                                <input type="hidden" name="amount" id="amount" x-model="rawAmount">
                            </div>
                             {{-- Tampilkan error amount (termasuk error limit) --}}
                            @error('amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            {{-- Tampilkan error id_employee jika ada --}}
                            @error('id_employee')
                                 <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('kasir.expenses.select-employee') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Pengeluaran
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>