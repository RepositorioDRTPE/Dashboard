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
            --red-dim:  #7f1d1d;
            --blue:     #1d4ed8;
            --blue-dim: #1e3a8a;
            --navy:     #0b1120;
            --sidebar-w: 268px;
            --header-h:  68px;
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

        h1, h2, h3, h4, h5 { font-family: 'Sora', sans-serif; }

        /* ─── BACKGROUND SCENE ─── */
        .bg-scene-fixed {
            background-image: url('/images/fondodash2.png');
            background-size: cover;
            background-position: center top;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        /* ─── SIDEBAR ─── */
        #sidebar {
            position: fixed;
            left: 0;
            top: var(--header-h);
            width: var(--sidebar-w);
            height: calc(100vh - var(--header-h));
            background: rgba(7, 10, 22, 0.97);
            border-right: 1px solid rgba(255,255,255,0.07);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 45;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
        }
        #sidebar::-webkit-scrollbar { width: 3px; }
        #sidebar::-webkit-scrollbar-track { background: transparent; }
        #sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 99px; }

        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.65);
            z-index: 44;
        }

        .sidebar-section-title {
            font-family: 'Sora', sans-serif;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.28);
            padding: 18px 16px 7px;
            border-top: 1px solid rgba(255,255,255,0.05);
            margin-top: 4px;
        }

        .sidebar-section-title:first-of-type { border-top: none; margin-top: 0; }

        .sb-item {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 7px 12px;
            border-radius: 10px;
            margin: 0 8px 2px;
            cursor: pointer;
            transition: background .18s, border-color .18s;
            border-left: 2px solid transparent;
        }
        .sb-item:hover { background: rgba(255,255,255,0.06); }
        .sb-item.is-active { background: rgba(220,38,38,.12); border-color: var(--red); }

        .sb-thumb {
            width: 32px; height: 32px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center;
        }
        .sb-thumb img { width: 100%; height: 100%; object-fit: cover; }

        .sb-dot {
            width: 8px; height: 8px;
            border-radius: 3px;
            flex-shrink: 0;
            margin-top: 5px;
        }

        /* Sub-type divider inside sidebar */
        .sb-type-sep {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 16px 4px;
        }
        .sb-type-sep span {
            font-size: 9px; font-weight: 800;
            letter-spacing: .18em; text-transform: uppercase;
            white-space: nowrap;
        }
        .sb-type-sep::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.07);
        }

        /* ─── LAYOUT ─── */
        #main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            transition: margin-left .3s;
        }

        /* ─── SLIDERS ─── */
        .clip-difusion  { clip-path: polygon(0 0, 100% 0, 100% calc(100% - 4.5vw), 0 100%); }
        .clip-eventos   { clip-path: polygon(0 0, 100% 0, 100% 100%, 0 calc(100% - 4.5vw)); }

        .ken-burns { animation: kenBurns 16s ease-out infinite alternate; }
        @keyframes kenBurns {
            from { transform: scale(1); }
            to   { transform: scale(1.14); }
        }

        /* Progress bar BOTTOM of slider (placed inside clip to appear near bottom edge) */
        .slider-bar-wrap {
            position: absolute;
            bottom: 5.5vw;   /* keeps it above the diagonal cut */
            left: 0; right: 0;
            height: 3px;
            background: rgba(255,255,255,0.12);
            z-index: 50;
        }
        .slider-bar-fill {
            height: 100%;
            transition: width 50ms linear;
        }

        /* Dots nav */
        .slider-dot {
            width: 8px; height: 8px;
            border-radius: 99px;
            background: rgba(255,255,255,0.3);
            transition: width .3s, background .3s;
            cursor: pointer; border: none;
        }
        .slider-dot.active { width: 22px; background: #fff; }

        /* ─── GALLERY ─── */
        .foto-extra   { display: none; opacity: 0; transform: scale(.95); transition: all .45s; }
        .mostrar-todas .foto-extra { display: block; opacity: 1; transform: scale(1); animation: fadeInGrid .45s ease forwards; }
        @keyframes fadeInGrid {
            from { opacity: 0; transform: translateY(12px) scale(.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .foto-galeria  { transition: transform .55s; cursor: zoom-in; display: block; }
        .foto-item:hover .foto-galeria { transform: scale(1.05); }

        /* ─── VIDEO ─── */
        .video-preview-container { position: relative; overflow: hidden; cursor: pointer; transition: all .3s; }
        .video-preview-container:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(0,0,0,.35); }
        .play-btn {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%,-50%);
            width: 62px; height: 62px;
            background: rgba(220,38,38,.88);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            transition: all .35s;
            animation: pulseRed 2s infinite;
        }
        .video-preview-container:hover .play-btn {
            background: #ef4444;
            transform: translate(-50%,-50%) scale(1.12);
            animation: none;
        }
        @keyframes pulseRed {
            0%   { box-shadow: 0 0 0 0 rgba(239,68,68,.7); }
            70%  { box-shadow: 0 0 0 14px rgba(239,68,68,0); }
            100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
        }

        /* ─── LIGHTBOX ─── */
        #lightbox { opacity: 0; visibility: hidden; transition: opacity .35s, visibility .35s; }
        #lightbox.active { opacity: 1; visibility: visible; }

        /* ─── ACTIVITY CARD (general) ─── */
        .activity-header-card {
            background: linear-gradient(135deg, #0f2167 0%, #1e3a8a 60%, #1d4ed8 100%);
            border-left: 5px solid #f59e0b;
            border-radius: 18px;
            position: relative;
            overflow: hidden;
        }
        .activity-header-card::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 120px; height: 120px;
            background: rgba(245,158,11,.08);
            border-radius: 50%;
        }

        /* ─── TIMELINE LINE ─── */
        .timeline-rail {
            border-left: 2px solid rgba(96,165,250,.25);
        }
        .timeline-node {
            position: absolute;
            left: calc(-1rem - 10px);
            top: 1.75rem;
            width: 18px; height: 18px;
            background: #3b82f6;
            border: 3px solid rgba(30,58,138,.8);
            border-radius: 5px;
            box-shadow: 0 0 12px rgba(96,165,250,.5);
            transition: all .25s;
            z-index: 2;
        }
        .reporte-wrapper:hover .timeline-node {
            background: var(--red);
            transform: rotate(45deg);
            box-shadow: 0 0 14px rgba(220,38,38,.5);
        }

        /* ─── HIGHLIGHT PULSE (scroll target) ─── */
        @keyframes highlightPulse {
            0%   { box-shadow: 0 0 0 0 rgba(220,38,38,.55); }
            60%  { box-shadow: 0 0 0 22px rgba(220,38,38,0); }
            100% { box-shadow: none; }
        }
        .highlight-target { animation: highlightPulse 2s ease; }

        /* ─── SECTION BACKGROUNDS ─── */
        .section-transparent-over-bg {
            position: relative;
        }
        .section-transparent-over-bg > .bg-overlay {
            position: absolute; inset: 0;
            background: rgba(11,17,32,.82);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        .section-transparent-over-bg > .content-z {
            position: relative; z-index: 2;
        }

        .section-dark-glass {
            background: rgba(5,9,22,.92);
            border-top: 1px solid rgba(255,255,255,.06);
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        /* ─── LATEST RECORDS ─── */
        .record-card {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 18px;
            overflow: hidden;
            transition: background .2s, border-color .2s, transform .2s, box-shadow .2s;
            cursor: pointer;
            display: block;
            text-decoration: none;
        }
        .record-card:hover {
            background: rgba(255,255,255,.08);
            border-color: rgba(255,255,255,.2);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,.4);
        }

        /* ─── FOOTER (light) ─── */
        .footer-light {
            background: #f0f4f8;
            color: #1e293b;
        }
        .footer-light h4 { color: #1e293b; }

        /* ─── SOCIAL BADGES ─── */
        .social-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 13px;
            border-radius: 9px;
            font-size: 11px; font-weight: 700;
            transition: all .18s;
            text-decoration: none;
            white-space: nowrap;
        }
        .social-badge:hover { transform: translateY(-2px); filter: brightness(1.1); }
        .badge-fb   { background: #1877f2; color: #fff; }
        .badge-yt   { background: #ff0000; color: #fff; }
        .badge-tt   { background: #111; color: #fff; border: 1px solid rgba(255,255,255,.15); }

        /* ─── SUBEVENT CARD ─── */
        .subevent-card {
            background: rgba(255,255,255,.96);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,.18);
            transition: box-shadow .25s;
        }
        .subevent-card:hover { box-shadow: 0 8px 36px rgba(0,0,0,.28); }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 1023px) {
            #sidebar     { transform: translateX(-100%); }
            #main-content { margin-left: 0 !important; }
        }
        @media (max-width: 1023px) {
            #sidebar.open { transform: translateX(0); }
            #sidebar-overlay.open { display: block; }
        }
    </style>
</head>

<body class="antialiased selection:bg-red-700 selection:text-white">

@php
    $photoReports   = $photoReports ?? collect();
    $difusiones     = $photoReports->where('type', 'difusion')->values();
    $institucionales = $photoReports->where('type', 'evento')->values();

    $todosSubEventos = collect();
    foreach ($actividades as $act) {
        foreach ($act->subEvents as $se) {
            $se->category_name      = $act->category->name ?? 'General';
            $se->parent_description = $act->description;
            $allPhotos = is_string($se->photos) ? json_decode($se->photos, true) : ($se->photos ?? []);
            $se->photos_arr = is_array($allPhotos) ? $allPhotos : [];
            $se->cover     = count($se->photos_arr) > 0 ? $se->photos_arr[0] : null;
            $todosSubEventos->push($se);
        }
    }
    /* Últimos 3 SÓLO con foto */
    $ultimos3 = $todosSubEventos
        ->filter(fn($s) => $s->cover !== null)
        ->sortByDesc('event_date')
        ->take(3)
        ->values();
@endphp

<!-- ════════════════════════════════════════════ HEADER ════════════ -->
<header id="main-header"
        class="fixed top-0 left-0 right-0 z-50 bg-slate-950/95 backdrop-blur-xl border-b border-white/08 shadow-2xl"
        style="height:var(--header-h);">
    <div class="h-full px-5 lg:pl-5 flex items-center justify-between gap-4">

        <!-- Left: toggle + brand -->
        <div class="flex items-center gap-3">
            <button id="sidebar-toggle"
                    class="lg:hidden w-10 h-10 rounded-xl bg-white/08 hover:bg-white/14 border border-white/10 flex items-center justify-center transition">
                <i class="fa-solid fa-bars text-white text-sm"></i>
            </button>

            <div class="bg-white/10 p-1.5 rounded-xl border border-white/15 shadow-lg">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-9 h-9 object-contain">
            </div>

            <div class="hidden sm:block">
                <p class="text-white font-black text-base leading-tight tracking-tight" style="font-family:'Sora',sans-serif;">
                    Portal Oficial de Actividades
                </p>
                <p class="text-slate-500 text-[10px] font-semibold uppercase tracking-widest">DTPE Puno · Perú</p>
            </div>
        </div>

        <!-- Right: login -->
        <a href="{{ route('login') }}"
           class="flex items-center gap-2 bg-white/08 hover:bg-red-700/70 border border-white/10 hover:border-red-500/40 px-5 py-2 rounded-xl text-xs font-bold tracking-wide text-white transition-all hover:shadow-lg">
            <i class="fa-solid fa-lock text-red-400 text-sm"></i>
            <span class="hidden sm:inline">Acceso Interno</span>
        </a>
    </div>
</header>

<!-- ════════════════════════════════════════════ SIDEBAR ══════════ -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<aside id="sidebar">

    <!-- ── LOGO + CONTACTO ── -->
    <div class="p-4 pb-5">
        <div class="flex items-center gap-3 mb-5 pt-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-11 h-11 object-contain rounded-xl bg-white/08 p-1.5 border border-white/10">
            <div>
                <p class="text-white font-black text-sm leading-tight" style="font-family:'Sora',sans-serif;">DTPE Puno</p>
                <p class="text-slate-500 text-[10px] uppercase tracking-wider">Dirección Regional</p>
            </div>
        </div>

        <!-- Addresses -->
        <div class="space-y-3 mb-4">
            <div class="flex items-start gap-2">
                <div class="w-6 h-6 rounded-lg bg-red-600/20 border border-red-500/25 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-location-dot text-red-400 text-[10px]"></i>
                </div>
                <div>
                    <p class="text-slate-200 text-[11px] font-bold">Sede Puno</p>
                    <p class="text-slate-500 text-[10px] leading-snug">Jr. Ayacucho N° 658, Puno</p>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <div class="w-6 h-6 rounded-lg bg-blue-600/20 border border-blue-500/25 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-location-dot text-blue-400 text-[10px]"></i>
                </div>
                <div>
                    <p class="text-slate-200 text-[11px] font-bold">Sede Juliaca</p>
                    <p class="text-slate-500 text-[10px] leading-snug">Jr. Amazonas N° 2000, Juliaca</p>
                </div>
            </div>
        </div>

        <!-- Social links -->
        <div class="flex gap-1.5 flex-wrap">
            <a href="https://www.facebook.com/GobiernoRegionalPuno" target="_blank" class="social-badge badge-fb">
                <i class="fa-brands fa-facebook text-sm"></i> Facebook
            </a>
            <a href="#" target="_blank" class="social-badge badge-yt">
                <i class="fa-brands fa-youtube text-sm"></i> YouTube
            </a>
            <a href="#" target="_blank" class="social-badge badge-tt">
                <i class="fa-brands fa-tiktok text-sm"></i> TikTok
            </a>
        </div>
    </div>

    <!-- ── REPORTES FOTOGRÁFICOS ── -->
    <p class="sidebar-section-title"><i class="fa-solid fa-images mr-1.5 text-blue-400"></i>Reportes Fotográficos</p>

    @if($difusiones->count())
        <div class="sb-type-sep"><span class="text-blue-400">Difusión</span></div>
        @foreach($difusiones->take(5) as $dif)
            <div class="sb-item" onclick="window.dispatchEvent(new CustomEvent('open-modal', {detail:{report:{{ $dif->toJson() }}}}))">
                <div class="sb-thumb">
                    @if(isset($dif->photos[0]))
                        <img src="{{ asset('storage/'.$dif->photos[0]) }}" alt="">
                    @else
                        <i class="fa-solid fa-image text-slate-600 text-xs"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-200 text-[11px] font-semibold leading-snug truncate">{{ $dif->title }}</p>
                    <p class="text-blue-400 text-[9px] mt-0.5 font-bold uppercase tracking-wider">Difusión</p>
                </div>
            </div>
        @endforeach
    @endif

    @if($institucionales->count())
        <div class="sb-type-sep mt-2"><span class="text-red-400">Institucional</span></div>
        @foreach($institucionales->take(5) as $inst)
            <div class="sb-item" onclick="window.dispatchEvent(new CustomEvent('open-modal', {detail:{report:{{ $inst->toJson() }}}}))">
                <div class="sb-thumb">
                    @if(isset($inst->photos[0]))
                        <img src="{{ asset('storage/'.$inst->photos[0]) }}" alt="">
                    @else
                        <i class="fa-solid fa-image text-slate-600 text-xs"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-200 text-[11px] font-semibold leading-snug truncate">{{ $inst->title }}</p>
                    <p class="text-red-400 text-[9px] mt-0.5 font-bold uppercase tracking-wider">Institucional</p>
                </div>
            </div>
        @endforeach
    @endif

    <!-- ── ACTIVIDADES REGISTRADAS ── -->
    <p class="sidebar-section-title"><i class="fa-solid fa-timeline mr-1.5 text-amber-400"></i>Actividades Registradas</p>

    @foreach($actividades->take(10) as $idx => $act)
        @if($act->subEvents->count() > 0)
            <div class="sb-item" onclick="scrollToActivity('actividad-{{ $idx }}')">
                <div class="sb-dot bg-amber-400 mt-1.5"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-200 text-[11px] font-semibold leading-snug line-clamp-2">{{ $act->description }}</p>
                    <p class="text-slate-500 text-[9px] mt-0.5">
                        <span class="text-amber-400 font-bold">PP:{{ $act->category->pp_code ?? '—' }}</span>
                        &nbsp;·&nbsp;{{ $act->subEvents->count() }} registro(s)
                    </p>
                </div>
            </div>
        @endif
    @endforeach

    @if($actividades->count() > 10)
        <p class="text-slate-600 text-[9px] px-6 pb-2 mt-1">+{{ $actividades->count() - 10 }} más en cronología</p>
    @endif

    <div class="h-8"></div>
</aside>

<!-- ════════════════════════════════════════════ MAIN CONTENT ═════ -->
<div id="main-content" style="padding-top:var(--header-h);">

    <!-- ─── SLIDERS (background shows through) ──────────────────── -->
    <div class="bg-scene-fixed relative">

        {{-- ══ DIFUSIÓN ══ --}}
        @if($difusiones->count() > 0)
        <section class="relative w-full overflow-hidden bg-blue-950/72 backdrop-blur-sm clip-difusion pb-[4.5vw] drop-shadow-2xl"
                 style="height: clamp(380px, 65vh, 700px);"
                 x-data="autoSlider({{ $difusiones->toJson() }}, 5000)">

            <!-- Badge + Dots (top-left) -->
            <div class="absolute top-5 left-6 z-30 flex items-center gap-3 flex-wrap">
                <span class="bg-blue-600/85 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full border border-blue-400/35 shadow-lg">
                    <i class="fa-solid fa-radio mr-1.5"></i> Actividades de Difusión
                </span>
                <div class="flex gap-1.5 items-center">
                    <template x-for="(item, i) in items" :key="i">
                        <button @click="active = i; progress = 0"
                                :class="active===i ? 'slider-dot active' : 'slider-dot'"></button>
                    </template>
                </div>
            </div>

            <!-- Slides -->
            <template x-for="(item, index) in items" :key="index">
                <div x-show="active === index"
                     x-transition:enter="transition-opacity duration-700"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-700"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 cursor-pointer group"
                     @click="$dispatch('open-modal', { report: item })">

                    <img :src="'{{ asset('storage') }}/' + (item.photos[0] || '')"
                         class="w-full h-full object-cover ken-burns" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-950 via-blue-900/35 to-blue-950/10"></div>

                    <div class="absolute bottom-[12vw] sm:bottom-[8vw] left-6 sm:left-12 max-w-2xl">
                        <div class="bg-blue-900/45 backdrop-blur-md border border-blue-400/20 rounded-2xl p-5 sm:p-7">
                            <h2 class="text-2xl sm:text-4xl font-black text-white leading-tight group-hover:text-blue-200 transition-colors"
                                x-text="item.title"></h2>
                            <p class="text-blue-300/80 mt-2 text-xs font-medium flex items-center gap-1.5">
                                <i class="fa-solid fa-hand-pointer animate-pulse"></i>
                                Presione para ver descripción y galería
                            </p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Progress bar at bottom (above clip cut) -->
            <div class="slider-bar-wrap">
                <div class="slider-bar-fill bg-blue-400 shadow-[0_0_8px_#60a5fa]"
                     :style="'width:'+progress+'%'"></div>
            </div>
        </section>
        @endif

        {{-- ══ INSTITUCIONAL ══ --}}
        @if($institucionales->count() > 0)
        <section class="relative w-full overflow-hidden bg-red-950/72 backdrop-blur-sm clip-eventos pb-[4.5vw] -mt-[4.5vw] drop-shadow-2xl"
                 style="height: clamp(380px, 65vh, 700px);"
                 x-data="autoSlider({{ $institucionales->toJson() }}, 5000)">

            <!-- Badge + Dots (top-right) -->
            <div class="absolute z-30 flex flex-col items-end gap-2" style="top: calc(5vw + 12px); right: 1.5rem;">
                <span class="bg-red-600/85 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full border border-red-400/35 shadow-lg">
                    <i class="fa-solid fa-calendar-star mr-1.5"></i> Eventos Institucionales
                </span>
                <div class="flex gap-1.5 items-center justify-end">
                    <template x-for="(item, i) in items" :key="i">
                        <button @click="active = i; progress = 0"
                                :class="active===i ? 'slider-dot active' : 'slider-dot'"></button>
                    </template>
                </div>
            </div>

            <!-- Slides -->
            <template x-for="(item, index) in items" :key="index">
                <div x-show="active === index"
                     x-transition:enter="transition-opacity duration-700"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-700"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 cursor-pointer group"
                     @click="$dispatch('open-modal', { report: item })">

                    <img :src="'{{ asset('storage') }}/' + (item.photos[0] || '')"
                         class="w-full h-full object-cover ken-burns" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-red-950 via-red-900/35 to-red-950/10"></div>

                    <div class="absolute bottom-[12vw] sm:bottom-[8vw] left-6 sm:left-12 max-w-2xl">
                        <div class="bg-red-900/45 backdrop-blur-md border border-red-400/20 rounded-2xl p-5 sm:p-7">
                            <h2 class="text-2xl sm:text-4xl font-black text-white leading-tight group-hover:text-red-200 transition-colors"
                                x-text="item.title"></h2>
                            <p class="text-red-300/80 mt-2 text-xs font-medium flex items-center gap-1.5">
                                <i class="fa-solid fa-hand-pointer animate-pulse"></i>
                                Presione para ver descripción y galería
                            </p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Progress bar at bottom (above clip cut) -->
            <div class="slider-bar-wrap" style="bottom:5.5vw;">
                <div class="slider-bar-fill bg-red-400 shadow-[0_0_8px_#f87171]"
                     :style="'width:'+progress+'%'"></div>
            </div>
        </section>
        @endif

    </div><!-- /bg-scene-fixed -->

    <!-- ─── ÚLTIMOS REGISTROS ──────────────────────────────────── -->
    @if($ultimos3->count() > 0)
    <div class="section-dark-glass py-14 -mt-[1px]">
        <section class="max-w-6xl mx-auto px-6 lg:px-10">

            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-900/40 flex-shrink-0">
                    <i class="fa-solid fa-bolt text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white">Últimos Registros</h2>
                    <p class="text-slate-500 text-xs">Actividades recientes con evidencia fotográfica · clic para ir al registro</p>
                </div>
                <div class="flex-1 h-px bg-gradient-to-r from-red-700/50 to-transparent hidden sm:block"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach($ultimos3 as $se)
                <div class="record-card" onclick="scrollToSubEvent('subevent-{{ $se->id }}')">
                    <div class="relative overflow-hidden" style="height:160px;">
                        <img src="{{ asset('storage/'.$se->cover) }}"
                             class="w-full h-full object-cover transition-transform duration-700 hover:scale-110"
                             loading="lazy" alt="{{ $se->report_title }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 to-transparent opacity-75"></div>
                        <div class="absolute top-3 left-3">
                            <span class="bg-red-600 text-white text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-wider shadow">
                                {{ $se->category_name }}
                            </span>
                        </div>
                        <div class="absolute bottom-3 right-3">
                            <span class="bg-black/40 backdrop-blur-sm text-slate-200 text-[9px] font-bold px-2 py-1 rounded-md border border-white/15">
                                <i class="fa-regular fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($se->event_date)->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-black text-slate-100 leading-snug line-clamp-2">{{ $se->report_title }}</h3>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-[10px] text-slate-500 font-medium">
                                <i class="fa-solid fa-users text-blue-400 mr-1"></i>{{ $se->attendees_count }} asistentes
                            </span>
                            <span class="text-[10px] text-red-400 font-bold flex items-center gap-1 transition-all">
                                Ver en cronología <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>
    @endif

    <!-- ─── CRONOLOGÍA (background shows through) ───────────────── -->
    <div class="bg-scene-fixed relative">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-[2px]"></div>

        <section class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 py-16" x-data="{ limit: 10 }">

            <!-- Section header -->
            <div class="mb-12">
                <div class="inline-flex items-center gap-3 bg-white/05 backdrop-blur-md border border-white/09 rounded-2xl px-6 py-4">
                    <div class="w-9 h-9 bg-red-700/80 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-timeline text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-white">Cronología Operativa</h2>
                        <p class="text-slate-500 text-[11px] mt-0.5">Historial detallado de cumplimiento de metas y reportes institucionales</p>
                    </div>
                </div>
            </div>

            <!-- Activities loop -->
            <div class="space-y-10">
                @foreach($actividades as $index => $actividad)
                @if($actividad->subEvents->count() > 0)
                @php
                    $sortedSub = $actividad->subEvents->sortByDesc('event_date')->values();
                    $latestSub = $sortedSub->first();
                    $restSub   = $sortedSub->skip(1)->values();

                    // Resolve photos for latest
                    $lPhotos = is_string($latestSub->photos) ? json_decode($latestSub->photos, true) : ($latestSub->photos ?? []);
                    $lPhotos = is_array($lPhotos) ? $lPhotos : [];
                    $lPriority = $latestSub->photo_priority ?? [];
                    if (!empty($lPriority) && count($lPriority) === count($lPhotos)) {
                        $combined = array_combine($lPriority, $lPhotos);
                        ksort($combined);
                        $lPhotos = array_values($combined);
                    }
                @endphp

                <article id="actividad-{{ $index }}"
                         x-show="{{ $index }} < limit"
                         x-transition.opacity.duration.500ms
                         style="display:{{ $index < 10 ? 'block' : 'none' }}">

                    <!-- ╔══ ACTIVITY HEADER (general) ══╗ -->
                    <div class="activity-header-card p-5 shadow-xl mb-0">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 relative z-10">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-amber-400/20 border border-amber-400/30 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-folder-open text-amber-400 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-blue-300 text-[9px] font-black uppercase tracking-widest mb-0.5">Actividad General</p>
                                    <h3 class="text-base sm:text-lg font-black text-white leading-snug">{{ $actividad->description }}</h3>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                                <span class="bg-amber-400/18 border border-amber-400/30 text-amber-300 text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest whitespace-nowrap">
                                    PP: {{ $actividad->category->pp_code ?? '000' }}
                                </span>
                                <span class="bg-white/10 border border-white/15 text-blue-200 text-[9px] font-bold px-3 py-1.5 rounded-lg whitespace-nowrap">
                                    {{ $actividad->subEvents->count() }} {{ $actividad->subEvents->count() === 1 ? 'registro' : 'registros' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- ╔══ TIMELINE ══╗ -->
                    <div class="relative timeline-rail ml-6 sm:ml-8 pl-7 sm:pl-10 space-y-5 pt-5 pb-3"
                         x-data="{ expanded: false }">

                        <!-- LATEST sub-event (always visible) -->
                        <div id="subevent-{{ $latestSub->id }}" class="relative reporte-wrapper group">
                            <div class="timeline-node"></div>

                            <div class="subevent-card">
                                <!-- Latest badge -->
                                <div class="bg-gradient-to-r from-red-600 to-red-700 px-5 py-2 flex items-center gap-2">
                                    <i class="fa-solid fa-star text-amber-300 text-xs"></i>
                                    <span class="text-white text-[10px] font-black uppercase tracking-widest">Registro más reciente</span>
                                    <div class="ml-auto text-white/60 text-[10px] font-medium">{{ \Carbon\Carbon::parse($latestSub->event_date)->format('d M. Y') }}</div>
                                </div>

                                <div class="p-5 sm:p-7">
                                    <div class="flex flex-wrap items-center gap-3 mb-4">
                                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                                            <i class="fa-regular fa-calendar text-red-600 mr-1"></i>
                                            {{ \Carbon\Carbon::parse($latestSub->event_date)->format('d M. Y') }}
                                        </span>
                                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200">
                                            <i class="fa-solid fa-users mr-1"></i>{{ $latestSub->attendees_count }} Asistentes
                                        </span>
                                    </div>

                                    <h4 class="text-xl sm:text-2xl font-black text-slate-900 mb-4 leading-tight">{{ $latestSub->report_title }}</h4>

                                    @if($latestSub->comment)
                                    <div class="bg-slate-50 border-l-4 border-slate-300 rounded-r-xl p-4 mb-5">
                                        <p class="text-slate-700 text-sm leading-relaxed font-medium">{{ $latestSub->comment }}</p>
                                    </div>
                                    @endif

                                    @if(count($lPhotos) > 0)
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                        <div class="grid grid-cols-2 gap-2 sm:gap-3 galeria-fotos">
                                            @foreach($lPhotos as $pi => $foto)
                                            <div class="foto-item relative overflow-hidden bg-slate-200 rounded-xl {{ $pi >= 4 ? 'foto-extra' : '' }} border-2 border-white shadow-sm">
                                                <img src="{{ asset('storage/'.$foto) }}"
                                                     class="foto-galeria w-full h-36 sm:h-52 object-cover"
                                                     loading="lazy" alt="Foto {{ $pi+1 }}">
                                            </div>
                                            @endforeach
                                        </div>
                                        @if(count($lPhotos) > 4)
                                        <button type="button" class="btn-mostrar-mas mt-3 w-full py-3 bg-white border border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-50 transition flex items-center justify-center gap-2 shadow-sm">
                                            <i class="fa-solid fa-images text-red-500"></i>
                                            <span>Ver {{ count($lPhotos) - 4 }} fotografías adicionales</span>
                                        </button>
                                        @endif
                                    </div>
                                    @endif

                                    @if($latestSub->youtube_url)
                                    @php
                                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $latestSub->youtube_url, $matches);
                                        $ytId = $matches[1] ?? null;
                                    @endphp
                                    @if($ytId)
                                    <div class="mt-5 video-preview-container rounded-2xl shadow-lg border-4 border-slate-100 bg-slate-900 overflow-hidden"
                                         id="video-container-{{ $latestSub->id }}">
                                        <img src="https://img.youtube.com/vi/{{ $ytId }}/maxresdefault.jpg"
                                             onerror="this.src='https://img.youtube.com/vi/{{ $ytId }}/hqdefault.jpg'"
                                             class="video-thumbnail w-full h-52 sm:h-72 object-cover opacity-90" loading="lazy">
                                        <div class="play-btn"
                                             onclick="playVideo(this,'{{ $ytId }}','video-container-{{ $latestSub->id }}')">
                                            <svg class="w-9 h-9 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                        <iframe class="video-iframe w-full h-52 sm:h-72 rounded-xl" style="display:none;"
                                                allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- /LATEST -->

                        <!-- REST of sub-events (collapsible) -->
                        @if($restSub->count() > 0)

                        <!-- Toggle button -->
                        <button @click="expanded = !expanded"
                                class="w-full py-2.5 flex items-center justify-center gap-2 rounded-xl border text-xs font-bold uppercase tracking-wide transition-all"
                                :class="expanded
                                    ? 'bg-slate-700/40 border-slate-600/40 text-slate-300 hover:bg-slate-600/40'
                                    : 'bg-white/06 border-white/10 text-slate-400 hover:bg-white/10 hover:text-slate-200 hover:border-white/20'">
                            <i class="fa-solid transition-transform duration-300"
                               :class="expanded ? 'fa-chevron-up' : 'fa-list-ul'"></i>
                            <span x-text="expanded
                                ? 'Ocultar registros anteriores'
                                : 'Ver {{ $restSub->count() }} registro(s) anterior(es) de esta actividad'">
                            </span>
                        </button>

                        <!-- Collapsible content -->
                        <div x-show="expanded"
                             x-transition:enter="transition-all duration-400"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition-all duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="space-y-5">

                            @foreach($restSub as $reporte)
                            @php
                                $rPhotos = is_string($reporte->photos) ? json_decode($reporte->photos, true) : ($reporte->photos ?? []);
                                $rPhotos = is_array($rPhotos) ? $rPhotos : [];
                                $rPriority = $reporte->photo_priority ?? [];
                                if (!empty($rPriority) && count($rPriority) === count($rPhotos)) {
                                    $c2 = array_combine($rPriority, $rPhotos);
                                    ksort($c2);
                                    $rPhotos = array_values($c2);
                                }
                            @endphp

                            <div id="subevent-{{ $reporte->id }}" class="relative reporte-wrapper group">
                                <div class="timeline-node" style="background:#475569;"></div>

                                <div class="subevent-card border border-slate-100">
                                    <div class="p-5 sm:p-7">
                                        <div class="flex flex-wrap items-center gap-3 mb-4">
                                            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                                                <i class="fa-regular fa-calendar text-slate-500 mr-1"></i>
                                                {{ \Carbon\Carbon::parse($reporte->event_date)->format('d M. Y') }}
                                            </span>
                                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200">
                                                <i class="fa-solid fa-users mr-1"></i>{{ $reporte->attendees_count }} Asistentes
                                            </span>
                                        </div>

                                        <h4 class="text-lg sm:text-xl font-black text-slate-800 mb-4 leading-snug">{{ $reporte->report_title }}</h4>

                                        @if($reporte->comment)
                                        <div class="bg-slate-50 border-l-4 border-slate-200 rounded-r-xl p-4 mb-5">
                                            <p class="text-slate-600 text-sm leading-relaxed font-medium">{{ $reporte->comment }}</p>
                                        </div>
                                        @endif

                                        @if(count($rPhotos) > 0)
                                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                            <div class="grid grid-cols-2 gap-2 sm:gap-3 galeria-fotos">
                                                @foreach($rPhotos as $pi => $foto)
                                                <div class="foto-item relative overflow-hidden bg-slate-200 rounded-xl {{ $pi >= 4 ? 'foto-extra' : '' }} border-2 border-white shadow-sm">
                                                    <img src="{{ asset('storage/'.$foto) }}"
                                                         class="foto-galeria w-full h-36 sm:h-52 object-cover"
                                                         loading="lazy" alt="Foto {{ $pi+1 }}">
                                                </div>
                                                @endforeach
                                            </div>
                                            @if(count($rPhotos) > 4)
                                            <button type="button" class="btn-mostrar-mas mt-3 w-full py-3 bg-white border border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-50 transition flex items-center justify-center gap-2 shadow-sm">
                                                <i class="fa-solid fa-images text-red-500"></i>
                                                <span>Ver {{ count($rPhotos) - 4 }} fotografías adicionales</span>
                                            </button>
                                            @endif
                                        </div>
                                        @endif

                                        @if($reporte->youtube_url)
                                        @php
                                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $reporte->youtube_url, $matches2);
                                            $ytId2 = $matches2[1] ?? null;
                                        @endphp
                                        @if($ytId2)
                                        <div class="mt-5 video-preview-container rounded-2xl shadow-lg border-4 border-slate-100 bg-slate-900 overflow-hidden"
                                             id="video-container-{{ $reporte->id }}">
                                            <img src="https://img.youtube.com/vi/{{ $ytId2 }}/maxresdefault.jpg"
                                                 onerror="this.src='https://img.youtube.com/vi/{{ $ytId2 }}/hqdefault.jpg'"
                                                 class="video-thumbnail w-full h-52 sm:h-72 object-cover opacity-90" loading="lazy">
                                            <div class="play-btn"
                                                 onclick="playVideo(this,'{{ $ytId2 }}','video-container-{{ $reporte->id }}')">
                                                <svg class="w-9 h-9 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                            <iframe class="video-iframe w-full h-52 sm:h-72 rounded-xl" style="display:none;"
                                                    allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div><!-- /collapsible -->
                        @endif
                        <!-- /REST -->

                    </div><!-- /timeline rail -->
                </article>
                @endif
                @endforeach
            </div><!-- /activities -->

            <!-- Load more -->
            @if(count($actividades) > 10)
            <div class="text-center mt-14" x-show="limit < {{ count($actividades) }}">
                <button @click="limit += 10"
                        class="bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest px-10 py-4 rounded-full
                               shadow-[0_10px_30px_rgba(220,38,38,.3)] hover:-translate-y-1 transition-all border border-red-500/30">
                    <i class="fa-solid fa-plus mr-2"></i> Cargar 10 actividades más
                </button>
            </div>
            @endif

        </section>
    </div>
    <!-- /Cronología -->

    <!-- ─── FOOTER INFORMATIVO (fondo claro) ────────────────────── -->
    <section class="footer-light border-t border-slate-300">
        <div class="max-w-7xl mx-auto py-16 px-6 lg:px-12">

            <div class="flex items-center gap-4 mb-10">
                <h2 class="text-2xl font-black text-slate-800">Medios e Información</h2>
                <div class="flex-1 h-px bg-slate-300"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Facebook (4 cols) -->
                <div class="lg:col-span-4">
                    <h4 class="text-xs font-black text-slate-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fa-brands fa-facebook text-blue-600 text-base"></i> Facebook
                    </h4>
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-slate-200" style="height:480px;">
                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FGobiernoRegionalPuno&tabs=timeline&width=340&height=480&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true&appId"
                                width="100%" height="480"
                                style="border:none;overflow:hidden;" scrolling="no" frameborder="0"
                                allowfullscreen allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                    </div>
                </div>

                <!-- Boletines + Contacto (8 cols, with left separator) -->
                <div class="lg:col-span-8 lg:pl-8 lg:border-l lg:border-slate-300">
                    <h4 class="text-xs font-black text-slate-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-newspaper text-red-600 text-base"></i> Boletines Informativos
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <!-- Boletín 001 -->
                        <div class="bg-white border border-slate-200 rounded-2xl flex flex-col items-center justify-center p-8 shadow-sm hover:shadow-lg transition group cursor-pointer hover:-translate-y-1">
                            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mb-3 border border-red-100 group-hover:bg-red-100 transition">
                                <i class="fa-solid fa-file-pdf text-2xl text-red-500"></i>
                            </div>
                            <p class="text-slate-800 font-bold text-sm">Boletín 001</p>
                            <p class="text-slate-400 text-xs mt-1">Próximamente disponible</p>
                        </div>

                        <!-- Boletín 002 -->
                        <div class="bg-white border border-slate-200 rounded-2xl flex flex-col items-center justify-center p-8 shadow-sm hover:shadow-lg transition group cursor-pointer hover:-translate-y-1">
                            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mb-3 border border-red-100 group-hover:bg-red-100 transition">
                                <i class="fa-solid fa-file-pdf text-2xl text-red-500"></i>
                            </div>
                            <p class="text-slate-800 font-bold text-sm">Boletín 002</p>
                            <p class="text-slate-400 text-xs mt-1">Próximamente disponible</p>
                        </div>
                    </div>

                    <!-- Contact card inside boletines area -->
                    <div class="bg-slate-800 rounded-2xl p-6 shadow-lg">
                        <h5 class="text-white font-black text-xs uppercase tracking-wider mb-5 flex items-center gap-2">
                            <i class="fa-solid fa-headset text-red-400"></i> Contáctenos
                        </h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                            <div class="flex items-start gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-red-600/20 border border-red-500/25 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-location-dot text-red-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-slate-200 text-xs font-bold">Sede Puno</p>
                                    <p class="text-slate-500 text-xs leading-snug mt-0.5">Jr. Ayacucho N° 658, Puno</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-blue-600/20 border border-blue-500/25 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-location-dot text-blue-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-slate-200 text-xs font-bold">Sede Juliaca</p>
                                    <p class="text-slate-500 text-xs leading-snug mt-0.5">Jr. Amazonas N° 2000, Juliaca</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            <a href="https://www.facebook.com/GobiernoRegionalPuno" target="_blank" class="social-badge badge-fb">
                                <i class="fa-brands fa-facebook text-base"></i> Facebook
                            </a>
                            <a href="#" target="_blank" class="social-badge badge-yt">
                                <i class="fa-brands fa-youtube text-base"></i> YouTube
                            </a>
                            <a href="#" target="_blank" class="social-badge badge-tt">
                                <i class="fa-brands fa-tiktok text-base"></i> TikTok
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ─── FOOTER BOTTOM ───────────────────────────────────────── -->
    <footer class="bg-slate-950 text-slate-600 py-8 text-center border-t border-white/05">
        <div class="max-w-5xl mx-auto px-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-7 h-7 object-contain mx-auto mb-3 opacity-30">
            <p class="font-black uppercase tracking-widest text-slate-500 text-[10px] mb-1">
                Dirección Regional de Trabajo y Promoción del Empleo Puno
            </p>
            <p class="text-xs">&copy; {{ date('Y') }} Todos los derechos reservados.</p>
        </div>
    </footer>

</div><!-- /main-content -->


<!-- ════════════════════════════════════════════ PHOTO REPORT MODAL -->
<div x-data="{ open: false, report: null, photoIndex: 0 }"
     @open-modal.window="report = $event.detail.report; photoIndex = 0; open = true; document.body.style.overflow = 'hidden';"
     x-show="open"
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
     style="display:none;">

    <div class="absolute inset-0 bg-slate-950/95 backdrop-blur-lg"
         @click="open = false; document.body.style.overflow = '';"></div>

    <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row"
         style="height: min(85vh, 640px);" x-transition>

        <!-- Image pane -->
        <div class="w-full md:w-3/5 h-56 md:h-full bg-slate-900 relative flex items-center justify-center border-r border-slate-100 flex-shrink-0">
            <template x-if="report && report.photos && report.photos.length > 0">
                <img :src="'{{ asset('storage') }}/' + report.photos[photoIndex]"
                     class="max-w-full max-h-full object-contain drop-shadow-2xl">
            </template>
            <button @click="photoIndex = photoIndex === 0 ? report.photos.length - 1 : photoIndex - 1"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-red-600 text-white rounded-full flex items-center justify-center border border-white/20 transition shadow-lg">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </button>
            <button @click="photoIndex = photoIndex === report.photos.length - 1 ? 0 : photoIndex + 1"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-red-600 text-white rounded-full flex items-center justify-center border border-white/20 transition shadow-lg">
                <i class="fa-solid fa-chevron-right text-sm"></i>
            </button>
        </div>

        <!-- Info pane -->
        <div class="w-full md:w-2/5 p-6 md:p-8 flex flex-col overflow-y-auto bg-slate-50 relative">
            <button @click="open = false; document.body.style.overflow = '';"
                    class="absolute top-4 right-4 w-9 h-9 bg-slate-200 rounded-full hover:bg-red-600 hover:text-white transition flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div x-show="report" class="mt-4">
                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase text-white tracking-widest"
                      :class="report?.type === 'evento' ? 'bg-red-600' : 'bg-blue-600'"
                      x-text="report?.type === 'evento' ? 'Evento Institucional' : 'Actividad de Difusión'"></span>
                <h3 class="text-2xl font-black text-slate-900 mt-4 mb-4 leading-tight" x-text="report?.title"></h3>
                <div class="h-1 w-10 bg-red-600 rounded-full mb-5"></div>
                <p class="text-slate-600 text-sm leading-relaxed font-medium" x-text="report?.description"></p>
                <div class="mt-auto pt-6 border-t border-slate-200 flex items-center gap-3 mt-6">
                    <i class="fa-solid fa-camera text-slate-400"></i>
                    <p class="text-sm font-bold text-slate-500">
                        Foto <span x-text="photoIndex + 1" class="text-slate-900"></span>
                        de <span x-text="report?.photos?.length" class="text-slate-900"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════ LIGHTBOX ═════════ -->
<div id="lightbox" class="fixed inset-0 z-[110] bg-slate-950/97 backdrop-blur-xl flex flex-col items-center justify-center">
    <div class="absolute top-0 left-0 w-full p-5 flex justify-between items-center z-50">
        <span id="lb-counter" class="text-white font-bold text-[10px] tracking-widest bg-white/10 px-4 py-2 rounded-full border border-white/15"></span>
        <button id="lb-close" class="w-11 h-11 bg-white/10 hover:bg-red-600 text-white rounded-full flex items-center justify-center border border-white/15 transition">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>
    <button id="lb-prev" class="absolute left-3 sm:left-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/06 hover:bg-red-600 text-white rounded-full flex items-center justify-center border border-white/10 z-50 transition hover:scale-110">
        <i class="fa-solid fa-chevron-left text-xl pr-0.5"></i>
    </button>
    <div class="relative max-w-6xl max-h-[82vh] w-full px-4 sm:px-24 flex items-center justify-center">
        <img id="lb-img" src="" class="max-w-full max-h-[80vh] object-contain rounded-xl shadow-2xl"
             style="transition: opacity .2s, transform .2s;">
    </div>
    <button id="lb-next" class="absolute right-3 sm:right-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/06 hover:bg-red-600 text-white rounded-full flex items-center justify-center border border-white/10 z-50 transition hover:scale-110">
        <i class="fa-solid fa-chevron-right text-xl pl-0.5"></i>
    </button>
</div>


<!-- ════════════════════════════════════════════ SCRIPTS ══════════ -->
<script>
// ── SIDEBAR ──────────────────────────────────────────────────────
const sidebar  = document.getElementById('sidebar');
const overlay  = document.getElementById('sidebar-overlay');
const toggle   = document.getElementById('sidebar-toggle');

function openSidebar()  { sidebar.classList.add('open');  overlay.classList.add('open');  }
function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('open'); }

toggle?.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
});

// ── SCROLL HELPERS ────────────────────────────────────────────────
function scrollToActivity(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function scrollToSubEvent(id) {
    const el = document.getElementById(id);
    if (!el) return;
    // Small delay so Alpine can expand the parent if needed
    setTimeout(() => {
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        el.classList.add('highlight-target');
        setTimeout(() => el.classList.remove('highlight-target'), 2600);
    }, 120);
}

// ── YOUTUBE ───────────────────────────────────────────────────────
function playVideo(playBtn, ytId, containerId) {
    const c = document.getElementById(containerId);
    c.querySelector('.video-thumbnail').style.display = 'none';
    playBtn.style.display = 'none';
    const iframe = c.querySelector('.video-iframe');
    iframe.src = `https://www.youtube.com/embed/${ytId}?autoplay=1&rel=0`;
    iframe.style.display = 'block';
}

// ── GALLERY "VER MÁS" ─────────────────────────────────────────────
document.querySelectorAll('.btn-mostrar-mas').forEach(btn => {
    btn.addEventListener('click', function () {
        const grid     = this.closest('[class*="rounded-2xl"]')?.querySelector('.galeria-fotos');
        if (!grid) return;
        const extras   = grid.querySelectorAll('.foto-extra').length;
        const span     = this.querySelector('span');
        const icon     = this.querySelector('i');
        const showing  = grid.classList.toggle('mostrar-todas');
        span.textContent = showing ? 'Ocultar fotografías adicionales' : `Ver ${extras} fotografías adicionales`;
        icon.classList.toggle('fa-images',   !showing);
        icon.classList.toggle('fa-chevron-up', showing);
    });
});

// ── LIGHTBOX ─────────────────────────────────────────────────────
let gallery = [], idx = 0;
const lb     = document.getElementById('lightbox');
const lbImg  = document.getElementById('lb-img');
const lbCtr  = document.getElementById('lb-counter');

document.querySelectorAll('.foto-galeria').forEach(img => {
    img.addEventListener('click', function () {
        const grid = this.closest('.galeria-fotos');
        gallery    = Array.from(grid.querySelectorAll('.foto-galeria'));
        idx        = gallery.indexOf(this);
        openLB();
    });
});

function updateLB() {
    lbImg.style.opacity   = '.4';
    lbImg.style.transform = 'scale(.97)';
    setTimeout(() => {
        lbImg.src        = gallery[idx].src;
        lbCtr.textContent = `IMAGEN ${idx + 1} DE ${gallery.length}`;
        lbImg.style.opacity   = '1';
        lbImg.style.transform = 'scale(1)';
    }, 160);
}
function openLB()  { updateLB(); lb.classList.add('active');    document.body.style.overflow = 'hidden'; }
function closeLB() { lb.classList.remove('active'); document.body.style.overflow = '';
                     setTimeout(() => { lbImg.src = ''; }, 400); }

document.getElementById('lb-close').addEventListener('click', closeLB);
document.getElementById('lb-next').addEventListener('click', () => { idx = (idx + 1) % gallery.length; updateLB(); });
document.getElementById('lb-prev').addEventListener('click', () => { idx = (idx - 1 + gallery.length) % gallery.length; updateLB(); });
lb.addEventListener('click', e => { if (e.target === lb) closeLB(); });
document.addEventListener('keydown', e => {
    if (!lb.classList.contains('active')) return;
    if (e.key === 'Escape')      closeLB();
    if (e.key === 'ArrowRight')  document.getElementById('lb-next').click();
    if (e.key === 'ArrowLeft')   document.getElementById('lb-prev').click();
});

// ── ALPINE AUTO-SLIDER ────────────────────────────────────────────
document.addEventListener('alpine:init', () => {
    Alpine.data('autoSlider', (items, totalMs) => ({
        items,
        active:   0,
        progress: 0,
        tick:     50,
        init() { if (this.items.length > 1) this.start(); },
        start() {
            const step = 100 / (totalMs / this.tick);
            setInterval(() => {
                this.progress += step;
                if (this.progress >= 100) {
                    this.progress = 0;
                    this.active   = (this.active + 1) % this.items.length;
                }
            }, this.tick);
        }
    }));
});
</script>
</body>
</html>

