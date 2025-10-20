<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="font-sans antialiased" x-data="{ isSidebarOpen: false }">
        
        <div class="relative min-h-screen bg-gray-100">

            <aside class="hidden lg:flex lg:flex-col w-64 bg-gray-800 text-white flex-shrink-0 fixed inset-y-0 left-0 z-10">
                @include('layouts.navigation')
            </aside>
            <div 
                x-show="isSidebarOpen" 
                class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden" 
                @click="isSidebarOpen = false"
                style="display: none;"
            ></div>
            <aside 
                class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-800 text-white transform transition-transform duration-300 ease-in-out lg:hidden"
                x-show="isSidebarOpen"
                x-transition:enter="transform translate-x-0"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform -translate-x-full"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                style="display: none;"
            >
                @include('layouts.navigation')
            </aside>
            <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
                
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                        
                        <button @click="isSidebarOpen = !isSidebarOpen" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': isSidebarOpen, 'inline-flex': !isSidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !isSidebarOpen, 'inline-flex': isSidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        
                        <div class="font-semibold text-xl text-gray-800 leading-tight">
                            @isset($header)
                                {{ $header }}
                            @endisset
                        </div>
                        
                        <div class="lg:hidden w-10"></div> 

                    </div>
                </header>
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        {{ $slot }}
                    </div>
                </main>
                </div>
            </div>
    </body>
</html>