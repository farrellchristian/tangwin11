<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-base font-semibold text-gray-900 tracking-tight">Pengumuman</h2>
            <p class="text-sm text-gray-400 mt-0.5">Kelola pengumuman yang tampil di halaman reservasi pelanggan.</p>
        </div>
    </x-slot>

    {{-- Google Fonts for Preview Modal --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Italiana&family=Manrope:wght@200;300;400;500;600&display=swap" rel="stylesheet">

    {{-- Cropper.js CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

    <style>
        [x-cloak] { display: none !important; }

        /* Font styles for preview modal */
        .preview-modal-container { font-family: 'Manrope', sans-serif; }
        .preview-modal-container .font-display { font-family: 'Italiana', serif; }

        /* Toggle Switch */
        .ts-wrap { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
        .ts-input { position: absolute; opacity: 0; width: 0; height: 0; }
        .ts-track {
            position: absolute; inset: 0;
            display: block;
            background: #E5E7EB;
            border-radius: 999px;
            cursor: pointer;
            transition: background .18s ease;
        }
        .ts-input:checked ~ .ts-track { background: #0f172a; }
        .ts-track::after {
            content: '';
            position: absolute;
            top: 3px; left: 3px;
            width: 18px; height: 18px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,.18);
            transition: transform .18s ease;
        }
        .ts-input:checked ~ .ts-track::after { transform: translateX(20px); }

        /* Cropper.js Theme Overrides */
        .cropper-modal { background: #0a0a0a; opacity: .55; }
        .cropper-view-box { outline: 2px solid rgba(198,168,124,.8); }
        .cropper-line { background-color: rgba(198,168,124,.5); }
        .cropper-point {
            background-color: #C6A87C;
            width: 10px !important; height: 10px !important;
            opacity: 1;
            border-radius: 50%;
            box-shadow: 0 0 0 2px rgba(0,0,0,.4);
        }
        .cropper-dashed { border-color: rgba(255,255,255,.2); }
        .cropper-center::before, .cropper-center::after { background-color: rgba(255,255,255,.4); }

        /* Ratio Buttons */
        .ratio-btn {
            padding: 5px 14px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: .02em;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: white;
            color: #6b7280;
            cursor: pointer;
            transition: all .15s ease;
            white-space: nowrap;
        }
        .ratio-btn:hover { border-color: #9ca3af; color: #374151; }
        .ratio-btn.active {
            background: #0f172a;
            color: white;
            border-color: #0f172a;
            box-shadow: 0 1px 3px rgba(15,23,42,.2);
        }
    </style>

    <div class="py-6" x-data="announcementManager()">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-2"
                     x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.announcement.update') }}" method="POST" id="announcement-form">
                @csrf

                <div class="relative bg-white border border-gray-200 rounded-xl overflow-hidden">

                    {{-- Header Card --}}
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Status Pengumuman</h3>
                                <p class="text-xs text-gray-400 mt-0.5">Aktifkan untuk menampilkan pengumuman di halaman reservasi.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-medium" :class="isActive ? 'text-gray-900' : 'text-gray-400'" x-text="isActive ? 'Aktif' : 'Nonaktif'"></span>
                                <input type="hidden" name="is_active" :value="isActive ? '1' : '0'">
                                <label class="ts-wrap">
                                    <input type="checkbox" class="ts-input" x-model="isActive">
                                    <span class="ts-track"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 space-y-5">

                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Judul Pengumuman <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title"
                                value="{{ old('title', $announcement->title) }}"
                                x-model="title"
                                placeholder="Contoh: Promo Spesial Akhir Bulan!"
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-shadow"
                                required>
                            @error('title')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1.5">Isi Pengumuman</label>
                            <textarea name="content" id="content" rows="4"
                                x-model="content"
                                placeholder="Tulis detail pengumuman di sini..."
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-shadow resize-none">{{ old('content', $announcement->content) }}</textarea>
                        </div>

                        {{-- Image Upload --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Gambar / Brosur <span class="text-xs font-normal text-gray-400">(opsional)</span></label>
                            <div class="relative">
                                {{-- Hidden input for base64 data --}}
                                <input type="hidden" name="image_base64" x-model="imageBase64">

                                {{-- Preview Area --}}
                                <div x-show="preview" class="relative group rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                    <img :src="preview" class="w-full max-h-[300px] object-contain" alt="Preview">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                        <button type="button" @click="$refs.fileInput.click()"
                                            class="px-3 py-1.5 bg-white rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-100 transition-colors shadow-sm">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                Ganti
                                            </span>
                                        </button>
                                        <button type="button" @click="recropImage()"
                                            class="px-3 py-1.5 bg-gray-900 rounded-lg text-xs font-medium text-white hover:bg-gray-700 transition-colors shadow-sm">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Crop Ulang
                                            </span>
                                        </button>
                                        <button type="button" @click="removeImage()"
                                            class="px-3 py-1.5 bg-red-500 rounded-lg text-xs font-medium text-white hover:bg-red-600 transition-colors shadow-sm">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                {{-- Upload Button --}}
                                <button type="button" x-show="!preview" @click="$refs.fileInput.click()"
                                    class="w-full py-8 border-2 border-dashed border-gray-200 rounded-lg hover:border-gray-400 transition-colors flex flex-col items-center gap-2 group">
                                    <svg class="w-8 h-8 text-gray-300 group-hover:text-gray-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M6.75 7.5l.75-.75a2.25 2.25 0 013.182 0L12 8.25M3 3h18a2 2 0 012 2v14a2 2 0 01-2 2H3a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                                    </svg>
                                    <span class="text-xs text-gray-400 group-hover:text-gray-600 transition-colors">Klik untuk upload gambar</span>
                                    <span class="text-[10px] text-gray-300">JPG, PNG, WEBP (Maks. 2MB) &mdash; Akan di-crop setelah upload</span>
                                </button>

                                <input type="file" x-ref="fileInput" class="hidden" accept="image/jpeg,image/png,image/webp" @change="handleFileSelect($event)">
                            </div>
                        </div>
                    </div>

                    {{-- Save Bar --}}
                    <div class="sticky bottom-0 z-10 flex items-center justify-between px-6 py-3.5 bg-white border-t border-gray-100">
                        <p class="text-xs text-gray-400">Klik &ldquo;Simpan&rdquo; untuk menerapkan perubahan pengumuman.</p>
                        <button type="submit"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>

            {{-- Preview Section --}}
            <div class="mt-6">
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-900">Preview Modal</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Tampilan pengumuman yang akan dilihat pelanggan di halaman reservasi.</p>
                    </div>
                    <div class="p-6">
                        <div class="preview-modal-container relative mx-auto max-w-md bg-[#0e0e0e] rounded-2xl overflow-hidden shadow-2xl border border-white/10">
                            {{-- Close Button --}}
                            <div class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>

                            {{-- Image Preview --}}
                            <template x-if="preview">
                                <div class="w-full bg-black flex items-center justify-center overflow-hidden">
                                    <img :src="preview" class="max-w-full max-h-[300px] object-contain" alt="Preview">
                                </div>
                            </template>

                            {{-- Content --}}
                            <div class="p-6">
                                {{-- Gold accent line --}}
                                <div class="h-[2px] w-10 bg-[#C6A87C] mb-4"></div>

                                {{-- Title --}}
                                <h3 class="text-xl font-display text-white mb-3" x-text="title || 'Judul Pengumuman'"></h3>

                                {{-- Content text --}}
                                <p class="text-sm text-gray-400 leading-relaxed whitespace-pre-line" x-text="content || 'Isi pengumuman akan muncul di sini...'"></p>

                                {{-- Close action button --}}
                                <div class="mt-5 w-full py-3 bg-white/5 border border-white/10 rounded-lg text-xs uppercase tracking-widest text-white/70 text-center">
                                    Tutup
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════ --}}
        {{-- IMAGE CROPPER MODAL                                    --}}
        {{-- ═══════════════════════════════════════════════════════ --}}
        <div x-show="showCropper" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="cancelCrop()"></div>

            {{-- Modal Card --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden"
                 @click.stop
                 x-show="showCropper"
                 x-transition:enter="transition ease-out duration-200 delay-50"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                {{-- Header --}}
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Sesuaikan Gambar
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">Geser crop area atau pilih rasio, lalu klik Terapkan.</p>
                    </div>
                    <button type="button" @click="cancelCrop()"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Aspect Ratio Presets --}}
                <div class="px-5 py-3 bg-gray-50/80 border-b border-gray-100 flex items-center gap-2 flex-wrap">
                    <span class="text-[11px] text-gray-400 uppercase tracking-wider font-medium mr-1">Rasio</span>
                    <button type="button" @click="setAspectRatio(NaN, 'free')"
                        class="ratio-btn" :class="currentRatio === 'free' && 'active'">Bebas</button>
                    <button type="button" @click="setAspectRatio(16/9, '16:9')"
                        class="ratio-btn" :class="currentRatio === '16:9' && 'active'">16:9</button>
                    <button type="button" @click="setAspectRatio(4/3, '4:3')"
                        class="ratio-btn" :class="currentRatio === '4:3' && 'active'">4:3</button>
                    <button type="button" @click="setAspectRatio(1, '1:1')"
                        class="ratio-btn" :class="currentRatio === '1:1' && 'active'">1:1</button>
                    <button type="button" @click="setAspectRatio(3/4, '3:4')"
                        class="ratio-btn" :class="currentRatio === '3:4' && 'active'">3:4</button>
                    <button type="button" @click="setAspectRatio(9/16, '9:16')"
                        class="ratio-btn" :class="currentRatio === '9:16' && 'active'">9:16</button>
                </div>

                {{-- Cropper Container --}}
                <div class="bg-[#1a1a1a] relative" style="height: 420px;">
                    <img x-ref="cropperImage" style="max-width: 100%; display: block;">
                </div>

                {{-- Footer Actions --}}
                <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between bg-white">
                    <p class="text-[11px] text-gray-400">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Output JPEG &bull; Kualitas tinggi
                        </span>
                    </p>
                    <div class="flex gap-3">
                        <button type="button" @click="cancelCrop()"
                            class="px-4 py-2 text-sm text-gray-500 hover:text-gray-800 transition-colors rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="button" @click="applyCrop()"
                            class="flex items-center gap-2 px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cropper.js Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

    <script>
        function announcementManager() {
            return {
                isActive: {{ $announcement->is_active ? 'true' : 'false' }},
                title: @json(old('title', $announcement->title ?? '')),
                content: @json(old('content', $announcement->content ?? '')),
                preview: '{{ $announcement->image ? "data:image/jpeg;base64," . $announcement->image : "" }}',
                imageBase64: '',

                // Cropper state
                showCropper: false,
                cropper: null,
                currentRatio: 'free',
                originalImageSrc: '',

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    // Validate size (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file maksimal 2MB.');
                        event.target.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.originalImageSrc = e.target.result;
                        this.openCropper(e.target.result);
                    };
                    reader.readAsDataURL(file);
                    event.target.value = '';
                },

                openCropper(src) {
                    const img = this.$refs.cropperImage;
                    img.src = src;
                    this.showCropper = true;

                    this.$nextTick(() => {
                        // Destroy previous instance if exists
                        if (this.cropper) {
                            this.cropper.destroy();
                        }

                        this.cropper = new Cropper(img, {
                            viewMode: 1,
                            dragMode: 'move',
                            aspectRatio: NaN,
                            autoCropArea: 0.85,
                            restore: false,
                            guides: true,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                            background: true,
                            responsive: true,
                        });
                        this.currentRatio = 'free';
                    });
                },

                setAspectRatio(ratio, label) {
                    if (this.cropper) {
                        this.cropper.setAspectRatio(ratio);
                    }
                    this.currentRatio = label;
                },

                applyCrop() {
                    if (!this.cropper) return;

                    const canvas = this.cropper.getCroppedCanvas({
                        maxWidth: 1200,
                        maxHeight: 1200,
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high',
                    });

                    const croppedBase64 = canvas.toDataURL('image/jpeg', 0.92);
                    this.preview = croppedBase64;
                    this.imageBase64 = croppedBase64;
                    this.closeCropper();
                },

                cancelCrop() {
                    this.closeCropper();
                },

                closeCropper() {
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                    this.showCropper = false;
                },

                recropImage() {
                    // Use original image if available, otherwise use current preview
                    const src = this.originalImageSrc || this.preview;
                    if (src) {
                        if (!this.originalImageSrc) {
                            this.originalImageSrc = this.preview;
                        }
                        this.openCropper(src);
                    }
                },

                removeImage() {
                    this.preview = '';
                    this.imageBase64 = 'DELETE';
                    this.originalImageSrc = '';
                }
            };
        }
    </script>
</x-app-layout>
