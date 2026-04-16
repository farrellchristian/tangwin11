{{-- components/toast-notification.blade.php --}}
<div x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            @if (session('success'))
                this.showToast('{{ addslashes(session('success')) }}', 'success');
            @endif
            @if (session('error'))
                this.showToast('{{ addslashes(session('error')) }}', 'error');
            @endif
        },
        showToast(msg, type) {
            this.message = msg;
            this.type = type;
            this.show = true;
            setTimeout(() => { this.show = false; }, 4000);
        }
    }"
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed z-[100] top-4 inset-x-4 md:inset-x-auto md:top-6 md:right-6 md:w-96 drop-shadow-xl"
    style="display: none;"
>
    <!-- Card Container -->
    <div class="rounded-xl p-4 flex items-start gap-3 backdrop-blur-md border shadow-2xl relative overflow-hidden"
         :class="{
             'bg-white/90 border-green-200': type === 'success',
             'bg-white/90 border-red-200': type === 'error'
         }">
         
        <!-- Progress Bar Background (Animation effect) -->
        <div class="absolute bottom-0 left-0 h-1 w-full bg-slate-100">
            <div class="h-full loading-bar"
                 :class="{
                     'bg-green-500': type === 'success',
                     'bg-red-500': type === 'error'
                 }"></div>
        </div>

        <!-- Icon section -->
        <div class="flex-shrink-0 mt-0.5" :class="type === 'success' ? 'text-green-500' : 'text-red-500'">
            <!-- Success Icon -->
            <svg x-show="type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <!-- Error Icon -->
            <svg x-show="type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <!-- Text section -->
        <div class="flex-1">
            <h3 class="text-sm font-bold text-slate-800" x-text="type === 'success' ? 'Berhasil!' : 'Terjadi Kesalahan!'"></h3>
            <p class="text-xs font-semibold text-slate-500 mt-0.5 leading-relaxed" x-text="message"></p>
        </div>

        <!-- Close Button -->
        <button @click="show = false" class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors p-1 bg-slate-50 hover:bg-slate-100 rounded-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Animation CSS -->
    <style>
        .loading-bar {
            width: 100%;
            animation: shrink 4s linear forwards;
        }
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
</div>
