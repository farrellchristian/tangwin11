<div class="flex flex-col h-full transition-all duration-300">

    {{-- HEADER SIDEBAR (Logo & Toggle) --}}
    <div class="flex-shrink-0 px-4 py-4 flex items-center transition-all duration-300"
        :class="isSidebarCollapsed ? 'justify-center flex-col gap-4' : 'justify-between'">

        <a href="{{ route('dashboard') }}" x-show="!isSidebarCollapsed" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
            <img src="{{ asset('images/logo_tangwin_white.png') }}" alt="Tangwin Logo" class="block h-24 w-auto transition-transform duration-300 hover:scale-105">
        </a>

        {{-- Logo versi mini (opsional, gunakan icon yang sama tapi kecil jika collapsed) --}}
        <a href="{{ route('dashboard') }}" x-show="isSidebarCollapsed" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
            <img src="{{ asset('images/logo_tangwin_white.png') }}" alt="Tangwin Logo" class="block h-8 w-auto">
        </a>

        {{-- Tombol Close untuk Mobile --}}
        <button @click="isSidebarOpen = false" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Tombol Toggle Collapse untuk Desktop --}}
        <button @click="toggleSidebar()" class="hidden lg:flex p-1.5 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none transition-colors duration-200 bg-gray-900/50 border border-gray-700" :title="isSidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
            {{-- Icon Panah Kiri (Collapse) --}}
            <svg x-show="!isSidebarCollapsed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
            </svg>
            {{-- Icon Panah Kanan (Expand) --}}
            <svg x-show="isSidebarCollapsed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
            </svg>
        </button>

    </div>

    <nav class="flex-1 overflow-y-auto scrollbar-hide py-2">

        @php
        // KONFIGURASI STYLE (Kita buat flex class nya dinamis di dalam loop nanti)
        // Base classes tanpa padding horizontal fix, kita atur via Alpine/Tailwind utility
        $baseLinkClasses = 'flex items-center w-full py-3 transition-colors duration-200 hover:bg-gray-700 hover:text-white border-l-4 border-transparent';
        $activeClasses = 'border-indigo-500 text-white font-semibold bg-gray-800';
        $inactiveClasses = 'text-gray-300';
        $iconSize = 'w-6 h-6'; // Sedikit diperbesar agar jelas saat icon-only

        // Submenu
        $subLinkClasses = 'flex items-center w-full py-2 text-gray-400 transition-colors duration-200 hover:bg-gray-700 hover:text-white border-l-4 border-transparent';
        $subActiveClasses = 'border-indigo-500 text-white font-semibold';

        $headingClasses = 'px-6 pt-4 pb-2 text-xs font-semibold uppercase text-gray-500 tracking-wider transition-opacity duration-200';
        @endphp


        @if (Auth::user()->role == 'admin')

        {{-- ================= BAGIAN ADMIN ================= --}}

        <div class="{{ $headingClasses }}" x-show="!isSidebarCollapsed">Navigasi Utama</div>
        <!-- Separator saat collapsed -->
        <div class="border-t border-gray-700/50 mx-4 my-2" x-show="isSidebarCollapsed" style="display: none;"></div>

        <a href="{{ route('admin.dashboard') }}"
            class="{{ $baseLinkClasses }} {{ request()->routeIs('admin.dashboard') ? $activeClasses : $inactiveClasses }}"
            :class="isSidebarCollapsed ? 'justify-center px-0' : 'pl-5 pr-6'"
            :title="isSidebarCollapsed ? 'Home' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span x-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity duration-200">Home</span>
        </a>

        <a href="{{ route('pos.index') }}"
            class="{{ $baseLinkClasses }} {{ request()->routeIs('pos.*') && !request()->routeIs('pos.history') ? $activeClasses : $inactiveClasses }}"
            :class="isSidebarCollapsed ? 'justify-center px-0' : 'pl-5 pr-6'"
            :title="isSidebarCollapsed ? 'Kasir' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
            </svg>
            <span x-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity duration-200">Kasir</span>
        </a>

        <div class="{{ $headingClasses }}" x-show="!isSidebarCollapsed">Analitik</div>
        <div class="border-t border-gray-700/50 mx-4 my-2" x-show="isSidebarCollapsed" style="display: none;"></div>

        <a href="{{ route('admin.reports.index') }}"
            class="{{ $baseLinkClasses }} {{ request()->routeIs('admin.reports.index') ? $activeClasses : $inactiveClasses }}"
            :class="isSidebarCollapsed ? 'justify-center px-0' : 'pl-5 pr-6'"
            :title="isSidebarCollapsed ? 'Laporan' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <span x-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity duration-200">Laporan</span>
        </a>

        <div class="{{ $headingClasses }}" x-show="!isSidebarCollapsed">Manajemen</div>
        <div class="border-t border-gray-700/50 mx-4 my-2" x-show="isSidebarCollapsed" style="display: none;"></div>

        {{-- AWAL BLOK MANAJEMEN DATA --}}
        @php
        $isDataManagementActive = request()->routeIs('admin.users.*') ||
        request()->routeIs('admin.employees.*') ||
        request()->routeIs('admin.services.*') ||
        request()->routeIs('admin.products.*') ||
        request()->routeIs('admin.foods.*') ||
        request()->routeIs('admin.payment-methods.*');
        @endphp

        <div x-data="{ open: {{ $isDataManagementActive ? 'true' : 'false' }} }">

            <button @click="isSidebarCollapsed ? toggleSidebar() : open = !open"
                class="{{ $baseLinkClasses }} {{ $isDataManagementActive ? $activeClasses : $inactiveClasses }}"
                :class="isSidebarCollapsed ? 'justify-center px-0' : 'justify-between pl-5 pr-6'"
                :title="isSidebarCollapsed ? 'Manajemen Data' : ''">

                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    <span x-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity duration-200">Manajemen Data</span>
                </span>

                <svg x-show="!isSidebarCollapsed" class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            {{-- Submenu hanya muncul jika sidebar TIDAK collapsed DAN open = true --}}
            <div x-show="open && !isSidebarCollapsed" x-collapse class="bg-gray-900/50">

                <a href="{{ route('admin.users.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.users.*') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Akun Kasir & Toko</span>
                </a>
                <a href="{{ route('admin.employees.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.employees.*') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Karyawan</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.services.*') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Layanan</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.products.*') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Produk</span>
                </a>
                <a href="{{ route('admin.foods.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.foods.*') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Makanan & Minuman</span>
                </a>
                <a href="{{ route('admin.payment-methods.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.payment-methods.*') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Metode Pembayaran</span>
                </a>

            </div>
        </div>
        {{-- AKHIR BLOK MANAJEMEN DATA --}}

        {{-- AWAL BLOK RESERVASI --}}
        @php
        $isReservationActive = request()->routeIs('admin.reservation.*') || request()->routeIs('admin.refunds.*');
        @endphp

        <div x-data="{ open: {{ $isReservationActive ? 'true' : 'false' }} }">

            <button @click="isSidebarCollapsed ? toggleSidebar() : open = !open"
                class="{{ $baseLinkClasses }} {{ $isReservationActive ? $activeClasses : $inactiveClasses }}"
                :class="isSidebarCollapsed ? 'justify-center px-0' : 'justify-between pl-5 pr-6'"
                :title="isSidebarCollapsed ? 'Reservasi' : ''">

                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span x-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity duration-200">Reservasi</span>
                </span>

                <svg x-show="!isSidebarCollapsed" class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div x-show="open && !isSidebarCollapsed" x-collapse class="bg-gray-900/50">
                <a href="{{ route('admin.reservation.slots.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.reservation.slots.*') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Kelola Jadwal</span>
                </a>
                <a href="{{ route('admin.reservation.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.reservation.index') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Lihat Reservasi</span>
                </a>
                <a href="{{ route('admin.refunds.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.refunds.*') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Permintaan Refund</span>
                </a>
            </div>
        </div>
        {{-- AKHIR BLOK RESERVASI --}}

        {{-- AWAL BLOK PRESENSI --}}
        @php
        $isPresenceActive = request()->routeIs('admin.presence-schedules.*') || request()->routeIs('admin.presence-recap.*');
        @endphp

        <div x-data="{ open: {{ $isPresenceActive ? 'true' : 'false' }} }">

            <button @click="isSidebarCollapsed ? toggleSidebar() : open = !open"
                class="{{ $baseLinkClasses }} {{ $isPresenceActive ? $activeClasses : $inactiveClasses }}"
                :class="isSidebarCollapsed ? 'justify-center px-0' : 'justify-between pl-5 pr-6'"
                :title="isSidebarCollapsed ? 'Presensi' : ''">

                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    <span x-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity duration-200">Presensi</span>
                </span>

                <svg x-show="!isSidebarCollapsed" class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div x-show="open && !isSidebarCollapsed" x-collapse class="bg-gray-900/50">
                <a href="{{ route('admin.presence-schedules.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.presence-schedules.*') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Jadwal Presensi</span>
                </a>
                <a href="{{ route('admin.presence-recap.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.presence-recap.*') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Rekap Presensi</span>
                </a>
            </div>
        </div>
        {{-- AKHIR BLOK PRESENSI --}}

        <div class="{{ $headingClasses }}" x-show="!isSidebarCollapsed">Sistem</div>
        <div class="border-t border-gray-700/50 mx-4 my-2" x-show="isSidebarCollapsed" style="display: none;"></div>

        {{-- AWAL BLOK PENGATURAN --}}
        @php
        $isSettingsActive = request()->routeIs('admin.expenses.index');
        @endphp

        <div x-data="{ open: {{ $isSettingsActive ? 'true' : 'false' }} }">
            <button @click="isSidebarCollapsed ? toggleSidebar() : open = !open"
                class="{{ $baseLinkClasses }} {{ $isSettingsActive ? $activeClasses : $inactiveClasses }}"
                :class="isSidebarCollapsed ? 'justify-center px-0' : 'justify-between pl-5 pr-6'"
                :title="isSidebarCollapsed ? 'Pengaturan' : ''">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>
                    <span x-show="!isSidebarCollapsed" class="whitespace-nowrap transition-opacity duration-200">Pengaturan</span>
                </span>
                <svg x-show="!isSidebarCollapsed" class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div x-show="open && !isSidebarCollapsed" x-collapse class="bg-gray-900/50">
                <a href="{{ route('admin.expenses.index') }}" class="{{ $subLinkClasses }} {{ request()->routeIs('admin.expenses.index') ? $subActiveClasses : '' }} pl-[52px] pr-6">
                    <span>Setting Pengeluaran</span>
                </a>
                <a href="#" class="{{ $subLinkClasses }} pl-[52px] pr-6">
                    <span>Setting Reservasi</span>
                </a>
            </div>
        </div>
        {{-- AKHIR BLOK PENGATURAN --}}

        @else

        {{-- ================= BAGIAN KASIR (Belum Full Collapsible Logic, tapi kita terapkan basic nya) ================= --}}
        {{-- Jika User Login sebagai Kasir, mereka biasanya pakai iPad/Tablet, jadi fitur collapse mungkin kurang relevan tapi tetap kita support --}}
        <div class="{{ $headingClasses }}" x-show="!isSidebarCollapsed">Kasir Menu</div>

        <a href="{{ route('kasir.dashboard') }}"
            class="{{ $baseLinkClasses }} {{ request()->routeIs('kasir.dashboard') ? $activeClasses : $inactiveClasses }}"
            :class="isSidebarCollapsed ? 'justify-center px-0' : 'pl-5 pr-6'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span x-show="!isSidebarCollapsed">Home</span>
        </a>

        <a href="{{ route('pos.index') }}"
            class="{{ $baseLinkClasses }} {{ request()->routeIs('pos.*') && !request()->routeIs('pos.history') ? $activeClasses : $inactiveClasses }}"
            :class="isSidebarCollapsed ? 'justify-center px-0' : 'pl-5 pr-6'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
            </svg>
            <span x-show="!isSidebarCollapsed">Kasir</span>
        </a>

        <a href="{{ route('pos.history') }}"
            class="{{ $baseLinkClasses }} {{ request()->routeIs('pos.history') ? $activeClasses : $inactiveClasses }}"
            :class="isSidebarCollapsed ? 'justify-center px-0' : 'pl-5 pr-6'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span x-show="!isSidebarCollapsed">Riwayat Transaksi</span>
        </a>

        <a href="{{ route('kasir.expenses.select-employee') }}"
            class="{{ $baseLinkClasses }} {{ request()->routeIs('kasir.expenses.*') ? $activeClasses : $inactiveClasses }}"
            :class="isSidebarCollapsed ? 'justify-center px-0' : 'pl-5 pr-6'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span x-show="!isSidebarCollapsed">Input Pengeluaran</span>
        </a>

        <a href="{{ route('presence.index') }}"
            class="{{ $baseLinkClasses }} {{ request()->routeIs('presence.*') ? $activeClasses : $inactiveClasses }}"
            :class="isSidebarCollapsed ? 'justify-center px-0' : 'pl-5 pr-6'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconSize }} transition-all duration-200" :class="isSidebarCollapsed ? 'mr-0' : 'mr-3'">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span x-show="!isSidebarCollapsed">Halaman Presensi</span>
        </a>

        @endif

    </nav>

    <div class="flex-shrink-0 p-4 border-t border-gray-700" :class="isSidebarCollapsed ? 'p-2' : 'p-4'">

        {{-- Custom Dropdown dengan Dynamic Positioning --}}
        <div x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false" class="relative">

            <button @click="open = ! open" class="w-full flex items-center text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 rounded-md focus:outline-none transition duration-150 ease-in-out"
                :class="isSidebarCollapsed ? 'justify-center px-0 py-2' : 'justify-between px-3 py-2'">

                <div class="flex items-center">
                    {{-- Avatar / Initial placeholder --}}
                    <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-xs" :class="isSidebarCollapsed ? '' : 'mr-2'">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>

                    <div x-show="!isSidebarCollapsed">{{ Auth::user()->name }}</div>
                </div>

                <div x-show="!isSidebarCollapsed" class="ms-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute z-50 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5"
                :class="isSidebarCollapsed ? 'left-full bottom-0 ml-2 w-48 origin-bottom-left' : 'right-0 bottom-full mb-2 w-48 origin-bottom-right'"
                style="display: none;"
                @click="open = false">

                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </div>
        </div>
    </div>
</div>