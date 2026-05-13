<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor de Actividades | Dirección de Trabajo y Promoción del Empleo - Puno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc;
        }
        
        /* Animaciones suaves para la galería */
        .foto-extra { 
            display: none; 
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .mostrar-todas .foto-extra { 
            display: block; 
            opacity: 1;
            transform: scale(1);
            animation: popIn 0.5s ease-out forwards;
        }
        
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.95) translateY(10px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Mejora para imágenes con hover */
        .foto-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .foto-item:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Línea de tiempo formal */
        .timeline-dot {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .group:hover .timeline-dot {
            transform: scale(1.8);
            background-color: #1d4ed8;
            border-color: #bfdbfe;
            box-shadow: 0 0 0 6px rgba(29, 78, 216, 0.15);
        }

        /* Video preview */
        .video-preview-container {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .video-preview-container:hover {
            transform: scale(1.01);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(29, 78, 216, 0.85);
            backdrop-filter: blur(4px);
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .video-preview-container:hover .play-button {
            background: #2563eb;
            width: 72px;
            height: 72px;
        }
    </style>
</head>
<body class="antialiased selection:bg-blue-700 selection:text-white min-h-screen bg-gray-50">

    <header class="bg-gradient-to-r from-gray-900 via-blue-900 to-gray-900 text-white shadow-2xl sticky top-0 z-50 border-b-2 border-amber-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center py-4 sm:h-24 gap-4 sm:gap-0">
                
                <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto">
                    <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-sm border border-white/5 shrink-0 shadow-inner">
                        <i class="fa-solid fa-building-columns text-2xl text-amber-400"></i>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight truncate">Dirección de Trabajo y Promoción del Empleo</h1>
                        <p class="text-amber-300/80 text-xs sm:text-sm font-semibold uppercase tracking-wider truncate">Puno - Perú</p>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="w-full sm:w-auto justify-center group flex items-center gap-2 bg-blue-800/30 hover:bg-blue-700/50 border border-amber-400/30 transition-all duration-300 px-6 py-3 rounded-full text-sm font-bold backdrop-blur-md">
                    <i class="fa-solid fa-lock text-xs group-hover:-translate-y-0.5 transition-transform text-amber-300"></i> 
                    <span>Acceso Interno</span>
                </a>

            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto py-10 sm:py-16 px-4 sm:px-6 lg:px-8">
        
        <div class="mb-12 bg-white border border-gray-200 p-5 rounded-3xl shadow-md flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="bg-blue-50 p-3 rounded-2xl text-blue-800 shrink-0">
                <i class="fa-solid fa-magnifying-glass-chart text-xl"></i>
            </div>
            <div>
                <h3 class="text-gray-800 font-bold text-lg">Seguimiento de Actividades Operativas</h3>
                <p class="text-gray-500 text-sm mt-0.5">Registro cronológico y evidencia fotográfica de las acciones ejecutadas por la Dirección Regional.</p>
            </div>
        </div>

        @forelse($actividades as $actividad)
            @if($actividad->subEvents->count() > 0)
                <article class="mb-16 bg-white rounded-[2rem] shadow-xl shadow-gray-200/70 overflow-hidden border border-gray-200 transition-all duration-500 hover:-translate-y-1 hover:shadow-2xl">
                    
                    <div class="px-6 py-8 sm:p-10 relative overflow-hidden bg-gradient-to-br from-gray-50 to-white">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
                        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-amber-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
                        
                        <div class="flex flex-wrap items-center gap-3 mb-4 relative z-10">
                            <span class="bg-blue-700 text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider shadow-md shadow-blue-200">
                                {{ $actividad->category->name ?? 'Categoría General' }}
                            </span>
                            <span class="text-gray-700 font-bold text-xs bg-white px-4 py-1.5 rounded-full border border-gray-300 shadow-sm flex items-center gap-2">
                                <i class="fa-solid fa-hashtag text-blue-700"></i>
                                Cód: {{ $actividad->category->pp_code ?? 'Sin Código' }}
                            </span>
                        </div>
                        
                        <h2 class="text-2xl sm:text-4xl font-extrabold text-gray-900 leading-tight relative z-10">
                            {{ $actividad->description }}
                        </h2>
                    </div>

                    <div class="h-px w-full bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>

                    <div class="p-6 sm:p-10">
                        <div class="relative border-l-2 border-blue-200 ml-2 sm:ml-4 pl-6 sm:pl-10 space-y-12">
                            @foreach($actividad->subEvents as $reporte)
                                <div class="relative reporte-container group">
                                    
                                    <div class="absolute -left-[31px] sm:-left-[47px] top-1.5 timeline-dot w-5 h-5 bg-white border-4 border-blue-600 rounded-full transition-all duration-300 shadow-[0_0_10px_rgba(37,99,235,0.4)]"></div>

                                    <div class="bg-white rounded-2xl p-1">
                                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-800 transition-colors duration-200">
                                            {{ $reporte->report_title }}
                                        </h3>
                                        
                                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-5 font-semibold">
                                            <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                                                <i class="fa-regular fa-calendar text-blue-700"></i>
                                                {{ \Carbon\Carbon::parse($reporte->event_date)->format('d/m/Y') }}
                                            </div>
                                            <div class="flex items-center gap-2 bg-green-50 text-green-700 px-3 py-1.5 rounded-lg border border-green-100">
                                                <i class="fa-solid fa-users"></i>
                                                {{ $reporte->attendees_count }} Asistentes
                                            </div>
                                        </div>

                                        @if($reporte->comment)
                                            <p class="text-gray-700 mb-6 leading-relaxed bg-gray-50 p-5 rounded-2xl border-l-4 border-blue-600 text-sm sm:text-base">
                                                {{ $reporte->comment }}
                                            </p>
                                        @endif

                                        {{-- Fotos en cuadrícula 2x2 --}}
                                        @if($reporte->photos && count($reporte->photos) > 0)
                                            <div class="mt-2 bg-gray-50 p-4 rounded-3xl border border-gray-200">
                                                <div class="grid grid-cols-2 gap-2 sm:gap-4 galeria-fotos">
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
                                                        <div class="foto-item relative overflow-hidden rounded-2xl shadow-sm {{ $index >= 4 ? 'foto-extra' : '' }} group/img">
                                                            <div class="absolute inset-0 bg-black/10 group-hover/img:bg-transparent transition-colors z-10"></div>
                                                            <img src="{{ asset('storage/'.$foto) }}" alt="Evidencia del evento" class="w-full h-40 sm:h-64 object-cover transform group-hover/img:scale-110 transition duration-700 ease-out" loading="lazy">
                                                        </div>
                                                    @endforeach
                                                </div>

                                                @if(count($photos) > 4)
                                                    <button type="button" class="btn-mostrar-mas mt-4 w-full py-4 bg-white border border-gray-300 text-blue-700 font-bold rounded-2xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow">
                                                        <i class="fa-solid fa-camera"></i>
                                                        <span>Ver {{ count($photos) - 4 }} fotografías adicionales</span>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Video de YouTube con previsualización y reproducción en línea --}}
                                        @if($reporte->youtube_url)
                                            @php
                                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $reporte->youtube_url, $matches);
                                                $youtubeId = $matches[1] ?? null;
                                            @endphp
                                            @if($youtubeId)
                                                <div class="mt-6 video-preview-container" id="video-container-{{ $reporte->id }}">
                                                    <img src="https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg" 
                                                         alt="Video" 
                                                         class="video-thumbnail w-full h-48 sm:h-64 object-cover rounded-xl shadow-md"
                                                         loading="lazy">
                                                    <div class="play-button" onclick="playVideo(this, '{{ $youtubeId }}', 'video-container-{{ $reporte->id }}')">
                                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M8 5v14l11-7z"/>
                                                        </svg>
                                                    </div>
                                                    <iframe class="video-iframe w-full h-48 sm:h-64 rounded-xl shadow-md" 
                                                            style="display:none;"
                                                            allow="autoplay; encrypted-media; picture-in-picture" 
                                                            allowfullscreen=""></iframe>
                                                </div>
                                            @else
                                                <div class="mt-4">
                                                    <a href="{{ $reporte->youtube_url }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white bg-blue-700 hover:bg-blue-800 font-bold shadow-lg shadow-blue-200 hover:-translate-y-1 transition-all duration-300">
                                                        <i class="fa-brands fa-youtube text-lg"></i> Ver video en YouTube
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
            <div class="text-center py-24 bg-white rounded-[2rem] shadow-xl border border-gray-200">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-folder-open text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-800">Sin actividades publicadas</h3>
                <p class="text-gray-500 mt-3 font-medium max-w-md mx-auto">Pronto se mostrarán aquí los reportes y evidencias de las actividades realizadas por la Dirección.</p>
            </div>
        @endforelse
    </main>

    <footer class="bg-gray-900 text-gray-300 py-10 text-center text-sm border-t-2 border-amber-500">
        <p class="font-medium tracking-wide">&copy; {{ date('Y') }} Dirección de Trabajo y Promoción del Empleo - Puno. Todos los derechos reservados.</p>
    </footer>

    <script>
        // Función para reproducir video en el mismo lugar
        function playVideo(playButton, youtubeId, containerId) {
            const container = document.getElementById(containerId);
            const thumbnail = container.querySelector('.video-thumbnail');
            const iframe = container.querySelector('.video-iframe');
            
            iframe.src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0`;
            iframe.style.display = 'block';
            thumbnail.style.display = 'none';
            playButton.style.display = 'none';
        }

        // Galería de fotos: mostrar/ocultar extras
        document.querySelectorAll('.btn-mostrar-mas').forEach(boton => {
            boton.addEventListener('click', function() {
                const contenedor = this.closest('.bg-gray-50, .rounded-3xl').querySelector('.galeria-fotos');
                if (!contenedor) return;
                
                const extraCount = contenedor.querySelectorAll('.foto-extra').length;
                const spanTexto = this.querySelector('span');
                const icono = this.querySelector('i');
                
                contenedor.classList.toggle('mostrar-todas');
                
                if(contenedor.classList.contains('mostrar-todas')) {
                    spanTexto.innerText = 'Cerrar galería fotográfica';
                    icono.classList.remove('fa-camera');
                    icono.classList.add('fa-chevron-up');
                    this.classList.replace('bg-white', 'bg-blue-50');
                } else {
                    spanTexto.innerText = `Ver ${extraCount} fotografías adicionales`;
                    icono.classList.remove('fa-chevron-up');
                    icono.classList.add('fa-camera');
                    this.classList.replace('bg-blue-50', 'bg-white');
                }
            });
        });
    </script>
</body>
</html>