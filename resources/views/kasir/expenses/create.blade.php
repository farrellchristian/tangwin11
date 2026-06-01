<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
            {{ __('Input Pengeluaran') }} <span class="hidden sm:inline">untuk <span class="text-indigo-600 font-extrabold">{{ $employee->employee_name }}</span></span>
        </h2>
    </x-slot>

    <div class="h-full flex flex-col justify-center items-center p-4 overflow-hidden bg-gray-50">
        <div class="w-full max-w-2xl flex flex-col">
            
            {{-- Tombol Kembali --}}
            <div class="mb-3 shrink-0">
                <a href="{{ route('kasir.expenses.select-employee') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 mr-1.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    KEMBALI
                </a>
            </div>

            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 flex flex-col overflow-hidden shrink-0">
                <div class="p-5 sm:p-7 text-gray-900">

                    {{-- Nama Karyawan untuk Mobile --}}
                    <div class="sm:hidden mb-4 pb-3 border-b border-gray-100 flex items-center justify-between">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Karyawan</p>
                        <h3 class="text-lg font-extrabold text-indigo-700">{{ $employee->employee_name }}</h3>
                    </div>

                    {{-- Informasi Limit Karyawan --}}
                    @if ($employee->daily_expense_limit !== null)
                        <div class="mb-5 p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                            <div>
                                <p class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider mb-1">Limit Harian</p>
                                <p class="text-sm font-bold text-gray-800">Rp {{ number_format($employee->daily_expense_limit, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-px h-10 bg-indigo-200 hidden sm:block"></div>
                            <div>
                                <p class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider mb-1">Terpakai</p>
                                <p class="text-sm font-bold text-gray-800">Rp {{ number_format($todayExpenses, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-px h-10 bg-indigo-200 hidden sm:block"></div>
                            <div class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg">
                                <p class="text-[10px] font-semibold uppercase tracking-wider opacity-80">Sisa Limit</p>
                                <p class="text-sm font-black">Rp {{ number_format(max(0, $employee->daily_expense_limit - $todayExpenses), 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kasir.expenses.store') }}" class="flex flex-col gap-4">
                        @csrf
                        <input type="hidden" name="id_employee" value="{{ $employee->id_employee }}">

                        <div>
                            <label for="description" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Keterangan Pengeluaran</label>
                            <textarea name="description" id="description" rows="2" class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-medium transition-colors" placeholder="Tuliskan untuk apa pengeluaran ini..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{
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
                            <label for="amount_display" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Jumlah Pengeluaran</label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                  <span class="text-gray-500 font-bold sm:text-sm">Rp</span>
                                </div>
                                <input type="text"
                                       id="amount_display"
                                       x-model="formattedAmount"
                                       @input="formatNumber"
                                       class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white pl-12 pr-4 py-3 focus:border-indigo-500 focus:ring-indigo-500 font-extrabold text-gray-900 transition-colors"
                                       placeholder="0"
                                       required
                                       inputmode="numeric">
                                <input type="hidden" name="amount" id="amount" x-model="rawAmount">
                            </div>
                            @error('amount')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                            @error('id_employee')
                                 <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('kasir.expenses.select-employee') }}" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-800 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-all shadow-md shadow-indigo-200 hover:-translate-y-0.5 active:translate-y-0">
                                Simpan Pengeluaran
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>