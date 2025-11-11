<div class="flex flex-col h-full">
    
    <div class="flex-shrink-0 px-4 py-4 flex items-center justify-between">
        
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-white" />
        </a>

        <button @click="isSidebarOpen = false" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

    </div>
    <nav class="flex-1 overflow-y-auto">

        @php
            // Definisikan kelas untuk link, agar mudah diganti
            $linkClasses = 'flex items-center w-full px-6 py-3 text-gray-300 transition-colors duration-200 hover:bg-gray-700 hover:text-white';
            $activeClasses = 'bg-indigo-600 text-white font-semibold';
            $iconClasses = 'w-5 h-5 mr-3'; // Ukuran dan margin ikon
        @endphp


        @if (Auth::user()->role == 'admin')
            
            <a href="{{ route('admin.dashboard') }}" 
               class="{{ $linkClasses }} {{ request()->routeIs('admin.dashboard') ? $activeClasses : '' }}">
                
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconClasses }}">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Home</span>
            </a>

            <a href="{{ route('pos.index') }}" class="{{ $linkClasses }} {{ request()->routeIs('pos.*') ? $activeClasses : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconClasses }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                </svg>
                <span>Kasir</span>
            </a>

            <a href="{{ route('admin.informasi.index') }}" 
               class="{{ $linkClasses }} {{ request()->routeIs('admin.informasi.*') ? $activeClasses : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconClasses }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Informasi</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="{{ $linkClasses }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconClasses }}">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                <span>Laporan</span>
            </a>

            <a href="{{ route('admin.expenses.index') }}" class="{{ $linkClasses }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconClasses }}">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span> Setting Pengeluaran</span>
            </a>

            <a href="#" class="{{ $linkClasses }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconClasses }}">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <span>Setting Reservasi</span>
            </a>

            {{-- AWAL BLOK PRESENSI BARU --}}
        @php
            // Cek apakah salah satu rute presensi (jadwal ATAU rekap) sedang aktif
            $isPresenceActive = request()->routeIs('admin.presence-schedules.*') || request()->routeIs('admin.presence-recap.*');
        @endphp

        <div x-data="{ open: {{ $isPresenceActive ? 'true' : 'false' }} }">
            
            {{-- Tombol Dropdown Utama --}}
            <button @click="open = !open" 
                    class="{{ $linkClasses }} justify-between {{ $isPresenceActive ? $activeClasses : '' }}">
                
                <span class="flex items-center">
                    {{-- Ikon 'Users' (Orang) untuk Presensi --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor" class="{{ $iconClasses }}">
                        <path d="M840-120v-640H120v320H40v-320q0-33 23.5-56.5T120-840h720q33 0 56.5 23.5T920-760v560q0 33-23.5 56.5T840-120ZM360-400q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM40-80v-112q0-34 17.5-62.5T104-298q62-31 126-46.5T360-360q66 0 130 15.5T616-298q29 15 46.5 43.5T680-192v112H40Zm80-80h480v-32q0-11-5.5-20T580-226q-54-27-109-40.5T360-280q-56 0-111 13.5T140-226q-9 5-14.5 14t-5.5 20v32Zm240-400Zm0 400Z"/>
                    </svg>
                    <span>Presensi</span>
                </span>

                {{-- Panah Dropdown (akan berputar) --}}
                <svg class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            {{-- Konten Sub-menu (Sudah diperbarui) --}}
            <div x-show="open" x-collapse class="bg-gray-900/50">   
                
                @php
                    // Kelas dasar untuk sub-link (abu-abu)
                    $subLinkClasses = 'flex items-center w-full pl-12 px-6 py-2 text-gray-400 transition-colors duration-200 hover:bg-gray-700 hover:text-white';
                    // Kelas untuk sub-link yang aktif (putih)
                    $subActiveClasses = 'text-white font-semibold'; 
                @endphp
                
                {{-- Link 1: Jadwal Presensi --}}
                <a href="{{ route('admin.presence-schedules.index') }}"
                   class="{{ $subLinkClasses }} {{ request()->routeIs('admin.presence-schedules.*') ? $subActiveClasses : '' }}">
                    <span>Jadwal Presensi</span> {{-- Titik dihapus --}}
                </a>

                {{-- Link 2: Rekap Presensi --}}
                <a href="{{ route('admin.presence-recap.index') }}"
                   class="{{ $subLinkClasses }} {{ request()->routeIs('admin.presence-recap.*') ? $subActiveClasses : '' }}">
                    <span>Rekap Presensi</span>
                </a>
                
            </div>
        </div>
        {{-- AKHIR BLOK PRESENSI BARU --}}

        @else

            <a href="{{ route('kasir.dashboard') }}" 
               class="{{ $linkClasses }} {{ request()->routeIs('kasir.dashboard') ? $activeClasses : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconClasses }}">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Home</span>
            </a>

            <a href="{{ route('pos.index') }}" class="{{ $linkClasses }} {{ request()->routeIs('pos.*') ? $activeClasses : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconClasses }}">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.75A.75.75 0 013 4.5h.75m0 0v.75A.75.75 0 013 6h-.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zM10.5 14.25h3m-3-3h3m-3-3h3M3.75 6.75h16.5M3.75 9.75h16.5" />
                </svg>
                <span>Kasir</span>
            </a>

            <a href="{{ route('kasir.expenses.select-employee') }}"
               class="{{ $linkClasses }} {{ request()->routeIs('kasir.expenses.*') ? $activeClasses : '' }}">
                 <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor" class="{{ $iconClasses }}">
                    <path d="M480-536.16q41.92 0 70.96-29.03Q580-594.23 580-636.15q0-41.93-29.04-70.96-29.04-29.04-70.96-29.04-41.92 0-70.96 29.04Q380-678.08 380-636.15q0 41.92 29.04 70.96 29.04 29.03 70.96 29.03ZM212.69-412.31q-29.92 0-51.11-21.19-21.19-21.19-21.19-51.12v-303.07q0-29.92 21.19-51.12Q182.77-860 212.69-860h535q29.92 0 51.12 21.19Q820-817.61 820-787.69v303.07q0 29.93-21.19 51.12-21.2 21.19-51.12 21.19h-535ZM480.38-100l145.77-145.77-41.77-41.77-74 74v-143.38H450v143.38l-74-74-41.77 41.77L480.38-100Zm-280-444.61v59.99q0 5 3.66 8.66 3.65 3.65 8.65 3.65h60q0-29.92-21.19-51.11-21.19-21.19-51.12-21.19Zm487.31 72.3h60q5 0 8.66-3.65 3.65-3.66 3.65-8.66v-59.99q-29.92 0-51.11 21.19-21.2 21.19-21.2 51.11ZM760-727.69v-60q0-5-3.65-8.66-3.66-3.65-8.66-3.65h-60q0 29.92 21.2 51.11 21.19 21.2 51.11 21.2ZM272.69-800h-60q-5 0-8.65 3.65-3.66 3.66-3.66 8.66v60q29.93 0 51.12-21.2 21.19-21.19 21.19-51.11Z"/>
                 </svg>
                 <span>Input Pengeluaran</span>
            </a>

            {{-- TAMBAHKAN INI --}}
            <a href="{{ route('presence.index') }}" 
               class="{{ $linkClasses }} {{ request()->routeIs('presence.*') ? $activeClasses : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="{{ $iconClasses }}">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Halaman Presensi</span>
            </a>
            
        @endif
        
    </nav>

    <div class="flex-shrink-0 p-4 border-t border-gray-700">
        <x-dropdown align="top" width="48">
            <x-slot name="trigger">
                <button class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 rounded-md focus:outline-none transition duration-150 ease-in-out">
                    <div>{{ Auth::user()->name }}</div>
                    <div class="ms-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
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
            </x-slot>
        </x-dropdown>
    </div>
</div>