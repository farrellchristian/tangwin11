<x-app-layout>
    {{-- HEADER WITH GRADIENT ACCENT --}}
    <x-slot name="header">
        <div class="relative">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <h2 class="font-extrabold text-3xl text-gray-900 tracking-tight">
                        Kelola Jadwal
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 font-medium">Atur ketersediaan slot waktu untuk semua cabang.</p>
                </div>
                
                <div class="flex items-center gap-3 bg-white p-1.5 rounded-xl shadow-sm border border-gray-100">
                    <div class="px-4 py-2 bg-indigo-50 rounded-lg text-indigo-700 text-sm font-bold flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-500"></span>
                        </span>
                        {{ $slots->count() }} Slot Aktif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen" x-data="{ 
        // --- LOGIC DATA ---
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

        // --- FUNGSI ---
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
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 flex items-center p-4 text-sm text-green-800 border border-green-200 rounded-2xl bg-green-50 shadow-sm relative overflow-hidden">
                    <div class="absolute inset-0 bg-green-100 opacity-20"></div>
                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3 z-10" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                    <span class="font-bold mr-1 z-10">Sukses!</span> <span class="z-10">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto z-10 text-green-600 hover:text-green-800"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm">
                    <p class="font-bold">Periksa input Anda:</p>
                    <ul class="list-disc pl-5 text-sm mt-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- LAYOUT SPLIT UTAMA --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- KOLOM KIRI: FORM CONTROL PANEL (Sticky) --}}
                <div class="lg:col-span-4 lg:sticky lg:top-8 space-y-6">
                    
                    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
                        {{-- Tab Switcher --}}
                        <div class="p-2 bg-gray-50/80 border-b border-gray-100 grid grid-cols-2 gap-1">
                            <button @click="inputMode = 'bulk'" 
                                :class="inputMode === 'bulk' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:bg-gray-100'"
                                class="py-2.5 text-sm font-bold rounded-xl transition-all duration-200 flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                Massal
                            </button>
                            <button @click="inputMode = 'single'" 
                                :class="inputMode === 'single' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:bg-gray-100'"
                                class="py-2.5 text-sm font-bold rounded-xl transition-all duration-200 flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Satuan
                            </button>
                        </div>

                        <div class="p-6">
                            {{-- FORM BULK --}}
                            <div x-show="inputMode === 'bulk'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">
                                <form method="POST" action="{{ route('admin.reservation.slots.store') }}" class="space-y-5">
                                    @csrf
                                    <input type="hidden" name="input_type" value="bulk">
                                    
                                    <div x-data="{ selectedStore: '' }">
                                        <div class="mb-4">
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Toko</label>
                                            <div class="relative">
                                                <select name="id_store" x-model="selectedStore" class="block w-full pl-3 pr-10 py-3 text-base border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-xl transition-shadow shadow-sm bg-gray-50 hover:bg-white cursor-pointer" required>
                                                    <option value="">-- Pilih Lokasi --</option>
                                                    @foreach($stores as $store)
                                                        <option value="{{ $store->id_store }}">{{ $store->store_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- HARI --}}
                                        <div x-data='{ open: false, selected: [], days: ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"], toggle(d){ this.selected.includes(d)?this.selected=this.selected.filter(x=>x!==d):this.selected.push(d)} }' class="relative mb-4">
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Hari Operasional</label>
                                            <template x-for="d in selected"><input type="hidden" name="day_of_week[]" :value="d"></template>
                                            <button type="button" @click="open=!open" @click.outside="open=false" class="w-full bg-white border border-gray-200 rounded-xl shadow-sm px-4 py-3 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 flex justify-between items-center group hover:border-indigo-300 transition-colors">
                                                <span x-text="selected.length ? selected.join(', ') : 'Pilih hari...'" :class="{'text-gray-400': !selected.length, 'text-gray-800 font-medium': selected.length}"></span>
                                                <svg class="h-5 w-5 text-gray-400 group-hover:text-indigo-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </button>
                                            <div x-show="open" style="display:none" class="absolute z-20 mt-1 w-full bg-white shadow-xl max-h-60 rounded-xl py-2 ring-1 ring-black/5 overflow-auto custom-scrollbar border border-gray-100">
                                                <template x-for="d in days">
                                                    <div @click="toggle(d)" class="cursor-pointer py-2.5 px-4 hover:bg-indigo-50 flex justify-between items-center transition-colors">
                                                        <span x-text="d" class="text-sm font-medium text-gray-700"></span>
                                                        <div x-show="selected.includes(d)" class="h-5 w-5 bg-indigo-500 rounded-full flex items-center justify-center text-white text-xs">✓</div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        {{-- KARYAWAN --}}
                                        <div x-data='{ open: false, selected: [], toggle(id){this.selected.includes(id)?this.selected=this.selected.filter(x=>x!==id):this.selected.push(id)} }' class="relative mb-4">
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Karyawan Bertugas</label>
                                            <template x-for="id in selected"><input type="hidden" name="employee_ids[]" :value="id"></template>
                                            <button type="button" @click="selectedStore ? open=!open : alert('Silakan pilih toko terlebih dahulu!')" @click.outside="open=false" class="w-full bg-white border border-gray-200 rounded-xl shadow-sm px-4 py-3 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 flex justify-between items-center group hover:border-indigo-300 transition-colors" :class="{'bg-gray-50 cursor-not-allowed opacity-75': !selectedStore}">
                                                <span x-text="selected.length ? selected.length + ' Karyawan dipilih' : (selectedStore ? 'Pilih karyawan...' : 'Pilih Toko dulu...')" :class="{'text-gray-400': !selected.length, 'text-gray-800 font-medium': selected.length}"></span>
                                                <svg class="h-5 w-5 text-gray-400 group-hover:text-indigo-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </button>
                                            <div x-show="open" style="display:none" class="absolute z-20 mt-1 w-full bg-white shadow-xl max-h-60 rounded-xl py-2 ring-1 ring-black/5 overflow-auto border border-gray-100">
                                                <template x-for="e in allEmployees.filter(emp => emp.store_id == selectedStore)">
                                                    <div @click="toggle(e.id)" class="cursor-pointer py-2.5 px-4 hover:bg-indigo-50 flex justify-between items-center transition-colors">
                                                        <span x-text="e.name" class="text-sm font-medium text-gray-700"></span>
                                                        <div x-show="selected.includes(e.id)" class="h-5 w-5 bg-indigo-500 rounded-full flex items-center justify-center text-white text-xs">✓</div>
                                                    </div>
                                                </template>
                                                <div x-show="allEmployees.filter(emp => emp.store_id == selectedStore).length === 0" class="py-3 px-4 text-gray-500 text-sm italic text-center">Tidak ada data karyawan.</div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Mulai</label>
                                                <input type="time" name="start_time" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Selesai</label>
                                                <input type="time" name="end_time" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kuota / Slot</label>
                                            <div class="relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">👥</span>
                                                </div>
                                                <input type="number" name="quota" value="1" min="1" class="w-full pl-10 border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                                            </div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Durasi (Interval)</label>
                                            <div class="bg-gray-50 p-1.5 rounded-xl flex gap-1">
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="interval" value="30" class="peer sr-only">
                                                    <div class="text-center py-2 text-sm font-medium text-gray-600 rounded-lg peer-checked:bg-white peer-checked:text-indigo-600 peer-checked:shadow-sm transition-all">30 Min</div>
                                                </label>
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="interval" value="60" checked class="peer sr-only">
                                                    <div class="text-center py-2 text-sm font-medium text-gray-600 rounded-lg peer-checked:bg-white peer-checked:text-indigo-600 peer-checked:shadow-sm transition-all">60 Min</div>
                                                </label>
                                            </div>
                                        </div>

                                        <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform active:scale-95">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                            Generate Jadwal
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- FORM SINGLE --}}
                            <div x-show="inputMode === 'single'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                                <form method="POST" action="{{ route('admin.reservation.slots.store') }}" class="space-y-5">
                                    @csrf
                                    <input type="hidden" name="input_type" value="single">

                                    <div x-data="{ selectedStore: '' }">
                                        <div class="mb-4">
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Toko</label>
                                            <select name="id_store" x-model="selectedStore" class="block w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-50" required>
                                                <option value="">-- Pilih Lokasi --</option>
                                                @foreach($stores as $store)
                                                    <option value="{{ $store->id_store }}">{{ $store->store_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Hari</label>
                                                <select name="day_of_week" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                                                    <option value="">- Hari -</option>
                                                    @foreach(["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"] as $day)
                                                        <option value="{{ $day }}">{{ $day }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam</label>
                                                <input type="time" name="slot_time" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                                            </div>
                                        </div>

                                        {{-- Karyawan (Single) --}}
                                        <div x-data='{ open: false, selected: [], toggle(id){this.selected.includes(id)?this.selected=this.selected.filter(x=>x!==id):this.selected.push(id)} }' class="relative mb-4">
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Karyawan</label>
                                            <template x-for="id in selected"><input type="hidden" name="employee_ids[]" :value="id"></template>
                                            <button type="button" @click="selectedStore ? open=!open : alert('Silakan pilih toko terlebih dahulu!')" @click.outside="open=false" class="w-full bg-white border border-gray-200 rounded-xl shadow-sm px-4 py-3 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 flex justify-between items-center group" :class="{'bg-gray-50 cursor-not-allowed': !selectedStore}">
                                                <span x-text="selected.length ? selected.length + ' Dipilih' : 'Pilih...'" :class="{'text-gray-400': !selected.length, 'text-gray-800 font-medium': selected.length}"></span>
                                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </button>
                                            <div x-show="open" style="display:none" class="absolute z-20 mt-1 w-full bg-white shadow-xl max-h-60 rounded-xl py-2 ring-1 ring-black/5 overflow-auto border border-gray-100">
                                                <template x-for="e in allEmployees.filter(emp => emp.store_id == selectedStore)">
                                                    <div @click="toggle(e.id)" class="cursor-pointer py-2.5 px-4 hover:bg-indigo-50 flex justify-between items-center">
                                                        <span x-text="e.name" class="text-sm font-medium text-gray-700"></span>
                                                        <div x-show="selected.includes(e.id)" class="h-5 w-5 bg-indigo-500 rounded-full flex items-center justify-center text-white text-xs">✓</div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="mb-6">
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kuota</label>
                                            <input type="number" name="quota" value="1" min="1" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                        </div>

                                        <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: LIST JADWAL --}}
                <div class="lg:col-span-8 space-y-6">
                    <div class="flex justify-between items-end">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Daftar Jadwal Aktif</h3>
                            <p class="text-sm text-gray-500">Menampilkan semua slot yang tersedia untuk booking.</p>
                        </div>
                        <form action="{{ route('admin.reservation.slots.destroyAll') }}" method="POST" onsubmit="return confirm('Yakin hapus SEMUA?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-500 hover:text-red-700 font-semibold bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition-colors border border-red-100">
                                Reset Semua
                            </button>
                        </form>
                    </div>

                    {{-- CARD GRID LIST (RESPONSIVE) --}}
                    @forelse($slots->groupBy('day_of_week') as $day => $daySlots)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            {{-- Header Hari --}}
                            <div class="px-6 py-3 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                                <span class="font-bold text-gray-800 flex items-center gap-2">
                                    <span class="w-2 h-6 bg-indigo-500 rounded-full"></span>
                                    {{ $day }}
                                </span>
                                <span class="text-xs font-semibold text-gray-500 bg-white px-2 py-1 rounded-md border border-gray-200">{{ $daySlots->count() }} Slot</span>
                            </div>
                            
                            <div class="divide-y divide-gray-100">
                                @foreach($daySlots as $slot)
                                    <div class="p-4 hover:bg-gray-50 transition-colors duration-150 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        {{-- Info Kiri --}}
                                        <div class="flex items-center gap-4">
                                            {{-- Jam --}}
                                            <div class="flex-shrink-0 w-16 text-center">
                                                <span class="block text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}</span>
                                                <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-wide">WIB</span>
                                            </div>
                                            
                                            {{-- Detail --}}
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700">
                                                        {{ $slot->store->store_name }}
                                                    </span>
                                                    <span class="flex items-center text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                        Kuota: {{ $slot->quota }}
                                                    </span>
                                                </div>
                                                <div class="flex flex-wrap gap-1.5 mt-2">
                                                    @forelse($slot->employees as $emp)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-white border border-gray-200 text-gray-700 shadow-sm">
                                                            <div class="w-1.5 h-1.5 rounded-full bg-green-400 mr-1.5"></div>
                                                            {{ $emp->employee_name }}
                                                        </span>
                                                    @empty
                                                        <span class="text-xs text-red-400 italic">Tidak ada karyawan</span>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex items-center gap-2 self-end sm:self-center">
                                            <button @click="openEdit(
                                                {{ $slot->id_slot }}, 
                                                '{{ $slot->day_of_week }}', 
                                                '{{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}', 
                                                [{{ $slot->employees->pluck('id_employee')->implode(',') }}], 
                                                {{ $slot->id_store }},
                                                {{ $slot->quota }}
                                            )" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>
                                            <form action="{{ route('admin.reservation.slots.destroy', $slot->id_slot) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus?')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 mb-4">
                                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Belum Ada Jadwal</h3>
                            <p class="text-gray-500 mt-2 max-w-sm mx-auto">Gunakan panel di sebelah kiri untuk membuat jadwal massal atau satuan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- MODAL EDIT (REDESIGNED) --}}
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" @click="showEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white rounded-2xl text-left shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full overflow-hidden">
                    <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-bold text-white" id="modal-title">Edit Jadwal</h3>
                        <button @click="showEditModal = false" class="text-indigo-200 hover:text-white transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <div class="bg-white px-6 py-6">
                        <p class="text-sm text-gray-500 mb-6">Anda sedang mengubah jadwal untuk hari <span class="font-bold text-indigo-600 px-2 py-0.5 bg-indigo-50 rounded" x-text="editSlotDay"></span>.</p>

                        <form :action="editUrl" method="POST" class="space-y-5">
                            @csrf @method('PUT')

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Jam</label>
                                <input type="time" name="slot_time" x-model="editSlotTimeInput" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>

                            <div class="relative">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Karyawan</label>
                                <template x-for="id in currentEmployees"><input type="hidden" name="employee_ids[]" :value="id"></template>
                                <button type="button" @click="modalDropdownOpen = !modalDropdownOpen" @click.outside="modalDropdownOpen = false" class="w-full bg-white border border-gray-200 rounded-xl shadow-sm px-4 py-3 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 flex justify-between items-center group">
                                    <span x-text="currentEmployees.length ? currentEmployees.length + ' Dipilih' : 'Pilih...'" :class="{'text-gray-400': !currentEmployees.length, 'text-gray-800 font-medium': currentEmployees.length}"></span>
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                                <div x-show="modalDropdownOpen" style="display:none" class="absolute z-20 mt-1 w-full bg-white shadow-xl max-h-60 rounded-xl py-2 ring-1 ring-black/5 overflow-auto border border-gray-100">
                                    <template x-for="e in allEmployees.filter(emp => emp.store_id == editSlotStoreId)">
                                        <div @click="toggleModalEmployee(e.id)" class="cursor-pointer py-2.5 px-4 hover:bg-indigo-50 flex justify-between items-center transition-colors">
                                            <span x-text="e.name" class="text-sm font-medium text-gray-700"></span>
                                            <div x-show="currentEmployees.includes(e.id)" class="h-5 w-5 bg-indigo-500 rounded-full flex items-center justify-center text-white text-xs">✓</div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kuota</label>
                                <input type="number" name="quota" x-model="editSlotQuota" min="1" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            </div>

                            <div class="pt-4 flex justify-end gap-3">
                                <button type="button" @click="showEditModal = false" class="px-5 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-indigo-600 rounded-xl shadow-md text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform active:scale-95 transition-all">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>