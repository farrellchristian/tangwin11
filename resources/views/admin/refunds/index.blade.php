<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-base text-gray-800 leading-tight">
            {{ __('Daftar Permintaan Refund') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6" x-data="{ showModal: false, refundId: null, actionType: null, showDeleteModal: false }">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">

                {{-- ===== MOBILE: CARD LIST (< md) ===== --}}
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse($refunds as $refund)
                    @php
                    $statusMap = [
                        'pending'   => ['label' => 'Menunggu',  'class' => 'bg-yellow-100 text-yellow-700'],
                        'processed' => ['label' => 'Selesai',   'class' => 'bg-green-100 text-green-700'],
                        'rejected'  => ['label' => 'Ditolak',   'class' => 'bg-red-100 text-red-700'],
                    ];
                    $sts = $statusMap[$refund->status] ?? ['label' => ucfirst($refund->status), 'class' => 'bg-gray-100 text-gray-700'];
                    @endphp
                    <div class="p-4 space-y-3">

                        {{-- Top row: ID + Date + Status --}}
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ID RSVP #{{ $refund->id_reservation }}</span>
                                <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ \Carbon\Carbon::parse($refund->created_at)->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full flex-shrink-0 {{ $sts['class'] }}">
                                {{ $sts['label'] }}
                            </span>
                        </div>

                        {{-- Info Box --}}
                        <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] text-gray-400 font-bold uppercase">Pelanggan</span>
                                <span class="text-sm font-semibold text-gray-800">{{ $refund->reservation->customer_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] text-gray-400 font-bold uppercase">Nominal</span>
                                <span class="text-sm font-bold text-indigo-600">Rp {{ number_format($refund->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2">
                                <span class="text-[10px] text-gray-400 font-bold uppercase block mb-1">Rekening Tujuan</span>
                                <p class="text-xs text-gray-700"><span class="font-semibold">{{ $refund->bank_name }}</span> – {{ $refund->account_number }}</p>
                                <p class="text-xs text-gray-500">a/n {{ $refund->account_name }}</p>
                            </div>
                            @if($refund->cancel_reason)
                            <div class="border-t border-gray-200 pt-2">
                                <span class="text-[10px] text-gray-400 font-bold uppercase block mb-1">Alasan</span>
                                <p class="text-xs text-gray-600 italic">"{{ $refund->cancel_reason }}"</p>
                            </div>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            @if($refund->status == 'pending')
                            <button type="button"
                                @click="showModal = true; actionType = 'process'; refundId = {{ $refund->id }}"
                                class="flex-1 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                                ✓ Proses
                            </button>
                            <button type="button"
                                @click="showModal = true; actionType = 'reject'; refundId = {{ $refund->id }}"
                                class="flex-1 py-2.5 text-xs font-bold text-white bg-red-500 hover:bg-red-600 rounded-lg transition">
                                ✕ Tolak
                            </button>
                            @endif
                            <button type="button"
                                @click="showDeleteModal = true; refundId = {{ $refund->id }}"
                                class="px-3 py-2.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition border border-red-200" title="Hapus Permanen">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="py-14 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <p class="text-sm">Tidak ada pengajuan refund saat ini.</p>
                    </div>
                    @endforelse
                </div>

                {{-- ===== DESKTOP: TABLE (>= md) ===== --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Tgl / Reservasi</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Nominal</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Rekening Tujuan</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Alasan Refund</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($refunds as $refund)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($refund->created_at)->format('d M Y, H:i') }}
                                    <br><span class="text-xs text-gray-400">ID RSVP: #{{ $refund->id_reservation }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $refund->reservation->customer_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 font-bold">Rp {{ number_format($refund->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div><strong>Bank:</strong> {{ $refund->bank_name }}</div>
                                    <div><strong>No Rek:</strong> {{ $refund->account_number }}</div>
                                    <div><strong>A/N:</strong> {{ $refund->account_name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ $refund->cancel_reason }}">
                                    {{ $refund->cancel_reason ?: '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                    $statusMap = [
                                        'pending'   => ['label' => 'Menunggu',  'class' => 'bg-yellow-100 text-yellow-700'],
                                        'processed' => ['label' => 'Selesai',   'class' => 'bg-green-100 text-green-700'],
                                        'rejected'  => ['label' => 'Ditolak',   'class' => 'bg-red-100 text-red-700'],
                                    ];
                                    $sts = $statusMap[$refund->status] ?? ['label' => ucfirst($refund->status), 'class' => 'bg-gray-100 text-gray-700'];
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $sts['class'] }}">{{ $sts['label'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        @if($refund->status == 'pending')
                                        <button type="button"
                                            @click="showModal = true; actionType = 'process'; refundId = {{ $refund->id }}"
                                            class="px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">Proses</button>
                                        <button type="button"
                                            @click="showModal = true; actionType = 'reject'; refundId = {{ $refund->id }}"
                                            class="px-3 py-1.5 text-xs font-semibold text-white bg-red-500 hover:bg-red-600 rounded-lg transition">Tolak</button>
                                        @endif
                                        <button type="button"
                                            @click="showDeleteModal = true; refundId = {{ $refund->id }}"
                                            class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-14 text-center text-gray-400 text-sm">Tidak ada pengajuan refund saat ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($refunds->hasPages())
                <div class="px-4 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $refunds->links() }}
                </div>
                @endif

            </div>
        </div>

        {{-- Confirm Modal (Process/Reject) --}}
        <div x-show="showModal" class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
                <div x-show="showModal"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full mx-4">

                    <form :action="actionType === 'process' ? '{{ url('admin/refunds') }}/' + refundId + '/process' : '{{ url('admin/refunds') }}/' + refundId + '/reject'" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-5 pt-6 pb-4">
                            <h3 class="text-base font-bold text-gray-900" id="modal-title" x-text="actionType === 'process' ? 'Konfirmasi Proses Transfer' : 'Konfirmasi Penolakan Refund'"></h3>
                            <p class="text-sm text-gray-500 mt-2">
                                <template x-if="actionType === 'process'">
                                    <span>Anda akan menandai refund ini sebagai selesai. Wajib mengunggah bukti transfer berupa gambar untuk pembukuan dan dikirim ke email pelanggan.</span>
                                </template>
                                <template x-if="actionType === 'reject'">
                                    <span>Apakah Anda yakin ingin menolak pengajuan refund ini? Aksi ini tidak dapat dibatalkan.</span>
                                </template>
                            </p>
                            <template x-if="actionType === 'process'">
                                <div class="mt-4">
                                    <label for="proof_image" class="block text-sm font-medium text-gray-700">Unggah Bukti Transfer <span class="text-red-500">*</span></label>
                                    <input type="file" name="proof_image" id="proof_image" accept="image/png, image/jpeg, image/jpg"
                                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" required>
                                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                                </div>
                            </template>
                        </div>
                        <div class="bg-gray-50 px-5 py-3 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                            <button type="button" @click="showModal = false"
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button type="submit"
                                :class="actionType === 'process' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-red-600 hover:bg-red-700'"
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white rounded-lg transition"
                                x-text="actionType === 'process' ? 'Tandai Selesai' : 'Ya, Tolak Refund'">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div x-show="showDeleteModal" class="fixed z-30 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full mx-4">
                    <form :action="'{{ url('admin/refunds') }}/' + refundId" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="bg-white px-5 pt-6 pb-4 text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900">Konfirmasi Hapus</h3>
                            <p class="text-sm text-gray-500 mt-2">Apakah Anda yakin ingin menghapus data refund ini dari tampilan? Data akan dipindahkan ke folder sampah (soft delete) dan tetap tersimpan di database.</p>
                        </div>
                        <div class="bg-gray-50 px-5 py-3 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                            <button type="button" @click="showDeleteModal = false" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm">Ya, Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>