<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Jadwal</h2>
    </x-slot>

    <div class="py-6" x-data="{
        activeDay: 'all',
        selectedStoreId: '{{ $selectedStoreId }}',
        showCreateModal: false,
        showEditModal: false,
        editUrl: '',
        editSlotDay: '',
        editSlotTimeInput: '',
        currentEmployees: [],
        editSlotStoreId: null,
        editSlotQuota: 1,
        modalDropdownOpen: false,
        inputMode: 'bulk',
        csrfToken: '{{ csrf_token() }}',
        dayDataUrl: '{{ route('admin.reservation.slots.dayData') }}',
        slotBaseUrl: '{{ url('/admin/reservation/slots') }}',
        allEmployees: [
            @foreach($employees as $e)
                { id: {{ $e->id_employee }}, name: '{{ addslashes($e->employee_name) }}', store_id: {{ $e->id_store }} },
            @endforeach
        ],
        stores: [
            @foreach($stores as $s)
                { id: {{ $s->id_store }}, name: '{{ addslashes($s->store_name) }}' },
            @endforeach
        ],
        openEdit(id, day, time, employees, storeId, quota) {
            this.editUrl = '{{ url('/admin/reservation/slots') }}/' + id;
            this.editSlotDay = day;
            this.editSlotTimeInput = time;
            this.currentEmployees = employees.map(Number);
            this.editSlotStoreId = Number(storeId);
            this.editSlotQuota = quota;
            this.showEditModal = true;
            this.modalDropdownOpen = false;
        },
        toggleModalEmployee(id) {
            id = Number(id);
            this.currentEmployees.includes(id)
                ? this.currentEmployees = this.currentEmployees.filter(x => x !== id)
                : this.currentEmployees.push(id);
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            {{-- Flash messages --}}
            
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- 1. HEADER & CONTROL PANEL --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden mb-4">
                <div class="p-4 sm:p-5 lg:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        {{-- Left Side: Identity & Controls --}}
                        <div class="flex-1">
                            <h1 class="text-lg font-bold text-slate-800 tracking-tight">Kelola Jadwal Operasional</h1>
                            <p class="text-xs text-slate-500">Atur slot reservasi dan penugasan karyawan.</p>

                            <div class="flex flex-wrap items-center gap-3 mt-4">
                                {{-- Store Selector --}}
                                <div class="relative">
                                    <select @change="window.location.search = '?store_id=' + $event.target.value"
                                        class="bg-slate-50 border-none ring-1 ring-slate-200 text-slate-600 text-[11px] font-bold rounded-lg pl-3 pr-8 py-2 focus:ring-1 focus:ring-indigo-500 transition-all cursor-pointer">
                                        @foreach ($stores as $store)
                                        <option value="{{ $store->id_store }}" {{ $selectedStoreId == $store->id_store ? 'selected' : '' }}>
                                            📍 {{ $store->store_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Day Navigation --}}
                                <div class="flex flex-wrap gap-1 p-1 bg-slate-100 rounded-lg">
                                    <button @click="activeDay = 'all'"
                                        :class="activeDay === 'all' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                        class="px-3 py-1.5 rounded-md text-[9px] font-bold uppercase transition-all">
                                        ALL
                                    </button>
                                    @php
                                    $shortDays = ['Senin' => 'SEN', 'Selasa' => 'SEL', 'Rabu' => 'RAB', 'Kamis' => 'KAM', 'Jumat' => 'JUM', 'Sabtu' => 'SAB', 'Minggu' => 'MIN'];
                                    @endphp
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $d)
                                    <button @click="activeDay = '{{ $d }}'"
                                        :class="activeDay === '{{ $d }}' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                        class="px-3 py-1.5 rounded-md text-[9px] font-bold uppercase transition-all">
                                        {{ $shortDays[$d] }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Right Side: Metric & Actions --}}
                        <div class="flex items-center justify-between lg:justify-end gap-6 lg:border-l border-slate-100 lg:pl-6">
                            <div class="flex flex-col items-end">
                                <span class="text-xl font-bold text-indigo-600 leading-none">{{ $totalSlotCount }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase mt-1">Total Slots</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <form action="{{ route('admin.reservation.slots.destroyAll') }}" method="POST" onsubmit="return confirm('Hapus semua slot jadwal?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Reset Semua">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                <button @click="showCreateModal = true"
                                    class="flex items-center gap-2 px-4 py-2.5 bg-slate-800 text-white text-[10px] font-bold rounded-lg hover:bg-slate-900 transition-all shadow-sm">
                                    <svg class="w-3.5 h-3.5 italic" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    TAMBAH JADWAL
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. DAFTAR JADWAL (ACCORDION + AJAX LAZY-LOAD) --}}
            <div class="space-y-3">
                @php
                $orderedDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                $dayLabels = ['Senin' => 'Se', 'Selasa' => 'Sl', 'Rabu' => 'Ra', 'Kamis' => 'Ka', 'Jumat' => 'Ju', 'Sabtu' => 'Sa', 'Minggu' => 'Mi'];
                @endphp

                @foreach($orderedDays as $day)
                @if(isset($daySummaries[$day]))
                @php $summary = $daySummaries[$day]; @endphp

                {{-- Accordion Hari --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden"
                    x-data="{
                        open: false,
                        slots: [],
                        loaded: false,
                        loading: false,
                        async loadSlots() {
                            if (this.loaded) return;
                            this.loading = true;
                            try {
                                const res = await fetch(`${dayDataUrl}?day={{ $day }}&store_id=${selectedStoreId}`);
                                this.slots = await res.json();
                                this.loaded = true;
                            } catch(e) { console.error(e); }
                            this.loading = false;
                        },
                        toggle() {
                            this.open = !this.open;
                            if (this.open) this.loadSlots();
                        }
                    }"
                    x-show="activeDay === 'all' || activeDay === '{{ $day }}'"
                    x-transition.opacity>

                    {{-- Accordion Header --}}
                    <button @click="toggle()" class="w-full px-4 py-3.5 flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer select-none">
                        <div class="flex items-center gap-4">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-xs"
                                :class="open ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500'">
                                {{ substr($day, 0, 2) }}
                            </div>
                            <div class="text-left font-bold text-slate-700 text-sm">
                                <span>{{ $day }}</span>
                                <span class="mx-2 text-slate-300">·</span>
                                <span class="text-[10px] text-slate-400 font-normal uppercase tracking-wider">{{ $summary->slot_count }} Slots</span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    {{-- Accordion Body --}}
                    <div x-show="open" x-collapse x-cloak>
                        <div x-show="loading" class="py-6 flex justify-center bg-slate-50/30">
                            <div class="w-5 h-5 border-2 border-indigo-200 border-t-indigo-500 rounded-full animate-spin"></div>
                        </div>

                        {{-- Desktop Table --}}
                        <div x-show="!loading && slots.length > 0" class="hidden md:block border-t border-slate-100">
                            <table class="w-full text-xs">
                                <thead class="bg-slate-50/50 text-[10px] text-slate-400 uppercase font-black tracking-wider">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Jam</th>
                                        <th class="px-4 py-3 text-left">Staf</th>
                                        <th class="px-4 py-3 text-center">Kuota</th>
                                        <th class="px-6 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <template x-for="slot in slots" :key="slot.id">
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-2.5 font-bold text-slate-700" x-text="slot.time"></td>
                                            <td class="px-4 py-2.5">
                                                <div class="flex flex-wrap gap-1">
                                                    <template x-for="emp in slot.employees" :key="emp.id">
                                                        <span class="px-1.5 py-0.5 rounded-md bg-white border border-slate-200 text-[9px] font-bold text-slate-500" x-text="emp.name"></span>
                                                    </template>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2.5 text-center font-bold text-slate-600" x-text="slot.quota"></td>
                                            <td class="px-6 py-2.5 text-right">
                                                <div class="flex items-center justify-end gap-1">
                                                    <button @click="openEdit(slot.id, slot.day, slot.time, slot.employees.map(e => e.id), slot.store_id, slot.quota)" class="p-1.5 text-slate-300 hover:text-indigo-600 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                                    <form :action="slotBaseUrl + '/' + slot.id" method="POST" class="inline">
                                                        <input type="hidden" name="_token" :value="csrfToken">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="p-1.5 text-slate-300 hover:text-red-500 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile List --}}
                        <div x-show="!loading && slots.length > 0" class="block md:hidden border-t border-slate-100 divide-y divide-slate-50">
                            <template x-for="slot in slots" :key="slot.id">
                                <div class="px-4 py-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="px-2 py-1 bg-slate-50 rounded-md text-[11px] font-bold text-slate-600" x-text="slot.time"></div>
                                        <div class="text-[10px] text-slate-400 font-bold" x-text="slot.quota + ' Qty'"></div>
                                    </div>
                                    <div class="flex gap-1">
                                        <button @click="openEdit(slot.id, slot.day, slot.time, slot.employees.map(e => e.id), slot.store_id, slot.quota)" class="p-1.5 text-slate-300"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach

                @if ($totalSlotCount === 0)
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-12 flex flex-col items-center justify-center text-center">
                    <div class="bg-slate-50 p-4 rounded-full mb-4">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-bold text-lg">Belum Ada Jadwal Reservasi</h3>
                    <p class="text-slate-500 text-sm mt-1 max-w-sm">
                        Silakan klik "Tambah Jadwal" untuk membuka slot.
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- ========== MODAL: BUAT JADWAL ========== --}}
        <div x-show="showCreateModal" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showCreateModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/30" @click="showCreateModal = false"></div>

                <div x-show="showCreateModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-lg shadow-xl w-full max-w-md">

                    <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Tambah Jadwal</h3>
                        <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>

                    {{-- Mode tabs --}}
                    <div class="px-5 pt-4 flex gap-4 border-b border-gray-100">
                        <button @click="inputMode = 'bulk'"
                            class="pb-2 text-sm font-medium border-b-2 transition-colors"
                            :class="inputMode === 'bulk' ? 'border-gray-800 text-gray-800' : 'border-transparent text-gray-400 hover:text-gray-600'">
                            Generate Massal
                        </button>
                        <button @click="inputMode = 'single'"
                            class="pb-2 text-sm font-medium border-b-2 transition-colors"
                            :class="inputMode === 'single' ? 'border-gray-800 text-gray-800' : 'border-transparent text-gray-400 hover:text-gray-600'">
                            Input Satuan
                        </button>
                    </div>

                    {{-- Form Massal --}}
                    <div x-show="inputMode === 'bulk'">
                        <form method="POST" action="{{ route('admin.reservation.slots.store') }}">
                            @csrf
                            <input type="hidden" name="input_type" value="bulk">
                            <div class="p-5 space-y-4" x-data="{ selectedStore: '' }">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Toko</label>
                                    <select name="id_store" x-model="selectedStore" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Pilih toko</option>
                                        @foreach($stores as $store)
                                        <option value="{{ $store->id_store }}">{{ $store->store_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Hari --}}
                                <div x-data='{ open: false, selected: [], dlist: ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"], toggle(d){ this.selected.includes(d)?this.selected=this.selected.filter(x=>x!==d):this.selected.push(d)} }'>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                                    <template x-for="d in selected"><input type="hidden" name="day_of_week[]" :value="d"></template>
                                    <div class="relative">
                                        <button type="button" @click="open=!open" @click.outside="open=false" class="w-full text-left text-sm bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 flex justify-between items-center focus:outline-none focus:ring-1 focus:ring-indigo-300">
                                            <span x-text="selected.length ? selected.join(', ') : 'Pilih hari'" :class="selected.length ? 'text-gray-800' : 'text-gray-400'" class="truncate"></span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="open" style="display:none" class="absolute z-30 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-48 overflow-y-auto">
                                            <template x-for="d in dlist">
                                                <label class="flex items-center px-3 py-2 hover:bg-gray-50 cursor-pointer text-sm" @click="toggle(d)">
                                                    <div class="w-4 h-4 border rounded mr-2 flex items-center justify-center" :class="selected.includes(d) ? 'bg-gray-800 border-gray-800' : 'border-gray-300'">
                                                        <svg x-show="selected.includes(d)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    <span x-text="d" class="text-gray-700"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- Karyawan --}}
                                <div x-data='{ open: false, selected: [], toggle(id){this.selected.includes(id)?this.selected=this.selected.filter(x=>x!==id):this.selected.push(id)} }'>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                                    <template x-for="id in selected"><input type="hidden" name="employee_ids[]" :value="id"></template>
                                    <div class="relative">
                                        <button type="button" @click="selectedStore ? open=!open : alert('Pilih toko dulu')" @click.outside="open=false" class="w-full text-left text-sm bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 flex justify-between items-center focus:outline-none focus:ring-1 focus:ring-indigo-300" :class="!selectedStore && 'bg-gray-50'">
                                            <span x-text="selected.length ? selected.length + ' karyawan' : 'Pilih karyawan'" :class="selected.length ? 'text-gray-800' : 'text-gray-400'" class="truncate"></span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="open" style="display:none" class="absolute z-30 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-48 overflow-y-auto">
                                            <template x-for="e in allEmployees.filter(emp => emp.store_id == selectedStore)">
                                                <label class="flex items-center px-3 py-2 hover:bg-gray-50 cursor-pointer text-sm" @click="toggle(e.id)">
                                                    <div class="w-4 h-4 border rounded mr-2 flex items-center justify-center" :class="selected.includes(e.id) ? 'bg-gray-800 border-gray-800' : 'border-gray-300'">
                                                        <svg x-show="selected.includes(e.id)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    <span x-text="e.name" class="text-gray-700"></span>
                                                </label>
                                            </template>
                                            <div x-show="allEmployees.filter(emp => emp.store_id == selectedStore).length === 0" class="px-3 py-2 text-sm text-gray-400">Tidak ada karyawan</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam mulai</label>
                                        <input type="time" name="start_time" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam selesai</label>
                                        <input type="time" name="end_time" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Interval</label>
                                        <select name="interval" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="30">30 menit</option>
                                            <option value="45">45 menit</option>
                                            <option value="60" selected>60 menit</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kuota</label>
                                        <input type="number" name="quota" value="1" min="1" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    </div>
                                </div>
                            </div>
                            <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-end gap-2 rounded-b-lg">
                                <button type="button" @click="showCreateModal = false" class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800">Batal</button>
                                <button type="submit" class="px-4 py-1.5 text-sm font-medium text-white bg-gray-800 rounded-md hover:bg-gray-700">Generate</button>
                            </div>
                        </form>
                    </div>

                    {{-- Form Satuan --}}
                    <div x-show="inputMode === 'single'" style="display:none;">
                        <form method="POST" action="{{ route('admin.reservation.slots.store') }}">
                            @csrf
                            <input type="hidden" name="input_type" value="single">
                            <div class="p-5 space-y-4" x-data="{ selectedStore: '' }">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Toko</label>
                                    <select name="id_store" x-model="selectedStore" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Pilih toko</option>
                                        @foreach($stores as $store)
                                        <option value="{{ $store->id_store }}">{{ $store->store_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                                        <select name="day_of_week" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                            <option value="">Pilih hari</option>
                                            @foreach(["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"] as $day)
                                            <option value="{{ $day }}">{{ $day }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam</label>
                                        <input type="time" name="slot_time" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    </div>
                                </div>

                                {{-- Karyawan --}}
                                <div x-data='{ open: false, selected: [], toggle(id){this.selected.includes(id)?this.selected=this.selected.filter(x=>x!==id):this.selected.push(id)} }'>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                                    <template x-for="id in selected"><input type="hidden" name="employee_ids[]" :value="id"></template>
                                    <div class="relative">
                                        <button type="button" @click="selectedStore ? open=!open : alert('Pilih toko dulu')" @click.outside="open=false" class="w-full text-left text-sm bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 flex justify-between items-center focus:outline-none focus:ring-1 focus:ring-indigo-300" :class="!selectedStore && 'bg-gray-50'">
                                            <span x-text="selected.length ? selected.length + ' dipilih' : 'Pilih karyawan'" :class="selected.length ? 'text-gray-800' : 'text-gray-400'" class="truncate"></span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="open" style="display:none" class="absolute z-30 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-48 overflow-y-auto">
                                            <template x-for="e in allEmployees.filter(emp => emp.store_id == selectedStore)">
                                                <label class="flex items-center px-3 py-2 hover:bg-gray-50 cursor-pointer text-sm" @click="toggle(e.id)">
                                                    <div class="w-4 h-4 border rounded mr-2 flex items-center justify-center" :class="selected.includes(e.id) ? 'bg-gray-800 border-gray-800' : 'border-gray-300'">
                                                        <svg x-show="selected.includes(e.id)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    <span x-text="e.name" class="text-gray-700"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kuota</label>
                                    <input type="number" name="quota" value="1" min="1" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                            <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-end gap-2 rounded-b-lg">
                                <button type="button" @click="showCreateModal = false" class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800">Batal</button>
                                <button type="submit" class="px-4 py-1.5 text-sm font-medium text-white bg-gray-800 rounded-md hover:bg-gray-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== MODAL: EDIT JADWAL ========== --}}
        <div x-show="showEditModal" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/30" @click="showEditModal = false"></div>

                <div x-show="showEditModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-lg shadow-xl w-full max-w-md">
                    <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Edit Jadwal &mdash; <span x-text="editSlotDay"></span></h3>
                        <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>

                    <form :action="editUrl" method="POST">
                        @csrf @method('PUT')
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam</label>
                                <input type="time" name="slot_time" x-model="editSlotTimeInput" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan</label>
                                <template x-for="id in currentEmployees"><input type="hidden" name="employee_ids[]" :value="id"></template>
                                <div class="relative">
                                    <button type="button" @click="modalDropdownOpen = !modalDropdownOpen" @click.outside="modalDropdownOpen = false" class="w-full text-left text-sm bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 flex justify-between items-center focus:outline-none focus:ring-1 focus:ring-indigo-300">
                                        <span x-text="currentEmployees.length ? currentEmployees.length + ' dipilih' : 'Pilih karyawan'" :class="currentEmployees.length ? 'text-gray-800' : 'text-gray-400'" class="truncate"></span>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="modalDropdownOpen" style="display:none" class="absolute z-30 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-48 overflow-y-auto">
                                        <template x-for="e in allEmployees.filter(emp => emp.store_id == editSlotStoreId)">
                                            <label class="flex items-center px-3 py-2 hover:bg-gray-50 cursor-pointer text-sm" @click="toggleModalEmployee(e.id)">
                                                <div class="w-4 h-4 border rounded mr-2 flex items-center justify-center" :class="currentEmployees.includes(e.id) ? 'bg-gray-800 border-gray-800' : 'border-gray-300'">
                                                    <svg x-show="currentEmployees.includes(e.id)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                                <span x-text="e.name" class="text-gray-700"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kuota</label>
                                <input type="number" name="quota" x-model="editSlotQuota" min="1" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                        </div>
                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-end gap-2 rounded-b-lg">
                            <button type="button" @click="showEditModal = false" class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800">Batal</button>
                            <button type="submit" class="px-4 py-1.5 text-sm font-medium text-white bg-gray-800 rounded-md hover:bg-gray-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>