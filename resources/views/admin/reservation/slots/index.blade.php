<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Jadwal Reservasi') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        // Variabel untuk Modal Edit
        showEditModal: false,
        editUrl: '',
        editSlotDay: '',
        editSlotTimeInput: '', // Variabel untuk menampung Jam yang diedit
        currentEmployees: [], 
        
        // Variabel Khusus Dropdown di dalam Modal
        modalDropdownOpen: false, 

        // Fungsi Buka Modal
        openEdit(id, day, time, employees) {
            this.editUrl = '{{ url('/admin/reservation/slots') }}/' + id;
            this.editSlotDay = day;
            this.editSlotTimeInput = time; // Masukkan jam lama ke input
            this.currentEmployees = employees; 
            this.showEditModal = true;
            this.modalDropdownOpen = false; 
        },

        // Fungsi Toggle Karyawan di dalam Modal
        toggleModalEmployee(id) {
            if (this.currentEmployees.includes(id)) {
                this.currentEmployees = this.currentEmployees.filter(x => x !== id);
            } else {
                this.currentEmployees.push(id);
            }
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Berhasil!</strong> <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul>@foreach ($errors->all() as $error) <li>- {{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <div class="p-6 bg-white shadow sm:rounded-lg">
                <header class="mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Tambah Jadwal Baru</h2>
                    <p class="text-sm text-gray-600">Generate slot waktu masal & tentukan karyawan.</p>
                </header>

                <form method="POST" action="{{ route('admin.reservation.slots.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div x-data='{ open: false, selected: [], days: ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"], toggle(d){ this.selected.includes(d)?this.selected=this.selected.filter(x=>x!==d):this.selected.push(d)} }' class="relative md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Hari</label>
                            <template x-for="d in selected"><input type="hidden" name="day_of_week[]" :value="d"></template>
                            <button type="button" @click="open=!open" @click.outside="open=false" class="w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-left cursor-default focus:ring-1 focus:ring-indigo-500 sm:text-sm relative">
                                <span x-text="selected.length ? selected.join(', ') : 'Pilih hari...'" :class="{'text-gray-500': !selected.length}"></span>
                            </button>
                            <ul x-show="open" style="display:none" class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 ring-1 ring-black ring-opacity-5 overflow-auto sm:text-sm">
                                <template x-for="d in days"><li @click="toggle(d)" class="cursor-pointer py-2 px-3 hover:bg-indigo-50" :class="{'bg-indigo-50 font-semibold text-indigo-600': selected.includes(d)}" x-text="d"></li></template>
                            </ul>
                        </div>

                        <div x-data='{ open: false, selected: [], employees: @json($employees->map(fn($e)=>["id"=>$e->id_employee,"name"=>$e->employee_name])), toggle(id){this.selected.includes(id)?this.selected=this.selected.filter(x=>x!==id):this.selected.push(id)} }' class="relative md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Karyawan</label>
                            <template x-for="id in selected"><input type="hidden" name="employee_ids[]" :value="id"></template>
                            <button type="button" @click="open=!open" @click.outside="open=false" class="w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-left cursor-default focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                                <span x-text="selected.length ? selected.length + ' Karyawan dipilih' : 'Pilih karyawan...'" :class="{'text-gray-500': !selected.length}"></span>
                            </button>
                            <ul x-show="open" style="display:none" class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 ring-1 ring-black ring-opacity-5 overflow-auto sm:text-sm">
                                <template x-for="e in employees"><li @click="toggle(e.id)" class="cursor-pointer py-2 px-3 hover:bg-indigo-50 flex justify-between" :class="{'bg-indigo-50 font-semibold text-indigo-600': selected.includes(e.id)}"><span x-text="e.name"></span><span x-show="selected.includes(e.id)">✓</span></li></template>
                            </ul>
                        </div>

                        <div><label class="block text-sm font-medium text-gray-700">Mulai</label><input type="time" name="start_time" class="w-full border-gray-300 rounded-md shadow-sm mt-1" required></div>
                        <div><label class="block text-sm font-medium text-gray-700">Selesai</label><input type="time" name="end_time" class="w-full border-gray-300 rounded-md shadow-sm mt-1" required></div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700">Interval</label><div class="mt-2 flex gap-4"><label><input type="radio" name="interval" value="30"> 30 Menit</label><label><input type="radio" name="interval" value="60" checked> 60 Menit</label></div></div>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">Generate Jadwal</button>
                </form>
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Jadwal Tersedia</h3>
                    <form action="{{ route('admin.reservation.slots.destroyAll') }}" method="POST" onsubmit="return confirm('Hapus SEMUA jadwal?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-600 hover:text-red-800 font-bold underline">Hapus Semua</button>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan Bertugas</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($slots as $slot)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900">{{ $slot->day_of_week }}</span>
                                            <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if($slot->employees->count() > 0)
                                                @foreach($slot->employees as $emp)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        <svg class="mr-1.5 h-2 w-2 text-indigo-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                        {{ $emp->employee_name }}
                                                    </span>
                                                @endforeach
                                                <span class="text-xs text-gray-400 ml-1">(Kuota: {{ $slot->quota }})</span>
                                            @else
                                                <span class="text-xs text-red-400 italic">Belum ada karyawan</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="openEdit({{ $slot->id_slot }}, '{{ $slot->day_of_week }}', '{{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}', [{{ $slot->employees->pluck('id_employee')->implode(',') }}])" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 p-2 rounded-full hover:bg-indigo-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </button>
                                            <form action="{{ route('admin.reservation.slots.destroy', $slot->id_slot) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus slot ini?')" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-full hover:bg-red-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada jadwal.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Jadwal</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Mengubah detail jadwal untuk hari <span class="font-bold" x-text="editSlotDay"></span>.</p>
                        </div>

                        <form :action="editUrl" method="POST" class="mt-4 relative space-y-4">
                            @csrf @method('PUT')

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Jadwal</label>
                                <input type="time" name="slot_time" x-model="editSlotTimeInput" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>

                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Karyawan</label>
                                <template x-for="id in currentEmployees"><input type="hidden" name="employee_ids[]" :value="id"></template>

                                <button type="button" @click="modalDropdownOpen = !modalDropdownOpen" @click.outside="modalDropdownOpen = false" class="w-full bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-left cursor-default focus:ring-1 focus:ring-indigo-500 sm:text-sm flex justify-between items-center">
                                    <span x-text="currentEmployees.length ? currentEmployees.length + ' Karyawan dipilih' : 'Pilih karyawan...'" :class="{'text-gray-500': !currentEmployees.length}"></span>
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>

                                <ul x-show="modalDropdownOpen" style="display:none" class="absolute z-50 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 ring-1 ring-black ring-opacity-5 overflow-auto sm:text-sm">
                                    @foreach($employees as $e)
                                        <li @click="toggleModalEmployee({{ $e->id_employee }})" class="cursor-pointer py-2 px-3 hover:bg-indigo-50 flex justify-between items-center" :class="{'bg-indigo-50 font-semibold text-indigo-600': currentEmployees.includes({{ $e->id_employee }})}">
                                            <span>{{ $e->employee_name }}</span>
                                            <span x-show="currentEmployees.includes({{ $e->id_employee }})" class="text-indigo-600"><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg></span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mt-5 sm:flex sm:flex-row-reverse gap-2">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:w-auto sm:text-sm">Simpan Perubahan</button>
                                <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>