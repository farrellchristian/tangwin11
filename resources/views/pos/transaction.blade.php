<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mr-4">
                {{ __('Transaksi Kasir') }} - {{ $store->store_name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="posTransactionData({{ Js::from($availableServices) }}, {{ Js::from($availableProducts) }}, {{ Js::from($availableFoods) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 gap-6">

            <div class="space-y-6">

                {{-- INFORMASI CAPSTER UTAMA --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-2">Informasi Capster Utama</h3>
                        <p>Capster yang melayani: <span class="font-medium">{{ $primaryEmployee->employee_name }}</span></p>
                        <input type="hidden" x-model="primaryEmployeeId">
                        <input type="hidden" x-model="storeId">
                    </div>
                </div>

                {{-- PILIH LAYANAN --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-3">Layanan Capster Utama</h3>
                        <div class="space-y-3">
                            <template x-for="(serviceItem, index) in cart.filter(item => item.type === 'service')" :key="serviceItem.uniqueId">
                                <div class="flex items-center justify-between p-4 border rounded-md">
                                    <div class="flex-1">
                                        <p class="font-medium" x-text="serviceItem.name"></p>
                                        <p class="text-sm text-gray-600">Rp <span x-text="formatCurrency(serviceItem.price)"></span></p>
                                        <select x-model="serviceItem.employeeId" class="mt-1 text-xs border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option :value="primaryEmployeeId" x-text="'Capster: {{ $primaryEmployee->employee_name }}'"></option>
                                            @foreach ($availableEmployees as $emp)
                                            <option value="{{ $emp->id_employee }}">Capster: {{ $emp->employee_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button @click="removeItem(cart.findIndex(item => item.uniqueId === serviceItem.uniqueId))" class="ml-4 text-red-500 hover:text-red-700 p-1 border border-red-300 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button @click="openItemModal('service')" class="w-full px-4 py-3 text-center text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-md flex items-center justify-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span>Tambah Layanan</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- GABUNGKAN PRODUK & MINUMAN --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-3">Produk & Minuman Lainnya (Opsional)</h3>
                        <div class="space-y-3 mb-4">
                            {{-- Daftar Produk yang sudah dipilih --}}
                            <template x-for="(productItem, index) in cart.filter(item => item.type === 'product')" :key="productItem.uniqueId">
                                <div class="flex items-center justify-between p-4 border rounded-md">
                                    <div class="flex-1">
                                        <p class="font-medium" x-text="productItem.name"></p>
                                        <p class="text-sm text-gray-600">Rp <span x-text="formatCurrency(productItem.price)"></span></p>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-4">
                                        <button @click="decreaseQuantity(cart.findIndex(item => item.uniqueId === productItem.uniqueId))" class="text-gray-500 hover:text-gray-700 p-1 border rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                            </svg>
                                        </button>
                                        <span x-text="productItem.quantity" class="text-sm"></span>
                                        <button @click="increaseQuantity(cart.findIndex(item => item.uniqueId === productItem.uniqueId))" class="text-gray-500 hover:text-gray-700 p-1 border rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>
                                        <button @click="removeItem(cart.findIndex(item => item.uniqueId === productItem.uniqueId))" class="text-red-500 hover:text-red-700 p-1 border border-red-300 rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            {{-- Daftar Minuman yang sudah dipilih --}}
                            <template x-for="(foodItem, index) in cart.filter(item => item.type === 'food')" :key="foodItem.uniqueId">
                                <div class="flex items-center justify-between p-4 border rounded-md">
                                    <div class="flex-1">
                                        <p class="font-medium" x-text="foodItem.name"></p>
                                        <p class="text-sm text-gray-600">Rp <span x-text="formatCurrency(foodItem.price)"></span></p>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-4">
                                        <button @click="decreaseQuantity(cart.findIndex(item => item.uniqueId === foodItem.uniqueId))" class="text-gray-500 hover:text-gray-700 p-1 border rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                            </svg>
                                        </button>
                                        <span x-text="foodItem.quantity" class="text-sm"></span>
                                        <button @click="increaseQuantity(cart.findIndex(item => item.uniqueId === foodItem.uniqueId))" class="text-gray-500 hover:text-gray-700 p-1 border rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>
                                        <button @click="removeItem(cart.findIndex(item => item.uniqueId === foodItem.uniqueId))" class="text-red-500 hover:text-red-700 p-1 border border-red-300 rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- TOMBOL PILIH PRODUK & MINUMAN (dengan ikon dan warna) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <button @click="openItemModal('product')" class="w-full px-4 py-3 text-center text-white bg-green-600 hover:bg-green-700 rounded-md shadow-md flex items-center justify-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 lucide-archive">
                                    <rect width="20" height="5" x="2" y="3" rx="1"></rect>
                                    <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"></path>
                                    <path d="M10 12h4"></path>
                                </svg>
                                <span>Pilih Produk</span>
                            </button>
                            <button @click="openItemModal('food')" class="w-full px-4 py-3 text-center text-white bg-yellow-600 hover:bg-yellow-700 rounded-md shadow-md flex items-center justify-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 lucide-cup-soda">
                                    <path d="m6 8 1.75 12.28a2 2 0 0 0 2 1.72h4.54a2 2 0 0 0 2-1.72L18 8"></path>
                                    <path d="M5 8h14"></path>
                                    <path d="M7 15a6.47 6.47 0 0 1 5 0 6.47 6.47 0 0 0 5 0"></path>
                                    <path d="m12 8 1-6h2"></path>
                                </svg>
                                <span>Pilih Minuman</span>
                            </button>
                        </div>
                    </div>
                </div>


            </div>{{-- RINGKASAN PEMBAYARAN --}}
            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Ringkasan Pembayaran</h3>

                        {{-- Total Harga --}}
                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between font-semibold">
                                <span>Subtotal</span>
                                <span x-text="'Rp ' + formatCurrency(calculateSubtotal())"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <label for="tips" class="text-sm text-gray-600">Tips (Opsional)</label>
                                <div class="relative rounded-md shadow-sm w-1/2">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><span class="text-gray-500 sm:text-sm">Rp</span></div>
                                    <input type="number" name="tips" id="tips" x-model.number="tips" @input="calculateTotal()" class="block w-full rounded-md border-gray-300 pl-10 pr-1 text-right focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0" min="0">
                                </div>
                            </div>
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span x-text="'Rp ' + formatCurrency(calculateTotal())"></span>
                            </div>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="mt-6 border-t pt-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                            <select id="payment_method" name="payment_method" x-model="paymentMethodId" @change="calculateChange()" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach ($paymentMethods as $method)
                                <option value="{{ $method->id_payment_method }}">{{ $method->method_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Form Pembayaran (Muncul jika Cash) --}}
                        <div x-show="paymentMethodId == {{ $paymentMethods->firstWhere('method_name', 'Cash')?->id_payment_method ?? 'null' }}" class="mt-4 border-t pt-4 space-y-2">
                            <div>
                                <label for="amount_paid" class="block text-sm font-medium text-gray-700">Jumlah Uang</label>
                                <div class="relative mt-1 rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><span class="text-gray-500 sm:text-sm">Rp</span></div>
                                    <input type="number" name="amount_paid" id="amount_paid" x-model.number="amountPaid" @input="calculateChange()" class="block w-full rounded-md border-gray-300 pl-10 pr-1 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0" min="0">
                                </div>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Kembalian:</span>
                                <span class="font-medium" x-text="'Rp ' + formatCurrency(changeAmount)"></span>
                            </div>
                            <div class="flex space-x-2 pt-2">
                                <button type="button" @click="setAmountPaid(totalAmount)" class="flex-1 text-xs py-1 px-2 border rounded hover:bg-gray-100">Uang Pas</button>
                                <button type="button" @click="setAmountPaid(50000)" class="flex-1 text-xs py-1 px-2 border rounded hover:bg-gray-100">50.000</button>
                                <button type="button" @click="setAmountPaid(100000)" class="flex-1 text-xs py-1 px-2 border rounded hover:bg-gray-100">100.000</button>
                            </div>
                        </div>


                        {{-- Tombol Proses Pembayaran --}}
                        <div class="mt-6 border-t pt-4">
                            <button @click="submitTransaction"
                                :disabled="!canSubmit()"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-50 transition ease-in-out duration-150">
                                Proses Pembayaran
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            {{-- MODAL PEMILIHAN ITEM --}}
            <div x-show="showItemModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto"
                aria-labelledby="modal-title"
                role="dialog"
                aria-modal="true"
                style="display: none;">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showItemModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                        aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div x-show="showItemModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                        <div class="flex justify-between items-center pb-3 border-b">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" x-text="modalTitle"></h3>
                            <button @click="closeItemModal()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-4 max-h-96 overflow-y-auto space-y-2">
                            <template x-for="item in filteredItems" :key="item.id_item">
                                <div class="flex items-center justify-between p-3 border rounded-md hover:bg-gray-50 cursor-pointer"
                                    @click="addItem(currentItemType, item.id_item, item.name, item.price); closeItemModal()"> {{-- Tutup modal setelah item dipilih --}}
                                    <div>
                                        <p class="font-medium" x-text="item.name"></p>
                                        <p class="text-sm text-gray-600">Rp <span x-text="formatCurrency(item.price)"></span></p>
                                        <p x-show="item.duration" class="text-xs text-gray-500">Durasi: <span x-text="item.duration"></span> menit</p>
                                        <p x-show="item.stock_available !== undefined" class="text-xs text-gray-500">Stok: <span x-text="item.stock_available"></span></p>
                                    </div>
                                    <div>
                                        <button class="text-green-600 hover:text-green-800 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <template x-if="filteredItems.length === 0">
                                <p class="text-center text-gray-500 py-4">Tidak ada item tersedia.</p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL SUKSES TRANSAKSI --}}
            <div x-show="showSuccessModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto"
                aria-labelledby="modal-title"
                role="dialog"
                aria-modal="true"
                style="display: none;">

                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showSuccessModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                        aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showSuccessModal"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block w-full max-w-sm p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">

                        <div class="mt-4 text-center">
                            {{-- CSS Animasi Checkmark --}}
                            <style>
                                .checkmark__circle {
                                    stroke-dasharray: 166;
                                    stroke-dashoffset: 166;
                                    stroke-width: 2;
                                    stroke-miterlimit: 10;
                                    stroke: #7ac142;
                                    fill: none;
                                    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards
                                }
                                .checkmark {
                                    width: 100px;
                                    height: 100px;
                                    border-radius: 50%;
                                    display: block;
                                    stroke-width: 2;
                                    stroke: #fff;
                                    stroke-miterlimit: 10;
                                    margin: 0 auto;
                                    box-shadow: inset 0px 0px 0px #7ac142;
                                    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both
                                }
                                .checkmark__check {
                                    transform-origin: 50% 50%;
                                    stroke-dasharray: 48;
                                    stroke-dashoffset: 48;
                                    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards
                                }
                                @keyframes stroke { 100% { stroke-dashoffset: 0 } }
                                @keyframes scale { 0%, 100% { transform: none } 50% { transform: scale3d(1.1, 1.1, 1) } }
                                @keyframes fill { 100% { box-shadow: inset 0px 0px 0px 60px #7ac142 } }
                            </style>

                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                            </svg>

                            <p class="font-bold text-2xl text-gray-800 mt-4">Transaksi Berhasil!</p>
                            <p class="text-sm text-gray-600 mt-1 mb-6">Apakah Anda ingin mencetak struk?</p>

                            <div class="flex flex-col space-y-3">
                                <button @click="printReceipt()" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0v3.396c0 .65.31 1.25.82 1.637l1.096.83a2.25 2.25 0 0 1 .894 1.802v.704h5.364v-.704a2.25 2.25 0 0 1 .894-1.802l1.096-.83a2.25 2.25 0 0 0 .82-1.637V7.037Z" />
                                    </svg>
                                    Ya, Cetak Struk
                                </button>
                                <button @click="backToIndex()" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Tidak, Selesai
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Script Alpine.js --}}
    <script>
        function posTransactionData(initialServices, initialProducts, initialFoods) {
            return {
                storeId: {{ $store->id_store }},
                primaryEmployeeId: {{ $primaryEmployee->id_employee }},
                cart: [],
                tips: 0,
                paymentMethodId: '',
                amountPaid: 0,
                changeAmount: 0,
                totalAmount: 0,

                isProcessing: false,
                
                showSuccessModal: false,
                successTransactionId: null,

                showItemModal: false,
                currentItemType: '',
                modalTitle: '',
                allServices: initialServices.map(s => ({
                    id_item: s.id_service,
                    name: s.service_name,
                    price: parseFloat(s.price),
                    duration: s.duration,
                    type: 'service'
                })),
                allProducts: initialProducts.map(p => ({
                    id_item: p.id_product,
                    name: p.product_name,
                    price: parseFloat(p.price),
                    stock_available: p.stock_available,
                    type: 'product'
                })),
                allFoods: initialFoods.map(f => ({
                    id_item: f.id_food,
                    name: f.food_name,
                    price: parseFloat(f.price),
                    stock_available: f.stock_available,
                    type: 'food'
                })),

                get filteredItems() {
                    if (this.currentItemType === 'service') return this.allServices;
                    if (this.currentItemType === 'product') return this.allProducts;
                    if (this.currentItemType === 'food') return this.allFoods;
                    return [];
                },

                openItemModal(type) {
                    this.currentItemType = type;
                    if (type === 'service') this.modalTitle = 'Pilih Layanan';
                    if (type === 'product') this.modalTitle = 'Pilih Produk';
                    if (type === 'food') this.modalTitle = 'Pilih Minuman';
                    this.showItemModal = true;
                },

                closeItemModal() {
                    this.showItemModal = false;
                    this.currentItemType = '';
                    this.modalTitle = '';
                },

                addItem(type, id, name, price) {
                    const uniqueId = `${type}-${id}-${Date.now()}`;

                    if (type === 'service') {
                        this.cart.push({
                            uniqueId: uniqueId,
                            id: id,
                            type: type,
                            name: name,
                            price: parseFloat(price),
                            quantity: 1,
                            employeeId: this.primaryEmployeeId
                        });
                    } else {
                        const existingItemIndex = this.cart.findIndex(item => item.id === id && item.type === type);
                        let currentStock = 0;
                        if (type === 'product') {
                            currentStock = this.allProducts.find(p => p.id_item === id)?.stock_available ?? 0;
                        } else if (type === 'food') {
                            currentStock = this.allFoods.find(f => f.id_item === id)?.stock_available ?? 0;
                        }

                        if (existingItemIndex > -1) {
                            if (this.cart[existingItemIndex].quantity < currentStock) {
                                this.cart[existingItemIndex].quantity++;
                            } else {
                                alert(`Stok ${name} tidak mencukupi.`);
                                return;
                            }
                        } else {
                            if (currentStock > 0) {
                                this.cart.push({
                                    uniqueId: uniqueId,
                                    id: id,
                                    type: type,
                                    name: name,
                                    price: parseFloat(price),
                                    quantity: 1,
                                    employeeId: null
                                });
                            } else {
                                alert(`Stok ${name} habis.`);
                                return;
                            }
                        }
                    }
                    this.calculateTotal();
                },

                increaseQuantity(index) {
                    const item = this.cart[index];
                    let currentStock = 0;
                    if (item.type === 'product') {
                        currentStock = this.allProducts.find(p => p.id_item === item.id)?.stock_available ?? 0;
                    } else if (item.type === 'food') {
                        currentStock = this.allFoods.find(f => f.id_item === item.id)?.stock_available ?? 0;
                    }

                    if (item.quantity < currentStock) {
                        item.quantity++;
                        this.calculateTotal();
                    } else {
                        alert(`Stok ${item.name} tidak mencukupi.`);
                    }
                },

                decreaseQuantity(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                        this.calculateTotal();
                    } else {
                        this.removeItem(index);
                    }
                },

                removeItem(index) {
                    this.cart.splice(index, 1);
                    this.calculateTotal();
                },

                calculateSubtotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },

                calculateTotal() {
                    this.totalAmount = this.calculateSubtotal() + parseFloat(this.tips || 0);
                    this.calculateChange();
                    return this.totalAmount;
                },

                calculateChange() {
                    const cashMethodId = {{ $paymentMethods->firstWhere('method_name', 'Cash')?->id_payment_method ?? 'null' }};
                    if (this.paymentMethodId && this.paymentMethodId == cashMethodId) {
                        this.changeAmount = Math.max(0, parseFloat(this.amountPaid || 0) - this.totalAmount);
                    } else {
                        this.changeAmount = 0;
                    }
                    return this.changeAmount;
                },

                setAmountPaid(amount) {
                    this.amountPaid = amount;
                    this.calculateChange();
                },

                formatCurrency(value) {
                    const numberValue = Number(value);
                    if (isNaN(numberValue)) {
                        return '0';
                    }
                    return new Intl.NumberFormat('id-ID').format(numberValue);
                },

                canSubmit() {
                    if (this.isProcessing) return false; // Nonaktifkan jika sedang memproses
                    if (this.cart.length === 0) return false;
                    if (!this.paymentMethodId) return false;

                    const cashMethodId = {{ $paymentMethods->firstWhere('method_name', 'Cash')?->id_payment_method ?? 'null' }};
                    if (this.paymentMethodId == cashMethodId) {
                        if (parseFloat(this.amountPaid || 0) < this.totalAmount) return false;
                    }

                    if (this.cart.filter(item => item.type === 'service').some(service => !service.employeeId)) {
                        return false;
                    }

                    return true;
                },

                submitTransaction() {
                    if (!this.canSubmit()) {
                        alert('Periksa kembali pesanan atau tunggu proses selesai.');
                        return;
                    }

                    this.isProcessing = true; // Mulai proses

                    // Ambil ID untuk Cash dan Qris dari data PHP
                    const cashMethodId = {{ $paymentMethods->firstWhere('method_name', 'Cash')?->id_payment_method ?? 'null' }};
                    const qrisMethodId = {{ $paymentMethods->firstWhere('method_name', 'Qris')?->id_payment_method ?? 'null' }};

                    // Siapkan data transaksi
                    const transactionData = {
                        _token: '{{ csrf_token() }}',
                        id_store: this.storeId,
                        id_employee_primary: this.primaryEmployeeId,
                        id_payment_method: this.paymentMethodId,
                        total_amount: this.totalAmount, // Ini adalah Subtotal + Tips
                        amount_paid: this.amountPaid,
                        change_amount: this.changeAmount,
                        tips: this.tips,
                        cart: this.cart.map(item => ({
                            id_item: item.id,
                            item_type: item.type,
                            id_employee: item.employeeId,
                            quantity: item.quantity,
                            price_at_sale: item.price,
                            name: item.name // Kirim nama untuk item_details
                        }))
                    };

                    // **LOGIKA PERCABANGAN PEMBAYARAN**
                    if (this.paymentMethodId == cashMethodId) {
                        // --- 1. LOGIKA UNTUK CASH ---
                        transactionData.status = 'paid';
                        transactionData.order_id = null;
                        this.executeSaveTransaction(transactionData);

                    } else {
                        // --- 2. LOGIKA UNTUK METODE LAIN (QRIS, Transfer) ---
                        // Qris tidak lagi menggunakan Midtrans, sehingga langsung disave sebagai lunas (Manual)
                        transactionData.status = 'paid';
                        transactionData.order_id = null;
                        this.executeSaveTransaction(transactionData);
                    }
                },

                // Fungsi ini HANYA untuk menyimpan data ke DB (logika lama Anda)
                executeSaveTransaction(transactionData) {
                    // Sekarang kita tambahkan 'status' dan 'order_id'
                    const dataToSave = {
                        ...transactionData,
                        status: transactionData.status || 'paid',
                        order_id: transactionData.order_id || null
                    };

                    fetch("{{ route('pos.store-transaction') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(dataToSave)
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw err;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                this.successTransactionId = data.transaction_id;
                                this.showSuccessModal = true;
                            } else {
                                alert('Error: ' + (data.message || 'Gagal menyimpan transaksi.'));
                            }
                        })
                        .catch(error => {
                            console.error('Error executeSaveTransaction:', error);
                            let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                            if (error && error.errors) {
                                errorMsg = "Error Validasi:\n";
                                for (const field in error.errors) {
                                    errorMsg += `- ${error.errors[field].join(', ')}\n`;
                                }
                            } else if (error && error.message) {
                                errorMsg = `Error: ${error.message}`;
                            }
                            alert(errorMsg);
                        })
                        .finally(() => {
                            this.isProcessing = false;
                        });
                },

                printReceipt() {
                    if (this.successTransactionId) {
                        window.open(`/pos/struk/${this.successTransactionId}`, '_blank');
                    }
                    this.backToIndex();
                },

                backToIndex() {
                    window.location.href = "{{ route('pos.index') }}";
                }

            }
        }
    </script>
</x-app-layout>