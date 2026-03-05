<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Jadwal</h2>
    </x-slot>

    <div class="py-6" x-data="{
        activeDay: 'all',
        activeStore: '{{ $selectedStoreId ?? 'all' }}',
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md text-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-green-600 hover:text-green-800 ml-2">&times;</button>
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                <ul class="list-disc pl-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- 1. TOP NAVBAR / FILTER HEADER (DESAIN MOCKUP) --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between px-6 py-4 mb-4">

                {{-- Kiri: Filter Toko & Tabs Hari --}}
                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    {{-- Filter Toko --}}
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-slate-500">Toko:</label>
                        <select x-model="activeStore" @change="window.location.search = '?store_id=' + $event.target.value" class="block w-48 pl-3 pr-8 py-2 text-sm font-medium border-slate-200 focus:outline-none focus:ring-slate-500 focus:border-slate-500 rounded-lg bg-white hover:bg-slate-50 transition cursor-pointer shadow-sm">
                            <option value="all" {{ $selectedStoreId == null ? 'selected' : '' }}>Semua Toko</option>
                            @foreach ($stores as $store)
                            <option value="{{ $store->id_store }}" {{ $selectedStoreId == $store->id_store ? 'selected' : '' }}>
                                {{ $store->store_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="hidden md:block w-px h-6 bg-slate-200"></div>

                    {{-- Tabs Hari --}}
                    <div class="flex flex-wrap items-center gap-1 bg-white">
                        <button @click="activeDay = 'all'" :class="activeDay === 'all' ? 'bg-[#1e293b] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                            Semua
                        </button>
                        @php
                        $daysMap = ['Senin' => 'Sen', 'Selasa' => 'Sel', 'Rabu' => 'Rab', 'Kamis' => 'Kam', 'Jumat' => 'Jum', 'Sabtu' => 'Sab', 'Minggu' => 'Min'];
                        @endphp
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $fullDay)
                        <button @click="activeDay = '{{ $fullDay }}'" :class="activeDay === '{{ $fullDay }}' ? 'bg-[#1e293b] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                            {{ $daysMap[$fullDay] }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Kanan: Info Slot & Actions --}}
                <div class="flex items-center gap-6 mt-4 lg:mt-0 pt-4 lg:pt-0 border-t border-slate-100 lg:border-0 pl-0 lg:pl-6">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold text-slate-400">
                            {{ $slots->flatten()->count() }} slot
                        </span>
                        <form action="{{ route('admin.reservation.slots.destroyAll') }}" method="POST" onsubmit="return confirm('Hapus semua slot jadwal di toko Anda?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 transition">
                                Reset semua
                            </button>
                        </form>
                    </div>
                    <button @click="showCreateModal = true" class="inline-flex items-center gap-2 px-4 py-2 bg-[#1e293b] text-white text-sm font-bold rounded-lg hover:bg-slate-800 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Jadwal
                    </button>
                </div>
            </div>

            {{-- 2. DAFTAR JADWAL (GROUP BY DAY) --}}
            <div class="space-y-4">
                @php
                $orderedDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                @endphp

                @foreach($orderedDays as $day)
                @if(isset($slots[$day]) && $slots[$day]->count() > 0)
                @php
                $daySlots = $slots[$day];
                @endphp
                {{-- Blok Hari --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden"
                    x-show="activeDay === 'all' || activeDay === '{{ $day }}'"
                    x-transition.opacity>

                    {{-- Header Hari --}}
                    <div class="px-8 py-5 border-b border-slate-50 flex items-center gap-3 bg-white">
                        <h3 class="font-bold text-slate-800 text-base">{{ $day }}</h3>
                        <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                        <span class="text-xs font-medium text-slate-400">{{ $daySlots->count() }} slot</span>
                    </div>

                    {{-- List Jam Grid Layout --}}
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-slate-50/50">
                        @foreach($daySlots as $slot)
                        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow-md hover:border-slate-300 transition-all flex flex-col gap-3 group relative"
                            x-show="activeStore === 'all' || activeStore == '{{ $slot->id_store }}'">

                            {{-- Top: Jam & Aksi --}}
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2">
                                    <div class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-black text-slate-800 text-lg tracking-wide">
                                        {{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-1">
                                    <button @click="openEdit(
                                                    {{ $slot->id_slot }},
                                                    '{{ $slot->day_of_week }}',
                                                    '{{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}',
                                                    [{{ $slot->employees->pluck('id_employee')->implode(',') }}],
                                                    {{ $slot->id_store }},
                                                    {{ $slot->quota }}
                                                )" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.reservation.slots.destroy', $slot->id_slot) }}" method="POST" class="inline" onsubmit="return confirm('Hapus slot jadwal ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Middle: Toko & Kuota --}}
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 flex py-1 rounded-md border border-slate-200/60 truncate max-w-[120px]">
                                    {{ $slot->store->store_name ?? 'N/A' }}
                                </span>
                                <span class="text-[11px] text-slate-500 font-medium bg-white border border-slate-200 px-2 py-0.5 rounded-full shadow-sm">
                                    Kuota <strong>{{ $slot->quota }}</strong>
                                </span>
                            </div>

                            {{-- Bottom: Karyawan --}}
                            <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                @forelse($slot->employees as $emp)
                                <span class="inline-flex items-center px-1.5 py-1 rounded bg-indigo-50 border border-indigo-100 text-[10px] font-bold text-indigo-700 tracking-wide uppercase shadow-sm">
                                    {{ $emp->employee_name }}
                                </span>
                                @empty
                                <span class="inline-flex items-center px-2 py-1 rounded bg-slate-50 border border-slate-200 text-[10px] font-bold text-slate-400 italic">
                                    Belum ada Staff
                                </span>
                                @endforelse
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endforeach

                @if ($slots->flatten()->count() === 0)
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