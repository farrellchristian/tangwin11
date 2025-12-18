<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tangwin Cut Studio') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* --- GLOBAL VARIABLES --- */
        :root {
            /* Palette Barber Modern */
            --navy-dark: #0f172a;  /* Biru Gelap (Main Background) */
            --navy-light: #1e293b; /* Biru Sedikit Terang */
            --red-accent: #dc2626; /* Merah Barber */
            --blue-accent: #2563eb;/* Biru Barber */
            --white: #ffffff;
            --gray-bg: #f1f5f9;
            
            --font-head: 'Oswald', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
        }

        /* --- RESET & BASE STYLES --- */
        html {
            height: 100%; /* Pastikan html full height */
        }
        
        body {
            min-height: 100vh; /* Body minimal setinggi layar, tapi bisa scroll */
            margin: 0;
            padding: 0;
            font-family: var(--font-body);
            background-color: var(--gray-bg);
            color: #334155;
            -webkit-font-smoothing: antialiased;
            /* Perbaikan Scroll */
            overflow-x: hidden; 
            overflow-y: auto; 
        }

        /* Utility Classes sederhana */
        .font-oswald { font-family: var(--font-head); }
        .text-navy { color: var(--navy-dark); }
        .text-red { color: var(--red-accent); }
        
        /* Custom Scrollbar (Biar rapi di desktop) */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="antialiased">
    {{ $slot }}
</body>
</html>