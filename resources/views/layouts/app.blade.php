<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <title>{{ config('DRTPE Puno', 'DRTPE Puno') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* FONDO INSTITUCIONAL RESTAURADO */
        body { 
            font-family: 'Inter', sans-serif; 
            background-image: url('');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        /* Barra de Desplazamiento Personalizada y Elegante */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.05); }
        ::-webkit-scrollbar-thumb { background: rgba(185, 28, 28, 0.6); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(185, 28, 28, 0.9); }

        /* Efecto Cristal (Glassmorphism) para el Header */
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* Animación de entrada fluida */
        @keyframes slideUpFade {
            0% { opacity: 0; transform: translateY(30px) scale(0.98); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-content {
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="antialiased text-slate-900 selection:bg-red-700 selection:text-white flex flex-col h-screen overflow-hidden" 
      x-data="{ pageLoaded: false }" 
      x-init="setTimeout(() => pageLoaded = true, 150)">

    <div x-show="!pageLoaded" 
         x-transition:leave="transition-opacity ease-in duration-500" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 z-[100] bg-slate-950 flex flex-col items-center justify-center">
        <div class="relative w-20 h-20">
            <div class="absolute inset-0 border-4 border-slate-700 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-red-600 rounded-full border-t-transparent animate-spin"></div>
        </div>
        <p class="text-white mt-6 font-bold tracking-widest uppercase text-xs animate-pulse">Iniciando Panel Operativo...</p>
    </div>

    <div class="min-h-screen flex flex-col w-full relative z-10">
        
        <div class="absolute inset-0 bg-slate-900/10 pointer-events-none z-0"></div>

        @include('layouts.navigation')

        <div class="flex-1 sm:pl-72 flex flex-col h-screen transition-all duration-300 relative z-10">
            
            @if (isset($header))
                <header class="glass-header shadow-[0_4px_30px_rgba(0,0,0,0.05)] sticky top-0 z-30 transition-all duration-500"
                        :class="pageLoaded ? 'translate-y-0 opacity-100' : '-translate-y-10 opacity-0'">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scrollbar-thin">
                <div x-show="pageLoaded" style="display: none;" class="animate-content">
                    {{ $slot }}
                </div>
                
                <footer class="mt-12 py-6 text-center">
                    <p class="text-xs font-bold tracking-widest text-slate-800/60 uppercase bg-white/40 backdrop-blur-sm inline-block px-4 py-2 rounded-full shadow-sm border border-white/50">
                        &copy; {{ date('Y') }} Dirección Regional de Trabajo Puno
                    </p>
                </footer>
            </main>
            
        </div>
    </div>
</body>
</html>


