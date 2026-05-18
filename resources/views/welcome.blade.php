<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Actividades | Dirección de Trabajo y Promoción del Empleo - Puno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-image: url('/images/fondodash.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Funcionalidad de mostrar/ocultar fotos intacta */
        .foto-extra {
            display: none;
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .mostrar-todas .foto-extra {
            display: block;
            opacity: 1;
            transform: scale(1);
            animation: fadeInGrid 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        @keyframes fadeInGrid {
            0% { opacity: 0; transform: translateY(15px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Efecto de galería (Interactivo sin lupa) */
        .foto-galeria {
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .foto-item:hover .foto-galeria {
            transform: scale(1.05);
        }

        /* Video preview interactivo */
        .video-preview-container {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .video-preview-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(220, 38, 38, 0.9);
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            animation: pulse-red 2s infinite;
        }
        .video-preview-container:hover .play-button {
            background: #ef4444;
            transform: translate(-50%, -50%) scale(1.15);
            animation: none;
        }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        /* Lightbox - Modal */
        #lightbox.active { opacity: 1; visibility: visible; }
        #lightbox {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
    </style>
</head>
<body class="antialiased selection:bg-red-700 selection:text-white min-h-screen">

    <header class="bg-gradient-to-r from-slate-900 to-slate-800 text-white sticky top-0 z-40 border-b border-slate-700 shadow-xl backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center py-3 sm:h-20 gap-4 sm:gap-0">
               
                <div class="flex items-center gap-4 w-full sm:w-auto group cursor-default">
                    <div class="bg-white p-2 rounded-2xl shadow-lg shrink-0 transition-all duration-300 group-hover:scale-105 border border-slate-200">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-11 h-11 object-contain">
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-lg sm:text-xl font-extrabold tracking-tight truncate">Portal Oficial de Actividades</h1>
                        <p class="text-slate-400 text-xs sm:text-sm font-bold uppercase tracking-widest truncate">DTPE Puno - Perú</p>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="w-full sm:w-auto justify-center flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/10 transition-all duration-300 px-6 py-2.5 rounded-xl text-sm font-semibold hover:shadow-lg hover:-translate-y-0.5">
                    <i class="fa-solid fa-user-shield text-red-400"></i>
                    <span>Acceso Interno</span>
                </a>

            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto py-10 sm:py-16 px-4 sm:px-6 lg:px-8 relative z-10">
       
        <div class="mb-12 bg-white/95 backdrop-blur-md p-8 rounded-2xl shadow-lg border border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-2 h-full bg-red-600"></div>
            <div class="pl-4">
                <span class="inline-block text-red-600 text-xs font-black uppercase tracking-widest mb-2">Transparencia Regional</span>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight tracking-tight">Cronología de Acciones</h1>
                <p class="text-slate-600 mt-2 text-base font-medium">Registro documental e interactivo de las intervenciones de la Dirección.</p>
            </div>
        </div>

        @forelse($actividades as $actividad)
            @if($actividad->subEvents->count() > 0)
                <article class="mb-14 transition-all duration-300">
                   
                    <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <h2 class="text-2xl sm:text-3xl font-black text-red-600 drop-shadow-sm">
                            {{ $actividad->description }}
                        </h2>
                        <div class="flex flex-wrap items-center gap-2 shrink-0">
                            <span class="bg-slate-800 text-white text-[10px] sm:text-xs font-bold px-3 py-1.5 uppercase tracking-widest rounded-md shadow-sm">
                                {{ $actividad->category->name ?? 'General' }}
                            </span>
                            <span class="text-slate-600 font-bold text-[10px] sm:text-xs bg-white px-3 py-1.5 rounded-md border border-slate-200 shadow-sm flex items-center gap-1.5">
                                PP: {{ $actividad->category->pp_code ?? '000' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <div class="relative border-l-[3px] border-slate-300 ml-4 sm:ml-8 pl-8 sm:pl-12 space-y-12 py-2">
                            @foreach($actividad->subEvents as $reporte)
                                <div class="relative reporte-container group">
                                   
                                    <div class="absolute left-[calc(-2rem-11px)] sm:left-[calc(-3rem-11px)] top-8 w-5 h-5 bg-gradient-to-br from-slate-400 to-slate-500 transform rotate-45 border-2 border-white shadow-[0_3px_10px_rgba(0,0,0,0.2)] group-hover:from-red-500 group-hover:to-red-700 group-hover:shadow-[0_5px_15px_rgba(220,38,38,0.5)] group-hover:scale-110 transition-all duration-300 z-10"></div>
                                    
                                    <div class="absolute left-[calc(-2rem)] sm:left-[calc(-3rem)] top-[41px] w-8 sm:w-12 h-[2px] bg-gradient-to-r from-slate-300 to-transparent group-hover:from-red-400 transition-colors duration-300 z-0 hidden sm:block"></div>

                                    <div class="bg-white/95 backdrop-blur-sm rounded-xl p-6 sm:p-8 shadow-[0_8px_30px_-5px_rgba(0,0,0,0.08)] border border-slate-100 relative overflow-hidden group-hover:shadow-[0_15px_40px_-5px_rgba(0,0,0,0.12)] transition-shadow duration-300">
                                        
                                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-slate-200 to-slate-100 group-hover:from-red-600 group-hover:to-red-400 transition-colors duration-500"></div>

                                        <div class="flex flex-wrap items-center gap-4 mb-5">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Fecha de Evento</span>
                                                <span class="text-sm font-bold text-slate-700 flex items-center gap-1.5">
                                                    <i class="fa-regular fa-calendar text-red-600"></i>
                                                    {{ \Carbon\Carbon::parse($reporte->event_date)->format('d M. Y') }}
                                                </span>
                                            </div>
                                            <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Participación</span>
                                                <span class="text-sm font-bold text-slate-700 flex items-center gap-1.5">
                                                    <i class="fa-solid fa-users text-blue-600"></i>
                                                    {{ $reporte->attendees_count }} Asistentes
                                                </span>
                                            </div>
                                        </div>

                                        <h3 class="text-2xl font-black text-slate-900 mb-4 leading-tight">
                                            {{ $reporte->report_title }}
                                        </h3>

                                        @if($reporte->comment)
                                            <div class="bg-slate-50 border border-slate-100 rounded-lg p-4 mb-6">
                                                <p class="text-slate-600 leading-relaxed text-base font-medium">
                                                    {{ $reporte->comment }}
                                                </p>
                                            </div>
                                        @endif

                                        {{-- CUADRÍCULA DE FOTOS --}}
                                        @if($reporte->photos && count($reporte->photos) > 0)
                                            <div class="mt-2">
                                                <div class="grid grid-cols-2 gap-3 galeria-fotos">
                                                    @php
                                                        $photos = $reporte->photos;
                                                        $priorities = $reporte->photo_priority ?? [];
                                                        if (!empty($priorities) && count($priorities) === count($photos)) {
                                                            $combined = array_combine($priorities, $photos);
                                                            ksort($combined);
                                                            $photos = array_values($combined);
                                                        }
                                                    @endphp
                                                    @foreach($photos as $index => $foto)
                                                        <div class="foto-item relative overflow-hidden bg-slate-200 rounded-xl {{ $index >= 4 ? 'foto-extra' : '' }} border border-slate-200">
                                                            <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 pointer-events-none mix-blend-multiply"></div>
                                                            <img src="{{ asset('storage/'.$foto) }}" 
                                                                 alt="Evidencia documental" 
                                                                 class="foto-galeria w-full h-48 sm:h-64 object-cover cursor-pointer" 
                                                                 loading="lazy">
                                                        </div>
                                                    @endforeach
                                                </div>

                                                @if(count($photos) > 4)
                                                    <button type="button" class="btn-mostrar-mas mt-4 w-full py-3.5 bg-white border border-slate-300 text-slate-700 font-bold text-sm uppercase tracking-wider rounded-xl hover:bg-slate-50 hover:border-slate-400 hover:shadow-sm transition-all flex items-center justify-center gap-2">
                                                        <i class="fa-solid fa-layer-group text-slate-400"></i>
                                                        <span>Expandir galería ({{ count($photos) - 4 }} ocultas)</span>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- VIDEO DE YOUTUBE --}}
                                        @if($reporte->youtube_url)
                                            @php
                                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $reporte->youtube_url, $matches);
                                                $youtubeId = $matches[1] ?? null;
                                            @endphp
                                            @if($youtubeId)
                                                <div class="mt-6 video-preview-container rounded-xl shadow-md border border-slate-200 bg-slate-900" id="video-container-{{ $reporte->id }}">
                                                    <img src="https://img.youtube.com/vi/{{ $youtubeId }}/maxresdefault.jpg"
                                                         onerror="this.src='https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg'"
                                                         alt="Video Reporte"
                                                         class="video-thumbnail w-full h-56 sm:h-80 object-cover opacity-90"
                                                         loading="lazy">
                                                    <div class="play-button" onclick="playVideo(this, '{{ $youtubeId }}', 'video-container-{{ $reporte->id }}')">
                                                        <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M8 5v14l11-7z"/>
                                                        </svg>
                                                    </div>
                                                    <iframe class="video-iframe w-full h-56 sm:h-80 rounded-xl"
                                                            style="display:none;"
                                                            allow="autoplay; encrypted-media; picture-in-picture"
                                                            allowfullscreen=""></iframe>
                                                </div>
                                            @else
                                                <div class="mt-6">
                                                    <a href="{{ $reporte->youtube_url }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider text-white bg-red-600 hover:bg-red-700 shadow-md shadow-red-500/20 hover:-translate-y-0.5 transition-all">
                                                        <i class="fa-brands fa-youtube text-xl"></i> Ver en YouTube
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>
            @endif
        @empty
            <div class="text-center py-20 bg-white/95 backdrop-blur-md rounded-2xl shadow-lg border border-slate-100">
                <i class="fa-regular fa-folder-open text-5xl text-slate-300 mb-4"></i>
                <h3 class="text-xl font-bold text-slate-800">No hay registros</h3>
                <p class="text-slate-500 mt-1">Aún no se han publicado actas o reportes en el sistema.</p>
            </div>
        @endforelse
    </main>

    <footer class="bg-slate-900 text-slate-400 py-8 text-center text-sm border-t border-slate-800 relative z-10">
        <div class="max-w-5xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Dirección Regional de Trabajo y Promoción del Empleo Puno.</p>
        </div>
    </footer>

    <div id="lightbox" class="fixed inset-0 z-50 bg-slate-950/95 backdrop-blur-lg flex flex-col items-center justify-center">
        <div class="absolute top-0 left-0 w-full p-6 flex justify-between items-center z-50">
            <span id="lb-counter" class="text-white font-bold text-sm tracking-widest bg-white/10 px-4 py-2 rounded-full border border-white/20"></span>
            <button id="lb-close" class="text-white bg-white/10 hover:bg-red-600 transition-colors w-12 h-12 rounded-full flex items-center justify-center border border-white/20 hover:border-red-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <button id="lb-prev" class="absolute left-2 sm:left-8 top-1/2 -translate-y-1/2 text-white bg-white/5 hover:bg-white/20 transition-all w-14 h-14 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/10 z-50 hover:scale-110">
            <i class="fa-solid fa-chevron-left text-2xl pr-1"></i>
        </button>

        <div class="relative max-w-6xl max-h-[85vh] w-full px-4 sm:px-24 flex items-center justify-center">
            <img id="lb-img" src="" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl transition-transform duration-300" alt="Vista ampliada">
        </div>

        <button id="lb-next" class="absolute right-2 sm:right-8 top-1/2 -translate-y-1/2 text-white bg-white/5 hover:bg-white/20 transition-all w-14 h-14 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/10 z-50 hover:scale-110">
            <i class="fa-solid fa-chevron-right text-2xl pl-1"></i>
        </button>
    </div>

    <script>
        function playVideo(playButton, youtubeId, containerId) {
            const container = document.getElementById(containerId);
            const thumbnail = container.querySelector('.video-thumbnail');
            const iframe = container.querySelector('.video-iframe');
           
            iframe.src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0`;
            iframe.style.display = 'block';
            thumbnail.style.display = 'none';
            playButton.style.display = 'none';
        }

        document.querySelectorAll('.btn-mostrar-mas').forEach(boton => {
            boton.addEventListener('click', function() {
                const contenedor = this.closest('.mt-2').querySelector('.galeria-fotos');
                if (!contenedor) return;
               
                const extraCount = contenedor.querySelectorAll('.foto-extra').length;
                const spanTexto = this.querySelector('span');
                const icono = this.querySelector('i');
               
                contenedor.classList.toggle('mostrar-todas');
               
                if(contenedor.classList.contains('mostrar-todas')) {
                    spanTexto.innerText = 'Ocultar fotografías adicionales';
                    icono.classList.remove('fa-layer-group');
                    icono.classList.add('fa-chevron-up');
                } else {
                    spanTexto.innerText = `Expandir galería (${extraCount} ocultas)`;
                    icono.classList.remove('fa-chevron-up');
                    icono.classList.add('fa-layer-group');
                }
            });
        });

        // Galería Lightbox
        let galeriaActual = [];
        let indiceActual = 0;
        
        const lightbox = document.getElementById('lightbox');
        const lbImg = document.getElementById('lb-img');
        const lbCounter = document.getElementById('lb-counter');
        
        document.querySelectorAll('.foto-galeria').forEach((img) => {
            img.addEventListener('click', function() {
                const contenedorPadre = this.closest('.galeria-fotos');
                galeriaActual = Array.from(contenedorPadre.querySelectorAll('.foto-galeria'));
                indiceActual = galeriaActual.indexOf(this);
                abrirLightbox();
            });
        });

        function actualizarImagen() {
            lbImg.style.transform = 'scale(0.95)';
            lbImg.style.opacity = '0.5';
            setTimeout(() => {
                lbImg.src = galeriaActual[indiceActual].src;
                lbCounter.innerText = `IMAGEN ${indiceActual + 1} DE ${galeriaActual.length}`;
                lbImg.style.transform = 'scale(1)';
                lbImg.style.opacity = '1';
            }, 150);
        }

        function abrirLightbox() {
            actualizarImagen();
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function cerrarLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(() => { lbImg.src = ''; }, 400);
        }

        document.getElementById('lb-close').addEventListener('click', cerrarLightbox);
        
        document.getElementById('lb-next').addEventListener('click', () => {
            indiceActual = (indiceActual + 1) % galeriaActual.length;
            actualizarImagen();
        });
        
        document.getElementById('lb-prev').addEventListener('click', () => {
            indiceActual = (indiceActual - 1 + galeriaActual.length) % galeriaActual.length;
            actualizarImagen();
        });

        lightbox.addEventListener('click', (e) => {
            if(e.target === lightbox) cerrarLightbox();
        });

        document.addEventListener('keydown', (e) => {
            if(!lightbox.classList.contains('active')) return;
            if(e.key === 'Escape') cerrarLightbox();
            if(e.key === 'ArrowRight') document.getElementById('lb-next').click();
            if(e.key === 'ArrowLeft') document.getElementById('lb-prev').click();
        });
    </script>
</body>
</html>

