<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Transparencia | DTPE Puno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800;900&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --red:      #dc2626;
            --navy:     #060c1a;
            --sidebar-w: 268px;
            --header-h:  68px;
            --diag:      4.5vw;
        }

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--navy);
            color: #e2e8f0;
            overflow-x: hidden;
            margin: 0;
        }
        h1,h2,h3,h4,h5 { font-family:'Sora',sans-serif; }

        /* ── BG SCENE ───────────────────────────────────────── */
        .bg-scene {
            background-image: url('/images/fondodash2.png');
            background-size: cover;
            background-position: center top;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        /* ── SIDEBAR ────────────────────────────────────────── */
        #sidebar {
            position: fixed; left:0; top:var(--header-h);
            width:var(--sidebar-w);
            height:calc(100vh - var(--header-h));
            background:rgba(4,8,18,.95);
            border-right:1px solid rgba(255,255,255,.07);
            backdrop-filter:blur(24px); -webkit-backdrop-filter:blur(24px);
            overflow-y:auto; overflow-x:hidden;
            z-index:45;
            transition:transform .3s cubic-bezier(.4,0,.2,1);
        }
        #sidebar::-webkit-scrollbar{width:3px}
        #sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);border-radius:99px}
        #sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);z-index:44;}

        .sb-section-label {
            font-family:'Sora',sans-serif;
            font-size:9px; font-weight:700; letter-spacing:.22em; text-transform:uppercase;
            color:rgba(255,255,255,.24);
            padding:16px 14px 6px;
            border-top:1px solid rgba(255,255,255,.05);
            margin-top:4px;
        }
        .sb-section-label:first-of-type{border-top:none;margin-top:0;}
        .sb-item {
            display:flex; align-items:flex-start; gap:9px;
            padding:6px 10px; border-radius:10px; margin:0 8px 2px;
            cursor:pointer; border-left:2px solid transparent;
            transition:background .15s, border-color .15s;
        }
        .sb-item:hover{background:rgba(255,255,255,.06);}
        .sb-item.active{background:rgba(220,38,38,.12);border-color:#dc2626;}
        .sb-thumb{
            width:30px;height:30px;border-radius:7px;overflow:hidden;flex-shrink:0;
            background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);
            display:flex;align-items:center;justify-content:center;
        }
        .sb-thumb img{width:100%;height:100%;object-fit:cover;}
        .sb-dot{width:7px;height:7px;border-radius:3px;flex-shrink:0;margin-top:5px;}
        .sb-sub-sep{
            display:flex;align-items:center;gap:7px;padding:5px 14px 3px;
        }
        .sb-sub-sep span{font-size:8.5px;font-weight:800;letter-spacing:.18em;text-transform:uppercase;white-space:nowrap;}
        .sb-sub-sep::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.07);}

        /* ── MAIN LAYOUT ─────────────────────────────────────── */
        #main-content{margin-left:var(--sidebar-w);min-height:100vh;transition:margin-left .3s;}

        /* ── SLIDERS ─────────────────────────────────────────── */
        .clip-top    { clip-path: polygon(0 0, 100% 0, 100% calc(100% - var(--diag)), 0 100%); }
        .clip-bottom { clip-path: polygon(0 0, 100% 0, 100% 100%, 0 calc(100% - var(--diag))); }

        .ken-burns{animation:kenBurns 16s ease-out infinite alternate;}
        @keyframes kenBurns{from{transform:scale(1);}to{transform:scale(1.13);}}

        .slider-progress-wrap{
            position:absolute;
            bottom:var(--diag);
            left:0;right:0;height:3px;
            background:rgba(255,255,255,.12);z-index:30;
        }
        .slider-progress-fill{height:100%;transition:width 50ms linear;}

        .slider-dot{width:8px;height:8px;border-radius:99px;background:rgba(255,255,255,.3);transition:width .3s,background .3s;cursor:pointer;border:none;}
        .slider-dot.is-active{width:22px;background:#fff;}

        /* ── SECCIONES ULTRA TRANSPARENTES (Modificado a .35 / .40) ── */
        .section-after-sliders {
            position:relative;
            margin-top:calc(-1 * var(--diag));
            padding-top:calc(var(--diag) + 2.5rem);
            background:rgba(5,9,20,.35); /* Máxima transparencia para lucir el fondo */
            backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
            border-bottom:1px solid rgba(255,255,255,.06);
        }

        .section-dark {
            background:rgba(5,9,20,.40); /* Máxima transparencia para lucir el fondo */
            backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
            border-top:1px solid rgba(255,255,255,.06);
            border-bottom:1px solid rgba(255,255,255,.06);
        }

        /* ── TIMELINE ── */
        .foto-extra{display:none;opacity:0;transform:scale(.95);transition:all .45s;}
        .mostrar-todas .foto-extra{display:block;opacity:1;transform:scale(1);animation:fadeInGrid .45s ease forwards;}
        @keyframes fadeInGrid{from{opacity:0;transform:translateY(12px) scale(.95);}to{opacity:1;transform:translateY(0) scale(1);}}
        .foto-galeria{transition:transform .55s;cursor:zoom-in;display:block;}
        .foto-item:hover .foto-galeria{transform:scale(1.05);}

        .foto-item.portrait .foto-galeria{height:260px;}
        .foto-item.landscape .foto-galeria{height:160px;}

        .timeline-rail{border-left:2px solid rgba(96,165,250,.22);}
        
        /* Diamante rotado original a 45 grados */
        .timeline-node {
            position: absolute; left: calc(-1rem - 9px); top: 1.75rem;
            width: 17px; height: 17px; background: #3b82f6;
            border: 3px solid rgba(15,30,70,.85); border-radius: 5px;
            box-shadow: 0 0 10px rgba(96,165,250,.45);
            transition: all .25s; z-index: 2; transform: rotate(45deg);
        }
        .reporte-wrapper:hover .timeline-node{background:#dc2626;transform:rotate(135deg);box-shadow:0 0 14px rgba(220,38,38,.5);}
        .subevent-card{background:rgba(255,255,255,.96);border-radius:18px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.18);transition:box-shadow .25s;}
        .subevent-card:hover{box-shadow:0 8px 36px rgba(0,0,0,.28);}

        .activity-header{
            background:linear-gradient(130deg,#0c1a50 0%,#1e3a8a 60%,#1d4ed8 100%);
            border-left:5px solid #f59e0b;
            border-radius:18px;overflow:hidden;position:relative;
        }
        .activity-header::before{content:'';position:absolute;top:-20px;right:-20px;width:100px;height:100px;background:rgba(245,158,11,.07);border-radius:50%;}

        .record-card{
            background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);
            border-radius:18px;overflow:hidden;
            transition:background .2s,border-color .2s,transform .2s,box-shadow .2s;
            cursor:pointer;display:block;
        }
        .record-card:hover{background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.2);transform:translateY(-4px);box-shadow:0 20px 40px rgba(0,0,0,.4);}

        /* Video de YouTube */
        .video-preview-container { position: relative; overflow: hidden; cursor: pointer; transition: all 0.3s ease; }
        .video-preview-container:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); }
        .play-button {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            background: rgba(220, 38, 38, 0.9); width: 64px; height: 64px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; transition: all 0.4s ease;
            animation: pulse-red 2s infinite; z-index: 10;
        }
        @keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); } 70% { box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); } 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }

        .social-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:9px; font-size:11px; font-weight:700; transition:all .18s; text-decoration:none; white-space:nowrap; }
        .social-badge:hover { transform:translateY(-2px); filter:brightness(1.1); }
        .badge-fb { background:#1877f2; color:#fff; }
        .badge-tt { background:#111; color:#fff; border:1px solid rgba(255,255,255,.15); }
        .footer-light { background:rgba(240,244,248,.95); backdrop-filter:blur(10px); color:#1e293b; }

        #lightbox { opacity:0; visibility:hidden; transition:opacity .35s,visibility .35s; }
        #lightbox.active { opacity:1; visibility:visible; }
        .highlight-target { animation:highlightPulse 2s ease; }
        @keyframes highlightPulse { 0% {box-shadow:0 0 0 0 rgba(220,38,38,.6);} 60% {box-shadow:0 0 0 22px rgba(220,38,38,0);} 100%{box-shadow:none;} }
    </style>
</head>

<body class="antialiased selection:bg-red-700 selection:text-white">

@php
    $comunicadosActivos = $comunicadosActivos ?? collect();
    $bulletins          = $bulletins ?? collect();
    $noticias           = $noticias ?? collect();
    $ultimos3           = $ultimos3 ?? collect();
@endphp

@if($comunicadosActivos->count() > 0)
    @php $destacado = $comunicadosActivos->first(); @endphp
    <div x-data="{ showPopup: true }" x-show="showPopup" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/5 backdrop-blur-md" style="display:none;">
        <div class="relative bg-slate-900 border border-white/10 rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl flex flex-col" @click.away="showPopup = false" x-transition>
            <div class="absolute top-4 right-4 z-50">
                <button @click="showPopup = false" class="w-10 h-10 rounded-full bg-black/60 text-white hover:bg-red-600 transition flex items-center justify-center border border-white/10"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="w-full relative bg-slate-950 flex items-center justify-center min-h-[320px] max-h-[70vh]">
                @if($destacado->file_type === 'image')
                    <img src="{{ asset('storage/' . $destacado->file_path) }}" class="w-full h-full object-contain">
                @else
                    <div class="p-8 text-center space-y-4">
                        <div class="w-20 h-20 bg-red-600/10 text-red-500 border border-red-500/20 rounded-2xl flex items-center justify-center mx-auto text-4xl shadow-xl"><i class="fa-solid fa-file-pdf"></i></div>
                        <span class="inline-block bg-red-600 text-white font-black text-[9px] uppercase tracking-widest px-3 py-1 rounded-md">Comunicado Oficial</span>
                        <h3 class="text-xl font-black text-white px-4 leading-tight">{{ $destacado->title }}</h3>
                    </div>
                @endif
            </div>
            <div class="bg-slate-950/80 p-5 border-t border-white/10 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-white font-black text-sm truncate">{{ $destacado->title }}</p>
                    <p class="text-slate-400 text-[10px] font-bold uppercase mt-0.5"><i class="fa-regular fa-calendar mr-1"></i>Publicado: {{ $destacado->published_at->format('d/m/Y') }}</p>
                </div>
                <a href="{{ asset('storage/' . $destacado->file_path) }}" target="_blank" class="bg-red-600 hover:bg-red-500 text-white text-xs font-black uppercase tracking-wider px-5 py-3 rounded-xl shadow-lg flex items-center gap-2"><i class="fa-solid fa-arrow-up-right-from-square"></i> Ver Más</a>
            </div>
        </div>
    </div>
@endif

<header id="main-header" class="fixed top-0 left-0 right-0 z-50 bg-slate-950/95 backdrop-blur-xl border-b border-white/[.07] shadow-2xl" style="height:var(--header-h);">
    <div class="h-full px-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <button id="sidebar-toggle" class="lg:hidden w-10 h-10 rounded-xl bg-white/08 hover:bg-white/14 border border-white/10 flex items-center justify-center transition"><i class="fa-solid fa-bars text-white text-sm"></i></button>
            <div class="bg-white/10 p-1.5 rounded-xl border border-white/15 shadow-lg"><img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-9 h-9 object-contain"></div>
            <div class="hidden sm:block">
                <p class="text-white font-black text-base leading-tight tracking-tight">Portal Oficial de Actividades</p>
                <p class="text-slate-500 text-[10px] font-semibold uppercase tracking-widest">DTPE Puno · Perú</p>
            </div>
        </div>
        <a href="{{ route('login') }}" class="flex items-center gap-2 bg-white/08 hover:bg-red-700/70 border border-white/10 hover:border-red-500/40 px-5 py-2 rounded-xl text-xs font-bold text-white transition-all"><i class="fa-solid fa-lock text-red-400 text-sm"></i><span class="hidden sm:inline">Acceso Interno</span></a>
    </div>
</header>

<div id="sidebar-overlay" onclick="closeSidebar()"></div>
<aside id="sidebar">
    <div class="p-4 pb-5">
        <div class="flex items-center gap-3 mb-5 pt-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-11 h-11 object-contain rounded-xl bg-white/08 p-1.5 border border-white/10">
            <div><p class="text-white font-black text-sm leading-tight">DTPE Puno</p><p class="text-slate-500 text-[10px] uppercase tracking-wider">Dirección Regional</p></div>
        </div>
        <div class="space-y-2.5 mb-4">
            <div class="flex items-start gap-2">
                <div class="w-6 h-6 rounded-lg bg-red-600/20 border border-red-500/25 flex items-center justify-center mt-0.5"><i class="fa-solid fa-location-dot text-red-400 text-[10px]"></i></div>
                <div><p class="text-slate-200 text-[11px] font-bold">Sede Puno</p><p class="text-slate-500 text-[10px] leading-snug">Jr. Ayacucho N° 658, Puno</p></div>
            </div>
            <div class="flex items-start gap-2">
                <div class="w-6 h-6 rounded-lg bg-blue-600/20 border border-blue-500/25 flex items-center justify-center mt-0.5"><i class="fa-solid fa-location-dot text-blue-400 text-[10px]"></i></div>
                <div><p class="text-slate-200 text-[11px] font-bold">Sede Juliaca</p><p class="text-slate-500 text-[10px] leading-snug">Jr. Amazonas N° 2000, Juliaca</p></div>
            </div>
        </div>
        <div class="flex gap-1.5 flex-wrap">
            <a href="https://www.facebook.com/DRTPEPunoOFICIAL/?locale=es_LA" target="_blank" class="social-badge badge-fb"><i class="fa-brands fa-facebook text-sm"></i> Facebook</a>
            <a href="#" target="_blank" class="social-badge badge-tt"><i class="fa-brands fa-tiktok text-sm"></i> TikTok</a>
        </div>
    </div>

    <p class="sb-section-label"><i class="fa-solid fa-images mr-1.5 text-blue-400"></i>Reportes Fotográficos</p>
    @if($difusiones->count())
        <div class="sb-sub-sep"><span class="text-blue-400">Difusión</span></div>
        @foreach($difusiones->take(5) as $dif)
        <div class="sb-item" onclick="window.dispatchEvent(new CustomEvent('open-modal',{detail:{report:{{ $dif->toJson() }}}}))">
            <div class="sb-thumb">@if(isset($dif->photos[0]))<img src="{{ asset('storage/'.$dif->photos[0]) }}">@endif</div>
            <div class="flex-1 min-w-0"><p class="text-slate-200 text-[11px] font-semibold leading-snug truncate">{{ $dif->title }}</p><p class="text-blue-400 text-[9px] mt-0.5 font-bold uppercase tracking-wider">Difusión</p></div>
        </div>
        @endforeach
    @endif
    @if($institucionales->count())
        <div class="sb-sub-sep mt-1"><span class="text-red-400">Institucional</span></div>
        @foreach($institucionales->take(5) as $inst)
        <div class="sb-item" onclick="window.dispatchEvent(new CustomEvent('open-modal',{detail:{report:{{ $inst->toJson() }}}}))">
            <div class="sb-thumb">@if(isset($inst->photos[0]))<img src="{{ asset('storage/'.$inst->photos[0]) }}">@endif</div>
            <div class="flex-1 min-w-0"><p class="text-slate-200 text-[11px] font-semibold leading-snug truncate">{{ $inst->title }}</p><p class="text-red-400 text-[9px] mt-0.5 font-bold uppercase tracking-wider">Institucional</p></div>
        </div>
        @endforeach
    @endif
</aside>

<div id="main-content" style="padding-top:var(--header-h);">

    <div class="bg-scene relative">
        @if($difusiones->count() > 0)
        <section class="relative w-full overflow-hidden clip-top z-30" style="height:clamp(360px,64vh,680px);background:rgba(15,28,80,.40);" x-data="autoSlider({{ $difusiones->toJson() }}, 5000)">
            <div class="absolute top-5 left-5 z-30 flex items-center gap-3 flex-wrap">
                <span class="bg-blue-600/85 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full border border-blue-400/30 shadow-lg"><i class="fa-solid fa-radio mr-1.5"></i> Actividades de Difusión</span>
                <div class="flex gap-1.5">
                    <template x-for="(item,i) in items" :key="i"><button @click="active=i;progress=0" :class="active===i?'slider-dot is-active':'slider-dot'"></button></template>
                </div>
            </div>
            <template x-for="(item,index) in items" :key="index">
                <div x-show="active===index" x-transition:enter="transition-opacity duration-700" class="absolute inset-0 cursor-pointer group" @click="$dispatch('open-modal',{report:item})">
                    <img :src="'{{ asset('storage') }}/'+item.photos[0]" class="w-full h-full object-cover ken-burns" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-950 via-blue-900/30 to-blue-950/10"></div>
                    <div class="absolute left-5 sm:left-12 max-w-2xl" style="bottom:calc(var(--diag) + 2.5rem);">
                        <div class="bg-blue-900/45 backdrop-blur-md border border-blue-400/20 rounded-2xl p-4 sm:p-6">
                            <h2 class="text-2xl sm:text-4xl font-black text-white leading-tight" x-text="item.title"></h2>
                            <p class="text-blue-300/80 mt-2 text-xs font-medium flex items-center gap-1.5"><i class="fa-solid fa-hand-pointer animate-pulse"></i> Presione para ver descripción y galería</p>
                        </div>
                    </div>
                </div>
            </template>
            <div class="slider-progress-wrap"><div class="slider-progress-fill bg-blue-400 shadow-[0_0_7px_#60a5fa]" :style="'width:'+progress+'%'"></div></div>
        </section>
        @endif

        @if($institucionales->count() > 0)
        <section class="relative w-full overflow-hidden clip-bottom z-20" style="height:clamp(360px,64vh,680px);margin-top:calc(-1*var(--diag));background:rgba(70,8,8,.40);" x-data="autoSlider({{ $institucionales->toJson() }}, 5000)">
            <div class="absolute z-30 flex flex-col items-end gap-2" style="top:calc(var(--diag) + 14px);right:1.25rem;">
                <span class="bg-red-600/85 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full border border-red-400/30 shadow-lg"><i class="fa-solid fa-calendar-star mr-1.5"></i> Eventos Institucionales</span>
                <div class="flex gap-1.5">
                    <template x-for="(item,i) in items" :key="i"><button @click="active=i;progress=0" :class="active===i?'slider-dot is-active':'slider-dot'"></button></template>
                </div>
            </div>
            <template x-for="(item,index) in items" :key="index">
                <div x-show="active===index" x-transition:enter="transition-opacity duration-700" class="absolute inset-0 cursor-pointer group" @click="$dispatch('open-modal',{report:item})">
                    <img :src="'{{ asset('storage') }}/'+item.photos[0]" class="w-full h-full object-cover ken-burns" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-red-950 via-red-900/30 to-red-950/10"></div>
                    <div class="absolute left-5 sm:left-12 max-w-2xl" style="bottom:calc(var(--diag) + 2.5rem);">
                        <div class="bg-red-900/45 backdrop-blur-md border border-red-400/20 rounded-2xl p-4 sm:p-6">
                            <h2 class="text-2xl sm:text-4xl font-black text-white leading-tight" x-text="item.title"></h2>
                            <p class="text-red-300/80 mt-2 text-xs font-medium flex items-center gap-1.5"><i class="fa-solid fa-hand-pointer animate-pulse"></i> Presione para ver descripción y galería</p>
                        </div>
                    </div>
                </div>
            </template>
            <div class="slider-progress-wrap"><div class="slider-progress-fill bg-red-400 shadow-[0_0_7px_#f87171]" :style="'width:'+progress+'%'"></div></div>
        </section>
        @endif
    </div>

    @if($ultimos3->count() > 0)
    <div class="section-after-sliders pb-14 z-20">
        <section class="max-w-6xl mx-auto px-5 lg:px-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0"><i class="fa-solid fa-bolt text-white"></i></div>
                <div>
                    <h2 class="text-xl font-black text-white">Últimos Registros</h2>
                    <p class="text-slate-400 text-xs font-medium">Actividades recientes con evidencia fotográfica</p>
                </div>
                <div class="flex-1 h-px bg-gradient-to-r from-red-700/40 to-transparent hidden sm:block"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($ultimos3 as $se)
                <div class="record-card" onclick="scrollToSubEvent('subevent-{{ $se->id }}', {{ $se->activity_index }})">
                    <div class="relative overflow-hidden" style="height:160px;">
                        <img src="{{ asset('storage/'.$se->cover) }}" class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 to-transparent opacity-80"></div>
                        <div class="absolute top-3 left-3"><span class="bg-red-600 text-white text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-wider shadow">{{ $se->category_name }}</span></div>
                        <div class="absolute bottom-3 right-3"><span class="bg-black/40 backdrop-blur-sm text-slate-200 text-[9px] font-bold px-2 py-1 rounded-md border border-white/15"><i class="fa-regular fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($se->event_date)->format('d/m/Y') }}</span></div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-black text-slate-100 leading-snug line-clamp-2">{{ $se->report_title }}</h3>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[10px] text-slate-400 font-medium"><i class="fa-solid fa-users text-blue-400 mr-1"></i>{{ $se->attendees_count }} asistentes</span>
                            <span class="text-[10px] text-red-400 font-bold flex items-center gap-1">Ver en cronología &rarr;</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>
    @endif

    @if($noticias->count() > 0)
    <div id="seccion-noticias" class="section-dark py-14 z-20">
        <section class="max-w-6xl mx-auto px-5 lg:px-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 bg-emerald-700 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-newspaper text-white text-sm"></i></div>
                <div>
                    <h2 class="text-xl font-black text-white">Noticias</h2>
                    <p class="text-slate-400 text-xs font-medium">Información institucional y comunicados recientes</p>
                </div>
                <div class="flex-1 h-px bg-gradient-to-r from-emerald-700/40 to-transparent hidden sm:block"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($noticias as $noticia)
                @php $orient = ($noticia->orientation ?? 'landscape') === 'portrait' ? 'portrait' : 'landscape'; @endphp
                <div class="noticia-card">
                    @if($noticia->photo)
                    <div class="noticia-img-wrap {{ $orient }} relative overflow-hidden">
                        <img src="{{ asset('storage/'.$noticia->photo) }}" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 to-transparent"></div>
                    </div>
                    @endif
                    <div class="p-5">
                        <p class="text-slate-400 text-[10px] font-bold mb-2"><i class="fa-regular fa-calendar text-emerald-400 mr-1"></i>{{ \Carbon\Carbon::parse($noticia->published_at)->format('d M. Y') }}</p>
                        <h3 class="text-base font-black text-slate-100 leading-snug mb-3">{{ $noticia->title }}</h3>
                        @if($noticia->description)
                        <p class="text-slate-400 text-xs leading-relaxed line-clamp-4">{{ $noticia->description }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>
    @endif

    <div class="bg-scene relative z-10">
        <div class="absolute inset-0 bg-slate-950/30 backdrop-blur-[1px]"></div>
        <section class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 py-16" x-data="{ limit:10 }">
            <div class="mb-12">
                <div class="inline-flex items-center gap-3 bg-white/05 backdrop-blur-md border border-white/09 rounded-2xl px-6 py-4">
                    <div class="w-9 h-9 bg-red-700/80 rounded-xl flex items-center justify-center"><i class="fa-solid fa-timeline text-white text-sm"></i></div>
                    <div>
                        <h2 class="text-xl font-black text-white">Cronología Operativa</h2>
                        <p class="text-slate-400 text-[11px] mt-0.5 font-medium">Historial de cumplimiento de metas institucionales</p>
                    </div>
                </div>
            </div>

            <div class="space-y-10">
            @foreach($actividades as $aIdx => $actividad)
            @if($actividad->subEvents->count() > 0)
            @php
                $latestSub = $actividad->subEvents->first();
                $restSub   = $actividad->subEvents->skip(1)->values();
            @endphp
            <article id="actividad-{{ $aIdx }}" x-show="{{ $aIdx }} < limit" x-transition.opacity.duration.500ms style="display:{{ $aIdx < 10 ? 'block' : 'none' }}">
                <div class="activity-header p-5 shadow-xl mb-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-400/20 border border-amber-400/30 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-folder-open text-amber-400 text-sm"></i></div>
                            <div><h3 class="text-base sm:text-lg font-black text-white leading-snug">{{ $actividad->description }}</h3></div>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap flex-shrink-0">
                            <span class="bg-amber-400/18 border border-amber-400/30 text-amber-300 text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">PP: {{ $actividad->category->pp_code ?? '000' }}</span>
                        </div>
                    </div>
                </div>

                <div class="relative timeline-rail ml-6 sm:ml-8 pl-7 sm:pl-10 space-y-5 pt-5 pb-3" x-data="{ expanded:false }" id="timeline-section-{{ $aIdx }}">
                    <div id="subevent-{{ $latestSub->id }}" data-activity-idx="{{ $aIdx }}" data-is-latest="1" class="relative reporte-wrapper group">
                        <div class="timeline-node"></div>
                        <div class="subevent-card">
                            <div class="bg-gradient-to-r from-red-600 to-red-700 px-5 py-2 flex items-center gap-2">
                                <i class="fa-solid fa-star text-amber-300 text-xs"></i>
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Registro más reciente</span>
                            </div>
                            <div class="p-5 sm:p-7">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200"><i class="fa-regular fa-calendar text-red-600 mr-1"></i>{{ \Carbon\Carbon::parse($latestSub->event_date)->format('d M. Y') }}</span>
                                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200"><i class="fa-solid fa-users mr-1"></i>{{ $latestSub->attendees_count }} Asistentes</span>
                                </div>
                                <h4 class="text-xl sm:text-2xl font-black text-slate-900 mb-4 leading-tight">{{ $latestSub->report_title }}</h4>
                                @if($latestSub->comment)
                                    <p class="text-slate-700 text-sm mb-5 font-medium">{{ $latestSub->comment }}</p>
                                @endif

                                @if(isset($latestSub->photos_sorted) && count($latestSub->photos_sorted) > 0)
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                    <div class="grid grid-cols-2 gap-2 sm:gap-3 galeria-fotos">
                                        @foreach($latestSub->photos_sorted as $pi=>$foto)
                                        <div class="foto-item relative overflow-hidden bg-slate-200 rounded-xl {{ $pi>=4?'foto-extra':'' }} border-2 border-white shadow-sm">
                                            <img src="{{ asset('storage/'.$foto) }}" class="foto-galeria w-full h-36 sm:h-52 object-cover" loading="lazy">
                                        </div>
                                        @endforeach
                                    </div>
                                    @if(count($latestSub->photos_sorted) > 4)
                                    <button type="button" class="btn-mostrar-mas mt-3 w-full py-3 bg-white border border-slate-200 text-slate-600 text-xs font-bold uppercase rounded-xl flex items-center justify-center gap-2 shadow-sm"><i class="fa-solid fa-images text-red-500"></i><span>Ver {{ count($latestSub->photos_sorted)-4 }} fotografías adicionales</span></button>
                                    @endif
                                </div>
                                @endif

                                @if($latestSub->youtube_url)
                                    @php
                                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $latestSub->youtube_url, $matches);
                                        $youtubeId = $matches[1] ?? null;
                                    @endphp
                                    @if($youtubeId)
                                        <div class="mt-6 video-preview-container rounded-2xl shadow-lg border-4 border-slate-100 bg-slate-900" id="video-container-{{ $latestSub->id }}">
                                            <img src="https://img.youtube.com/vi/{{ $youtubeId }}/maxresdefault.jpg" onerror="this.src='https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg'" class="video-thumbnail w-full h-56 sm:h-80 object-cover opacity-90" loading="lazy">
                                            <div class="play-button" onclick="playVideo(this, '{{ $youtubeId }}', 'video-container-{{ $latestSub->id }}')">
                                                <svg class="w-10 h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                            <iframe class="video-iframe w-full h-56 sm:h-80 rounded-xl" style="display:none;" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($restSub->count() > 0)
                    <button id="expand-toggle-{{ $aIdx }}" @click="expanded=!expanded" class="w-full py-2.5 flex items-center justify-center gap-2 rounded-xl border text-xs font-bold uppercase tracking-wide transition-all" :class="expanded?'bg-slate-700/40 border-slate-600/40 text-slate-300 hover:bg-slate-600/40':'bg-white/06 border-white/10 text-slate-400 hover:bg-white/10'"><i class="fa-solid transition-transform duration-300" :class="expanded?'fa-chevron-up':'fa-list-ul'"></i><span x-text="expanded?'Ocultar registros anteriores':'Ver {{ $restSub->count() }} registro(s) anterior(es) de esta actividad'"></span></button>
                    <div x-show="expanded" x-transition class="space-y-5">
                        @foreach($restSub as $reporte)
                        <div id="subevent-{{ $reporte->id }}" data-activity-idx="{{ $aIdx }}" data-is-latest="0" class="relative reporte-wrapper group">
                            <div class="timeline-node" style="background:#475569;"></div>
                            <div class="subevent-card border border-slate-100">
                                <div class="p-5 sm:p-7">
                                    <div class="flex flex-wrap items-center gap-3 mb-4">
                                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200"><i class="fa-regular fa-calendar text-slate-500 mr-1"></i>{{ \Carbon\Carbon::parse($reporte->event_date)->format('d M. Y') }}</span>
                                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200"><i class="fa-solid fa-users mr-1"></i>{{ $reporte->attendees_count }} Asistentes</span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-black text-slate-800 mb-4 leading-snug">{{ $reporte->report_title }}</h4>
                                    @if($reporte->comment)
                                    <div class="bg-slate-50 border-l-4 border-slate-200 rounded-r-xl p-4 mb-5"><p class="text-slate-600 text-sm leading-relaxed font-medium">{{ $reporte->comment }}</p></div>
                                    @endif
                                    
                                    @if(isset($reporte->photos_sorted) && count($reporte->photos_sorted) > 0)
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                        <div class="grid grid-cols-2 gap-2 sm:gap-3 galeria-fotos">
                                            @foreach($reporte->photos_sorted as $pi=>$foto)
                                            <div class="foto-item relative overflow-hidden bg-slate-200 rounded-xl {{ $pi>=4?'foto-extra':'' }} border-2 border-white shadow-sm"><img src="{{ asset('storage/'.$foto) }}" class="foto-galeria w-full h-36 sm:h-52 object-cover" loading="lazy"></div>
                                            @endforeach
                                        </div>
                                        @if(count($reporte->photos_sorted) > 4)
                                        <button type="button" class="btn-mostrar-mas mt-3 w-full py-3 bg-white border border-slate-200 text-slate-600 text-xs font-bold uppercase rounded-xl flex items-center justify-center gap-2"><i class="fa-solid fa-images text-red-500"></i><span>Ver {{ count($reporte->photos_sorted)-4 }} fotografías adicionales</span></button>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </article>
            @endif
            @endforeach
            </div>

            @if(count($actividades)>10)
            <div class="text-center mt-14" x-show="limit < {{ count($actividades) }}">
                <button @click="limit+=10" class="bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest px-10 py-4 rounded-full border border-red-500/30 shadow-lg"><i class="fa-solid fa-plus mr-2"></i> Cargar 10 actividades más</button>
            </div>
            @endif
        </section>
    </div>

    <section class="footer-light border-t border-slate-300 relative z-20">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-12">
            <div class="flex items-center gap-4 mb-10"><h2 class="text-2xl font-black text-slate-800">Medios e Información</h2><div class="flex-1 h-px bg-slate-300"></div></div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-4">
                    <div class="flex items-center gap-2 mb-4"><i class="fa-brands fa-facebook text-blue-600 text-lg"></i><h4 class="text-xs font-black text-slate-600 uppercase tracking-wider">Facebook</h4></div>
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-slate-200" style="height:480px;">
                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FDRTPEPunoOFICIAL&tabs=timeline&width=340&height=480&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true&appId"
                                width="100%" height="480"
                                style="border:none;overflow:hidden;" scrolling="no" frameborder="0"
                                allowfullscreen allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                    </div>
                </div>

                <div class="lg:col-span-8 lg:pl-10 lg:border-l lg:border-slate-300 space-y-8">
                    <div>
                        <div class="flex items-center gap-2 mb-4"><i class="fa-brands fa-tiktok text-slate-900 text-lg"></i><h4 class="text-xs font-black text-slate-600 uppercase tracking-wider">TikTok</h4></div>
                        <a href="#" target="_blank" class="flex items-center gap-5 bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 border border-slate-700/60 transition group shadow-lg">
                            <div class="w-14 h-14 rounded-2xl bg-black flex items-center justify-center border border-white/10"><i class="fa-brands fa-tiktok text-white text-2xl"></i></div>
                            <div class="flex-1 min-w-0"><p class="text-white font-black text-base">@DTREPuno</p><p class="text-slate-400 text-xs mt-1">Síganos para ver nuestras actividades en formato corto.</p></div>
                        </a>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-4"><i class="fa-solid fa-newspaper text-red-600 text-lg"></i><h4 class="text-xs font-black text-slate-600 uppercase tracking-wider">Boletines Informativos</h4></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($bulletins as $boletin)
                                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-md flex flex-col h-[380px]">
                                    <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 flex justify-between items-center shrink-0">
                                        <span class="text-xs font-black text-slate-800 truncate max-w-[70%]"><i class="fa-solid fa-file-pdf text-red-600 mr-1.5"></i>{{ $boletin->title }}</span>
                                        <a href="{{ asset('storage/' . $boletin->file_path) }}" target="_blank" class="text-[10px] bg-red-600 text-white px-2 py-0.5 rounded font-bold uppercase hover:bg-red-700"><i class="fa-solid fa-expand"></i></a>
                                    </div>
                                    <div class="flex-1 w-full bg-slate-100">
                                        <iframe src="{{ asset('storage/' . $boletin->file_path) }}#toolbar=0&navpanes=0&scrollbar=0" width="100%" height="100%" class="border-none"></iframe>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white border border-slate-200 rounded-2xl flex flex-col items-center justify-center p-8 col-span-2 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-folder-open text-xl mb-2 text-slate-300"></i> No hay boletines publicados actualmente
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($comunicadosActivos->count() > 0)
    <section class="bg-slate-900/40 backdrop-blur-md border-t border-white/10 relative z-30 w-full py-12"
             x-data="{ active: 0, count: {{ $comunicadosActivos->count() }} }"
             x-init="if(count > 1) { setInterval(() => { active = (active + 1) % count }, 5000) }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center justify-between mb-6 bg-slate-950/50 p-4 rounded-2xl border border-white/5 shadow-xl">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></div>
                    <h2 class="text-sm font-black text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-bullhorn text-amber-500"></i> Tablón de Comunicados Oficiales Vigentes
                    </h2>
                </div>
                <div class="text-xs font-mono text-slate-400 font-bold bg-black/40 px-3 py-1 rounded-md border border-white/5">
                    <span x-text="active + 1"></span> / <span x-text="count"></span>
                </div>
            </div>

            <div class="relative h-[340px] w-full bg-slate-950/70 border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
                @foreach($comunicadosActivos as $index => $comunicado)
                    <div x-show="active === {{ $index }}" 
                         x-transition:enter="transition-all duration-700 ease-in-out"
                         x-transition:enter-start="opacity-0 transform translate-x-8"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:leave="transition-all duration-500 ease-in-out"
                         x-transition:leave-start="opacity-100 transform translate-x-0"
                         x-transition:leave-end="opacity-0 transform -translate-x-8"
                         class="absolute inset-0 w-full h-full flex flex-col md:flex-row items-center justify-between"
                         style="display: none;">
                        
                        <div class="w-full md:w-1/2 h-44 md:h-full bg-slate-950 flex items-center justify-center relative border-b md:border-b-0 md:border-r border-white/5 shrink-0">
                            @if($comunicado->file_type === 'image')
                                <img src="{{ asset('storage/' . $comunicado->file_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center space-y-2">
                                    <i class="fa-regular fa-file-pdf text-6xl text-red-500/80 drop-shadow"></i>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Documento Técnico PDF</p>
                                </div>
                            @endif
                        </div>

                        <div class="w-full md:w-1/2 p-6 sm:p-8 flex flex-col justify-center h-full">
                            <span class="px-2.5 py-1 rounded bg-amber-500/10 text-amber-400 font-mono text-[9px] font-black uppercase border border-amber-500/20 self-start mb-3">
                                Comunicado Activo
                            </span>
                            <h3 class="text-white font-black text-xl sm:text-2xl leading-tight mb-3 drop-shadow">{{ $comunicado->title }}</h3>
                            <p class="text-slate-400 text-xs sm:text-sm font-medium line-clamp-3 mb-6">{{ $comunicado->description ?? 'Sin descripción adicional.' }}</p>
                            
                            <div class="flex items-center gap-4">
                                <a href="{{ asset('storage/' . $comunicado->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-black uppercase tracking-wider shadow-md transition-all">
                                    <i class="fa-solid fa-file-arrow-down"></i> Descargar / Ver
                                </a>
                                <span class="text-slate-500 text-[11px] font-bold"><i class="fa-regular fa-calendar mr-1"></i>Emisión: {{ $comunicado->published_at->format('d/m/Y') }}</span>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            @if($comunicadosActivos->count() > 1)
                <div class="flex justify-center gap-1.5 mt-4">
                    @foreach($comunicadosActivos as $index => $c)
                        <button @click="active = {{ $index }}" class="h-2 rounded-full transition-all duration-300" :class="active === {{ $index }} ? 'bg-amber-500 w-5 shadow-[0_0_8px_#f59e0b]' : 'bg-white/20 w-2'"></button>
                    @endforeach
                </div>
            @endif

        </div>
    </section>
    @endif

    <footer class="bg-slate-950 text-slate-600 py-8 text-center border-t border-white/05 relative z-20">
        <div class="max-w-5xl mx-auto px-4">
            <p class="font-black uppercase tracking-widest text-slate-500 text-[10px]">Dirección Regional de Trabajo y Promoción del Empleo Puno</p>
            <p class="text-xs">&copy; {{ date('Y') }} Todos los derechos reservados.</p>
        </div>
    </footer>
</div>

<div x-data="{ open:false, report:null, photoIndex:0 }" 
     @open-modal.window="report=$event.detail.report;photoIndex=0;open=true;document.body.style.overflow='hidden';"
     x-show="open" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4" 
     style="display:none;"
     x-transition>
    
    <div class="absolute inset-0 bg-slate-950/95 backdrop-blur-lg cursor-pointer" @click="open=false;document.body.style.overflow='';"></div>
    
    <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row z-10" style="height:min(85vh,640px);">
        <div class="w-full md:w-3/5 h-56 md:h-full bg-slate-900 relative flex items-center justify-center flex-shrink-0">
            <template x-if="report&&report.photos&&report.photos.length>0">
                <img :src="'{{ asset('storage') }}/'+report.photos[photoIndex]" class="max-w-full max-h-full object-contain">
            </template>
            <button @click="photoIndex=photoIndex===0?report.photos.length-1:photoIndex-1" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 text-white rounded-full flex items-center justify-center border border-white/20 transition shadow-lg"><i class="fa-solid fa-chevron-left"></i></button>
            <button @click="photoIndex=photoIndex===report.photos.length-1?0:photoIndex+1" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 text-white rounded-full flex items-center justify-center border border-white/20 transition shadow-lg"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        <div class="w-full md:w-2/5 p-6 flex flex-col overflow-y-auto bg-slate-50 relative">
            <button @click="open=false;document.body.style.overflow='';" class="absolute top-4 right-4 w-9 h-9 bg-slate-200 rounded-full hover:bg-red-600 hover:text-white flex items-center justify-center transition"><i class="fa-solid fa-xmark"></i></button>
            <div x-show="report" class="mt-4">
                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase text-white tracking-widest" :class="report?.type==='evento'?'bg-red-600':'bg-blue-600'" x-text="report?.type==='evento'?'Evento Institucional':'Actividad de Difusión'"></span>
                <h3 class="text-2xl font-black text-slate-900 mt-4 mb-4 leading-tight" x-text="report?.title"></h3>
                <p class="text-slate-600 text-sm font-medium" x-text="report?.description"></p>
            </div>
        </div>
    </div>
</div>

<div id="lightbox" class="fixed inset-0 z-[110] bg-slate-950/97 backdrop-blur-xl flex flex-col items-center justify-center">
    <div class="absolute top-0 left-0 w-full p-5 flex justify-between items-center z-50">
        <span id="lb-counter" class="text-white font-bold text-[10px] tracking-widest bg-white/10 px-4 py-2 rounded-full border border-white/15"></span>
        <button id="lb-close" class="w-11 h-11 bg-white/10 hover:bg-red-600 text-white rounded-full flex items-center justify-center border border-white/15 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
    </div>
    <button id="lb-prev" class="absolute left-3 sm:left-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/06 text-white rounded-full flex items-center justify-center border border-white/10 z-50 transition"><i class="fa-solid fa-chevron-left text-xl"></i></button>
    <div class="relative max-w-6xl max-h-[82vh] w-full px-4 sm:px-24 flex items-center justify-center"><img id="lb-img" src="" class="max-w-full max-h-[80vh] object-contain rounded-xl shadow-2xl" style="transition:opacity .2s,transform .2s;"></div>
    <button id="lb-next" class="absolute right-3 sm:right-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/06 text-white rounded-full flex items-center justify-center border border-white/10 z-50 transition"><i class="fa-solid fa-chevron-right text-xl"></i></button>
</div>

<script>
// Sidebar Toggle
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebar-overlay');
document.getElementById('sidebar-toggle')?.addEventListener('click', () => { sidebar.classList.contains('open') ? closeSidebar() : openSidebar(); });
function openSidebar()  { sidebar.classList.add('open');  overlay.classList.add('open');  }
function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('open'); }

// Scroll Help
function scrollToSection(id) { document.getElementById(id)?.scrollIntoView({ behavior:'smooth', block:'start' }); }
function scrollToActivity(id) { document.getElementById(id)?.scrollIntoView({ behavior:'smooth', block:'start' }); }
function scrollToSubEvent(seId, activityIdx) {
    const el = document.getElementById(seId);
    if (!el) return;
    if (el.dataset.isLatest === '1') { _doScrollToEl(el); return; }
    if (isElHidden(el)) {
        const toggleBtn = document.getElementById('expand-toggle-' + activityIdx);
        if (toggleBtn) { toggleBtn.click(); setTimeout(() => _doScrollToEl(el), 520); } 
        else { _doScrollToEl(el); }
    } else { _doScrollToEl(el); }
}
function isElHidden(el) {
    let node = el;
    while (node && node !== document.body) {
        if (window.getComputedStyle(node).display === 'none') return true;
        node = node.parentElement;
    }
    return false;
}
function _doScrollToEl(el) {
    el.scrollIntoView({ behavior:'smooth', block:'center' });
    el.classList.add('highlight-target');
    setTimeout(() => el.classList.remove('highlight-target'), 2800);
}

// Reproducción YouTube
function playVideo(playButton, youtubeId, containerId) {
    const container = document.getElementById(containerId);
    const thumbnail = container.querySelector('.video-thumbnail');
    const iframe = container.querySelector('.video-iframe');
    iframe.src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0`;
    iframe.style.display = 'block';
    thumbnail.style.display = 'none';
    playButton.style.display = 'none';
}

// Expandir Fotografías 4x4
document.querySelectorAll('.btn-mostrar-mas').forEach(btn => {
    btn.addEventListener('click', function () {
        const wrap   = this.closest('.rounded-2xl') || this.closest('.bg-slate-50');
        const grid   = wrap?.querySelector('.galeria-fotos');
        if (!grid) return;
        const extras = grid.querySelectorAll('.foto-extra').length;
        const span   = this.querySelector('span');
        const icon   = this.querySelector('i');
        const show   = grid.classList.toggle('mostrar-todas');
        span.textContent = show ? 'Ocultar fotografías adicionales' : `Ver ${extras} fotografías adicionales`;
        icon.classList.toggle('fa-images', !show);
        icon.classList.toggle('fa-chevron-up', show);
    });
});

// Lightbox Cronología
let gallery=[], lbIdx=0;
const lb    = document.getElementById('lightbox');
const lbImg = document.getElementById('lb-img');
const lbCtr = document.getElementById('lb-counter');

document.querySelectorAll('.foto-galeria').forEach(img => {
    img.addEventListener('click', function () {
        const grid = this.closest('.galeria-fotos');
        gallery    = Array.from(grid.querySelectorAll('.foto-galeria'));
        lbIdx      = gallery.indexOf(this);
        openLB();
    });
});
function updateLB() {
    lbImg.style.opacity = '.4';
    setTimeout(() => {
        lbImg.src          = gallery[lbIdx].src;
        lbCtr.textContent  = `IMAGEN ${lbIdx+1} DE ${gallery.length}`;
        lbImg.style.opacity   = '1';
    }, 160);
}
function openLB()  { updateLB(); lb.classList.add('active'); document.body.style.overflow='hidden'; }
function closeLB() { lb.classList.remove('active'); document.body.style.overflow=''; }
document.getElementById('lb-close').addEventListener('click', closeLB);
document.getElementById('lb-next').addEventListener('click',  ()=>{ lbIdx=(lbIdx+1)%gallery.length; updateLB(); });
document.getElementById('lb-prev').addEventListener('click',  ()=>{ lbIdx=(lbIdx-1+gallery.length)%gallery.length; updateLB(); });

// Alpine JIT AutoSlider (Progreso Exacto de 5 segundos)
document.addEventListener('alpine:init', () => {
    Alpine.data('autoSlider', (items, totalMs) => ({
        items, active:0, progress:0, tick:50,
        init() { if(this.items.length>1) this.start(); },
        start() {
            const step = 100/(totalMs/this.tick);
            setInterval(()=>{
                this.progress += step;
                if(this.progress >= 100){
                    this.progress = 0;
                    this.active   = (this.active+1)%this.items.length;
                }
            }, this.tick);
        }
    }));
});
</script>
</body>
</html>

