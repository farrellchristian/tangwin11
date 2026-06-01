<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-base font-semibold text-gray-900 tracking-tight">Tampilan Web Reservasi</h2>
            <p class="text-sm text-gray-400 mt-0.5">Atur apa yang tampil di halaman booking pelanggan.</p>
        </div>
    </x-slot>

    <style>
        /* ── CRITICAL: Hide x-cloak elements before Alpine.js initializes ── */
        [x-cloak] { display: none !important; }

        /* ── Toggle Switch ─────────────────────── */
        .ts-wrap { position: relative; display: inline-block; width: 36px; height: 20px; flex-shrink: 0; }
        .ts-input { position: absolute; opacity: 0; width: 0; height: 0; }
        .ts-track {
            position: absolute; inset: 0;
            display: block;
            background: #E5E7EB;
            border-radius: 999px;
            cursor: pointer;
            transition: background .18s ease;
        }
        .ts-input:checked ~ .ts-track { background: #111827; }
        .ts-track::after {
            content: '';
            position: absolute;
            top: 3px; left: 3px;
            width: 14px; height: 14px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,.18);
            transition: transform .18s ease;
        }
        .ts-input:checked ~ .ts-track::after { transform: translateX(16px); }

        /* ── Row ──────────────────────────────── */
        .vis-row { transition: opacity .15s ease; }
        .vis-row.off { opacity: .38; }

        /* ── Tabs ─────────────────────────────── */
        .tab-trigger {
            position: relative; padding: .625rem 1rem;
            font-size: .8125rem; font-weight: 500; color: #6B7280;
            border-bottom: 2px solid transparent;
            cursor: pointer; transition: color .15s ease, border-color .15s ease;
            white-space: nowrap;
        }
        .tab-trigger:hover { color: #374151; }
        .tab-trigger.active { color: #111827; border-color: #111827; }

        .tab-panel { display: none; }
        .tab-panel.active { display: block; animation: panelIn .14s ease; }
        @keyframes panelIn {
            from { opacity: 0; transform: translateY(5px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Section header in list ───────────── */
        .group-label {
            padding: .375rem 1.25rem;
            font-size: .6875rem; font-weight: 600;
            letter-spacing: .06em; text-transform: uppercase;
            color: #9CA3AF; background: #FAFAFA;
            border-bottom: 1px solid #F3F4F6;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <div class="py-6" x-data="cropperManager()">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ── Stat Cards ─────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                @php
                    $stats = [
                        ['label' => 'Cabang',      'on' => $stores->where('show_on_reservation',1)->count(),        'total' => $stores->count()],
                        ['label' => 'Layanan',      'on' => $services->where('show_on_reservation',1)->count(),       'total' => $services->count()],
                        ['label' => 'Stylist',      'on' => $employees->where('show_on_reservation',1)->count(),      'total' => $employees->count()],
                    ];
                @endphp
                @foreach($stats as $s)
                    <div class="bg-white border border-gray-200 rounded-xl px-4 py-3">
                        <p class="text-xs text-gray-400 mb-1">{{ $s['label'] }}</p>
                        <p class="text-2xl font-semibold text-gray-900 leading-none">{{ $s['on'] }}<span class="text-sm font-normal text-gray-400">/{{ $s['total'] }}</span></p>
                        <p class="text-xs text-gray-400 mt-1">ditampilkan</p>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('admin.website-setting.update') }}" method="POST" id="vis-form" enctype="multipart/form-data">
                @csrf

                <div class="relative bg-white border border-gray-200 rounded-xl overflow-hidden">

                    {{-- ── Tab Bar ─────────────────────────── --}}
                    <div class="flex overflow-x-auto border-b border-gray-100 px-2" role="tablist">
                        <button type="button" class="tab-trigger active" onclick="switchTab('stores', this)" role="tab">
                            Cabang
                            <span class="ml-1.5 text-xs text-gray-400 font-normal">{{ $stores->count() }}</span>
                        </button>
                        <button type="button" class="tab-trigger" onclick="switchTab('services', this)" role="tab">
                            Layanan
                            <span class="ml-1.5 text-xs text-gray-400 font-normal">{{ $services->count() }}</span>
                        </button>
                        <button type="button" class="tab-trigger" onclick="switchTab('employees', this)" role="tab">
                            Stylist
                            <span class="ml-1.5 text-xs text-gray-400 font-normal">{{ $employees->count() }}</span>
                        </button>
                    </div>

                    {{-- ── TAB: Cabang ─────────────────────── --}}
                    <div id="tab-stores" class="tab-panel active">
                        <div class="flex items-center justify-between px-5 py-2.5 border-b border-gray-50">
                            <p class="text-xs text-gray-400">Pilih cabang yang tersedia di halaman booking</p>
                            <button type="button" onclick="toggleAll('store')" class="text-xs font-medium text-gray-700 hover:text-gray-900 transition-colors">Pilih semua</button>
                        </div>
                        @forelse($stores as $store)
                            <label class="vis-row {{ !$store->show_on_reservation ? 'off' : '' }} flex items-center gap-4 px-5 py-3.5 border-b border-gray-50 last:border-0 hover:bg-gray-50/60 cursor-pointer transition-colors">
                                <div class="ts-wrap">
                                    <input type="checkbox" class="ts-input store-check" name="stores[]" value="{{ $store->id_store }}"
                                        @checked($store->show_on_reservation)
                                        onchange="this.closest('.vis-row').classList.toggle('off', !this.checked)">
                                    <div class="ts-track"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $store->store_name }}</p>
                                    @if($store->address)
                                        <p class="text-xs text-gray-400 truncate mt-0.5">{{ $store->address }}</p>
                                    @endif
                                </div>
                            </label>
                        @empty
                            <p class="px-5 py-6 text-sm text-gray-400 text-center">Belum ada cabang.</p>
                        @endforelse
                    </div>

                    {{-- ── TAB: Layanan ────────────────────── --}}
                    <div id="tab-services" class="tab-panel">
                        <div class="flex items-center justify-between px-5 py-2.5 border-b border-gray-50">
                            <p class="text-xs text-gray-400">Layanan yang bisa dipesan pelanggan</p>
                            <button type="button" onclick="toggleAll('services')" class="text-xs font-medium text-gray-700 hover:text-gray-900 transition-colors">Pilih semua</button>
                        </div>
                        @php $servicesByStore = $services->groupBy(fn($s) => $s->store->store_name ?? 'Tanpa Cabang'); @endphp
                        @foreach($servicesByStore as $storeName => $storeServices)
                            <p class="group-label">{{ $storeName }}</p>
                            @foreach($storeServices as $service)
                                <label class="vis-row {{ !$service->show_on_reservation ? 'off' : '' }} flex items-center gap-4 px-5 py-3.5 border-b border-gray-50 last:border-0 hover:bg-gray-50/60 cursor-pointer transition-colors">
                                    <div class="ts-wrap">
                                        <input type="checkbox" class="ts-input services-check" name="services[]" value="{{ $service->id_service }}"
                                            @checked($service->show_on_reservation)
                                            onchange="this.closest('.vis-row').classList.toggle('off', !this.checked)">
                                        <div class="ts-track"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $service->service_name }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                                    </div>
                                </label>
                            @endforeach
                        @endforeach
                        @if($services->isEmpty())
                            <p class="px-5 py-6 text-sm text-gray-400 text-center">Belum ada layanan.</p>
                        @endif
                    </div>

                    {{-- ── TAB: Stylist ────────────────────── --}}
                    <div id="tab-employees" class="tab-panel">
                        <div class="flex items-center justify-between px-5 py-2.5 border-b border-gray-50">
                            <p class="text-xs text-gray-400">Klik pada foto untuk mengganti profil kapster</p>
                            <button type="button" onclick="toggleAll('employees')" class="text-xs font-medium text-gray-700 hover:text-gray-900 transition-colors">Pilih semua</button>
                        </div>
                        @php $empByStore = $employees->groupBy(fn($e) => $e->store->store_name ?? 'Tanpa Cabang'); @endphp
                        @foreach($empByStore as $storeName => $storeEmployees)
                            <p class="group-label">{{ $storeName }}</p>
                            @foreach($storeEmployees as $employee)
                                <div class="vis-row {{ !$employee->show_on_reservation ? 'off' : '' }} flex items-center gap-4 px-5 py-3 border-b border-gray-50 last:border-0 hover:bg-gray-50/60 transition-colors">
                                    
                                    {{-- Toggle --}}
                                    <div class="ts-wrap">
                                        <input type="checkbox" id="emp-toggle-{{ $employee->id_employee }}" class="ts-input employees-check" name="employees[]" value="{{ $employee->id_employee }}"
                                            @checked($employee->show_on_reservation)
                                            onchange="this.closest('.vis-row').classList.toggle('off', !this.checked)">
                                        <label for="emp-toggle-{{ $employee->id_employee }}" class="ts-track"></label>
                                    </div>

                                    {{-- Photo Upload Section --}}
                                    <div class="relative group" id="emp-photo-{{ $employee->id_employee }}"
                                         x-data="{ 
                                            preview: '{{ $employee->photo ? 'data:image/jpeg;base64,'.$employee->photo : '' }}',
                                            initials: '{{ strtoupper(substr($employee->employee_name, 0, 2)) }}',
                                            croppedBase64: ''
                                         }"
                                         @photo-updated.window="if($event.detail.id == {{ $employee->id_employee }}) { preview = $event.detail.base64; croppedBase64 = $event.detail.base64; }">
                                        
                                        {{-- Hidden input for base64 --}}
                                        <input type="hidden" :name="'employee_photos_base64[' + {{ $employee->id_employee }} + ']'" x-model="croppedBase64">
                                        
                                        <button type="button" @click="triggerCropper({{ $employee->id_employee }})" 
                                                class="relative w-12 h-12 rounded-full overflow-hidden bg-gray-100 border-2 border-white shadow-sm group-hover:border-gray-200 transition-all focus:outline-none">
                                            
                                            <template x-if="preview">
                                                <img :src="preview" class="w-full h-full object-cover">
                                            </template>
                                            
                                            <template x-if="!preview">
                                                <div class="w-full h-full flex items-center justify-center text-[11px] font-bold text-gray-400 uppercase" x-text="initials"></div>
                                            </template>

                                            {{-- Overlay Camera Icon on Hover --}}
                                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                        </button>

                                        {{-- Delete Photo Button --}}
                                        <button type="button" x-show="preview" 
                                                @click="preview = ''; croppedBase64 = 'DELETE'"
                                                class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center shadow-sm hover:bg-red-600 transition-colors focus:outline-none">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $employee->employee_name }}</p>
                                        
                                        {{-- Instagram Input --}}
                                        <div class="mt-1 flex items-center gap-1.5">
                                            <span class="text-[10px] font-bold text-gray-400">@</span>
                                            <input type="text" 
                                                   name="instagram_usernames[{{ $employee->id_employee }}]" 
                                                   value="{{ $employee->instagram_username }}"
                                                   placeholder="username_ig"
                                                   class="w-full max-w-[120px] bg-transparent border-0 border-b border-gray-100 p-0 text-[11px] text-gray-500 placeholder-gray-300 focus:ring-0 focus:border-gray-900 transition-colors">
                                        </div>

                                        <p class="text-[10px] text-gray-400 uppercase tracking-wide mt-1">{{ $employee->position ?? 'Stylist' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                        @if($employees->isEmpty())
                            <p class="px-5 py-6 text-sm text-gray-400 text-center">Belum ada stylist.</p>
                        @endif
                    </div>


                    {{-- ── Card Footer: Save Bar ───────────────────── --}}
                    <div class="sticky bottom-0 z-10 flex items-center justify-between px-5 py-3.5 bg-white border-t border-gray-100">
                        <p class="text-xs text-gray-400">Klik &ldquo;Simpan&rdquo; untuk menerapkan perubahan foto dan visibilitas.</p>
                        <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan
                        </button>
                    </div>

                </div>{{-- /card --}}

            </form>
        </div>

        {{-- ── CROPPER MODAL ────────────────────────── --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     class="fixed inset-0 transition-opacity bg-black bg-opacity-75" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Sesuaikan Foto</h3>
                        <p class="text-sm text-gray-400 mt-1 mb-4">Geser atau zoom untuk mengatur posisi foto terbaik.</p>
                        
                        <div class="relative w-full aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-100">
                            <img id="cropper-image" src="" class="max-w-full block">
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 flex flex-row-reverse gap-2 sm:px-6">
                        <button type="button" @click="applyCrop()" class="inline-flex justify-center w-full px-4 py-2 text-sm font-semibold text-white bg-gray-900 border border-transparent rounded-lg hover:bg-gray-700 sm:w-auto">
                            Potong & Simpan
                        </button>
                        <button type="button" @click="closeModal()" class="inline-flex justify-center w-full px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 sm:w-auto">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden global file input for cropper --}}
        <input type="file" id="global-file-input" class="hidden" accept="image/*" @change="handleFileSelect">
    </div>

    <script>
        // Tab switching
        function switchTab(id, trigger) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tab-trigger').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-' + id).classList.add('active');
            trigger.classList.add('active');
        }

        // Toggle all in a group
        function toggleAll(group) {
            const boxes = document.querySelectorAll(`.${group}-check`);
            const allOn = Array.from(boxes).every(b => b.checked);
            boxes.forEach(b => {
                b.checked = !allOn;
                b.closest('.vis-row').classList.toggle('off', !b.checked);
            });
        }

        // Cropper Manager (Alpine.js)
        function cropperManager() {
            return {
                showModal: false,
                currentEmployeeId: null,
                cropper: null,

                triggerCropper(id) {
                    this.currentEmployeeId = id;
                    document.getElementById('global-file-input').click();
                },

                handleFileSelect(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = (event) => {
                        this.showModal = true;
                        const image = document.getElementById('cropper-image');
                        image.src = event.target.result;

                        if (this.cropper) {
                            this.cropper.destroy();
                        }

                        this.$nextTick(() => {
                            this.cropper = new Cropper(image, {
                                aspectRatio: 1,
                                viewMode: 1,
                                autoCropArea: 1,
                                responsive: true,
                                guides: true,
                                center: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                            });
                        });
                    };
                    reader.readAsDataURL(file);
                    // Clear input so same file can be selected again
                    e.target.value = '';
                },

                applyCrop() {
                    if (!this.cropper) return;

                    const canvas = this.cropper.getCroppedCanvas({
                        width: 400,
                        height: 400,
                    });

                    const base64 = canvas.toDataURL('image/jpeg', 0.85);
                    
                    // Dispatch event to update the specific employee row
                    window.dispatchEvent(new CustomEvent('photo-updated', {
                        detail: {
                            id: this.currentEmployeeId,
                            base64: base64
                        }
                    }));

                    this.closeModal();
                },

                closeModal() {
                    this.showModal = false;
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                }
            }
        }
    </script>
</x-app-layout>