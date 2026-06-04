<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Portal de Transparencia | DRTPE Puno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800;900&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --red:       #dc2626;
            --navy:      #060c1a;
            --sidebar-w: 300px;
            --header-h:  68px;
            --navbar-h:  72px;
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

        /* ── BG SCENE ──────────────────────────────────────────── */
        .bg-scene {
            background-image: url('/images/fondodash2.png');
            background-size: cover;
            background-position: center top;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        /* ── SIDEBAR ───────────────────────────────────────────── */
        #sidebar {
            position: fixed; left: 0;
            top: calc(var(--header-h));
            width: var(--sidebar-w);
            height: calc(100vh - var(--header-h));
            background: rgba(4,8,20,.97);
            border-right: 1px solid rgba(255,255,255,.07);
            backdrop-filter: blur(28px); -webkit-backdrop-filter: blur(28px);
            overflow-y: auto; overflow-x: hidden;
            z-index: 45;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        #sidebar::-webkit-scrollbar { width: 3px; }
        #sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 99px; }

        #sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.65); z-index: 44; }

        @media (max-width: 1023px) {
            #sidebar      { transform: translateX(-100%); }
            #main-content { margin-left: 0 !important; }
        }
        #sidebar.open        { transform: translateX(0); }
        #sidebar-overlay.open { display: block; }

        /* ── SIDEBAR ITEMS ─────────────────────────────────────── */
        .sb-label {
            font-family:'Sora',sans-serif; font-size:9.5px; font-weight:700;
            letter-spacing:.22em; text-transform:uppercase; color:rgba(255,255,255,.24);
            padding:18px 16px 7px;
            border-top:1px solid rgba(255,255,255,.05); margin-top:4px;
        }
        .sb-label:first-of-type { border-top:none; margin-top:0; }

        .sb-item {
            display:flex; align-items:flex-start; gap:9px;
            padding:7px 11px; border-radius:11px; margin:0 8px 2px;
            cursor:pointer; border-left:2px solid transparent;
            transition:background .15s,border-color .15s;
        }
        .sb-item:hover  { background:rgba(255,255,255,.06); }
        .sb-item.active { background:rgba(220,38,38,.12); border-color:#dc2626; }

        .sb-thumb {
            width:32px; height:32px; border-radius:8px; overflow:hidden; flex-shrink:0;
            background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
            display:flex; align-items:center; justify-content:center;
        }
        .sb-thumb img { width:100%; height:100%; object-fit:cover; }
        .sb-dot { width:7px; height:7px; border-radius:3px; flex-shrink:0; margin-top:5px; }

        .sb-sep { display:flex; align-items:center; gap:7px; padding:5px 14px 3px; }
        .sb-sep span { font-size:8.5px; font-weight:800; letter-spacing:.18em; text-transform:uppercase; white-space:nowrap; }
        .sb-sep::after { content:''; flex:1; height:1px; background:rgba(255,255,255,.07); }

        /* ── MAIN LAYOUT ───────────────────────────────────────── */
        #main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            padding-top: var(--header-h);
            transition: margin-left .3s;
        }

        #top-navbar { padding-left: 0; }
        @media (min-width: 1024px) { #top-navbar { padding-left: var(--sidebar-w); } }

        /* ── SLIDERS ───────────────────────────────────────────── */
        .clip-top    { clip-path: polygon(0 0,100% 0,100% calc(100% - var(--diag)),0 100%); }
        .clip-bottom { clip-path: polygon(0 0,100% 0,100% 100%,0 calc(100% - var(--diag))); }

        .ken-burns { animation:kenBurns 16s ease-out infinite alternate; }
        @keyframes kenBurns { from{transform:scale(1);} to{transform:scale(1.13);} }

        .slider-progress-wrap {
            position:absolute; bottom:var(--diag); left:0; right:0;
            height:3px; background:rgba(255,255,255,.12); z-index:30;
        }
        .slider-progress-fill { height:100%; transition:width 50ms linear; }

        .slider-dot { width:8px; height:8px; border-radius:99px; background:rgba(255,255,255,.3); transition:width .3s,background .3s; cursor:pointer; border:none; padding:0; }
        .slider-dot.is-active { width:22px; background:#fff; }

        /* ── SECTION STYLES ────────────────────────────────────── */
        .section-after-sliders {
            position:relative;
            margin-top:calc(-1 * var(--diag));
            padding-top:calc(var(--diag) + 2.5rem);
            background:rgba(5,9,20,.38);
            backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px);
            border-bottom:1px solid rgba(255,255,255,.06);
        }
        .section-dark {
            background:rgba(5,9,20,.40);
            backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px);
            border-top:1px solid rgba(255,255,255,.06);
            border-bottom:1px solid rgba(255,255,255,.06);
        }
        .section-deep {
            background:rgba(3,6,16,.55);
            backdrop-filter:blur(4px); -webkit-backdrop-filter:blur(4px);
            border-top:1px solid rgba(255,255,255,.06);
        }

        /* ── GALLERY ───────────────────────────────────────────── */
        .foto-extra { display:none; opacity:0; transform:scale(.95); transition:all .45s; }
        .mostrar-todas .foto-extra { display:block; opacity:1; transform:scale(1); animation:fadeInGrid .45s ease forwards; }
        @keyframes fadeInGrid { from{opacity:0;transform:translateY(12px) scale(.95);} to{opacity:1;transform:translateY(0) scale(1);} }
        .foto-galeria { transition:transform .55s; cursor:zoom-in; display:block; }
        .foto-item:hover .foto-galeria { transform:scale(1.05); }

        /* ── TIMELINE ──────────────────────────────────────────── */
        .timeline-rail { border-left:2px solid rgba(96,165,250,.22); }
        .timeline-node {
            position:absolute; left:calc(-1rem - 9px); top:1.75rem;
            width:17px; height:17px; background:#3b82f6;
            border:3px solid rgba(15,30,70,.85); border-radius:5px;
            box-shadow:0 0 10px rgba(96,165,250,.45);
            transition:all .25s; z-index:2; transform:rotate(45deg);
        }
        .reporte-wrapper:hover .timeline-node { background:#dc2626; transform:rotate(135deg); box-shadow:0 0 14px rgba(220,38,38,.5); }
        .subevent-card { background:rgba(255,255,255,.96); border-radius:18px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.18); transition:box-shadow .25s; }
        .subevent-card:hover { box-shadow:0 8px 36px rgba(0,0,0,.28); }

        .activity-header {
            background:linear-gradient(130deg,#0c1a50 0%,#1e3a8a 60%,#1d4ed8 100%);
            border-left:5px solid #f59e0b; border-radius:18px; overflow:hidden; position:relative;
        }
        .activity-header::before { content:''; position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(245,158,11,.07); border-radius:50%; }

        /* ── CARDS ─────────────────────────────────────────────── */
        .record-card {
            background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.09);
            border-radius:18px; overflow:hidden;
            transition:background .2s,border-color .2s,transform .2s,box-shadow .2s;
            cursor:pointer; display:block;
        }
        .record-card:hover { background:rgba(255,255,255,.08); border-color:rgba(255,255,255,.2); transform:translateY(-4px); box-shadow:0 20px 40px rgba(0,0,0,.4); }

        .noticia-card {
            background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.09);
            border-radius:18px; overflow:hidden;
            transition:background .2s,border-color .2s,transform .2s,box-shadow .2s;
        }
        .noticia-card:hover { background:rgba(255,255,255,.08); border-color:rgba(255,255,255,.2); transform:translateY(-3px); box-shadow:0 16px 36px rgba(0,0,0,.4); }
        .noticia-img-wrap.portrait  { height:280px; }
        .noticia-img-wrap.landscape { height:180px; }

        /* ── VIDEO YOUTUBE ─────────────────────────────────────── */
        .video-preview-container { position:relative; overflow:hidden; cursor:pointer; transition:all .3s ease; }
        .video-preview-container:hover { transform:translateY(-4px); box-shadow:0 20px 25px -5px rgba(0,0,0,.2); }
        .play-button {
            position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
            background:rgba(220,38,38,.9); width:64px; height:64px; border-radius:50%;
            display:flex; align-items:center; justify-content:center; transition:all .4s ease;
            animation:pulseRed 2s infinite; z-index:10;
        }
        @keyframes pulseRed { 0%{box-shadow:0 0 0 0 rgba(239,68,68,.7);} 70%{box-shadow:0 0 0 15px rgba(239,68,68,0);} 100%{box-shadow:0 0 0 0 rgba(239,68,68,0);} }

        /* ── COMUNICADOS ───────────────────────────────────────── */
        .comunicado-pdf-icon {
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            height:100%; gap:12px; padding:24px;
            background:linear-gradient(135deg,rgba(15,20,40,.9),rgba(30,40,70,.9));
        }

        /* ── SOCIAL ────────────────────────────────────────────── */
        .social-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:9px; font-size:11px; font-weight:700; transition:all .18s; text-decoration:none; white-space:nowrap; }
        .social-badge:hover { transform:translateY(-2px); filter:brightness(1.1); }
        .badge-fb { background:#1877f2; color:#fff; }
        .badge-tt { background:#111; color:#fff; border:1px solid rgba(255,255,255,.15); }

        /* ── FOOTER LIGHT ──────────────────────────────────────── */
        .footer-light { background:rgba(240,244,248,.97); backdrop-filter:blur(10px); color:#1e293b; }

        /* ── LIGHTBOX ──────────────────────────────────────────── */
        #lightbox { opacity:0; visibility:hidden; transition:opacity .35s,visibility .35s; }
        #lightbox.active { opacity:1; visibility:visible; }

        /* ── HIGHLIGHT SCROLL ──────────────────────────────────── */
        @keyframes highlightPulse { 0%{box-shadow:0 0 0 0 rgba(220,38,38,.6);} 60%{box-shadow:0 0 0 22px rgba(220,38,38,0);} 100%{box-shadow:none;} }
        .highlight-target { animation:highlightPulse 2s ease; }

        /* ── TOP NAVBAR DROPDOWN ───────────────────────────────── */
        [x-cloak] { display:none !important; }
    </style>
</head>

<body class="antialiased selection:bg-red-700 selection:text-white">

{{-- ════════════════════════════════════════════════════════════ --}}
{{-- COMUNICADOS POPUP INSTITUCIONAL                              --}}
{{-- ════════════════════════════════════════════════════════════ --}}
@if(isset($comunicadosActivos) && $comunicadosActivos->count() > 0)
<script>
    window.comunicadosPlataforma = @json($comunicadosActivos);
</script>

<div x-data="{
        showPopup: true,
        active: 0,
        items: window.comunicadosPlataforma || [],
        get current() { return this.items[this.active] || null; },
        init() {
            if (this.items.length > 1) {
                setInterval(() => {
                    this.active = (this.active + 1) % this.items.length;
                }, 6500);
            }
        }
    }"
     x-show="showPopup"
     class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md"
     x-cloak>

    <div class="relative bg-white rounded-2xl w-full max-w-5xl shadow-2xl flex flex-col md:flex-row overflow-hidden border border-slate-100"
         style="height: 75vh;"
         @click.away="showPopup = false"
         x-transition>

        <template x-if="current">
            <div class="w-full h-full flex flex-col md:flex-row items-stretch">

                {{-- COLUMNA IZQUIERDA: VISUALIZADOR --}}
                <div class="w-full md:w-[62%] bg-slate-950 flex items-center justify-center relative overflow-hidden h-1/2 md:h-full border-b md:border-b-0 md:border-r border-slate-100">

                    <template x-if="current.file_type === 'image'">
                        <div class="w-full h-full p-4 flex items-center justify-center">
                            <img :src="'/storage/' + current.file_path"
                                 class="w-full h-full object-contain shadow-2xl transition-all duration-300">
                        </div>
                    </template>

                    <template x-if="current.file_type !== 'image'">
                        <div class="w-full h-full bg-slate-900">
                            <iframe :key="current.id"
                                    :src="'/storage/' + current.file_path + '#toolbar=0&navpanes=0&statusbar=0&view=Fit'"
                                    class="w-full h-full border-none"
                                    allow="autoplay"></iframe>
                        </div>
                    </template>

                    <template x-if="items.length > 1">
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-slate-950/60 backdrop-blur-sm px-3 py-1.5 flex gap-1.5 z-10 border border-white/10 rounded-full">
                            <template x-for="(c, i) in items" :key="'dot-'+i">
                                <button @click="active = i"
                                        class="h-1.5 transition-all duration-300 border-none p-0 rounded-full"
                                        :class="active === i ? 'bg-amber-500 w-4 shadow-[0_0_6px_#f59e0b]' : 'bg-white/35 w-1.5'"></button>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- COLUMNA DERECHA: DATOS INSTITUCIONALES --}}
                <div class="w-full md:w-[38%] bg-white flex flex-col justify-between p-5 sm:p-6 overflow-y-auto h-1/2 md:h-full text-slate-800">

                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <span class="text-[9px] font-mono font-black text-red-600 uppercase tracking-widest flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span>
                                Difusión Oficial
                            </span>
                            <button @click="showPopup = false; document.body.style.overflow='';"
                                    class="text-slate-400 hover:text-slate-700 transition text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                Cerrar <i class="fa-solid fa-xmark text-xs text-red-500"></i>
                            </button>
                        </div>

                        <div class="space-y-2">
                            <span class="bg-slate-100 text-slate-600 border border-slate-200/60 font-mono text-[9px] font-black uppercase px-2 py-0.5 rounded">Comunicado Activo</span>
                            <h3 class="text-slate-900 font-black text-base sm:text-lg tracking-tight uppercase leading-snug break-words" x-text="current.title"></h3>
                            <div class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                <i class="fa-regular fa-calendar-check text-slate-400"></i> Publicado:
                                <span class="text-slate-600" x-text="current.published_at ? new Date(current.published_at).toLocaleDateString('es-PE') : ''"></span>
                            </div>

                            <hr class="border-slate-100 my-2">

                            <p class="text-slate-600 text-xs font-medium leading-relaxed bg-slate-50 p-3 border border-slate-100 rounded-xl max-h-40 overflow-y-auto scrollbar-thin"
                               x-text="current.description || 'Sin descripción adicional adjunta.'"></p>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-slate-100 mt-4 md:mt-0">

                        <div x-show="current.attachments && current.attachments.length > 0" class="space-y-2">
                            <p class="text-slate-400 text-[9px] font-mono font-black uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-paperclip text-amber-500"></i> Archivos y Bases Vinculadas:
                            </p>
                            <div class="space-y-1.5 max-h-36 overflow-y-auto pr-1 scrollbar-thin">
                                <template x-for="(adjuntoPath, idx) in current.attachments" :key="idx">
                                    <a :href="'/storage/' + adjuntoPath" target="_blank"
                                       class="flex items-center gap-2 bg-slate-50 hover:bg-slate-100 border border-slate-200/70 p-2 text-[11px] font-bold text-slate-700 hover:text-slate-900 transition rounded-xl truncate group">
                                        <div class="w-5 h-5 rounded-md bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm">
                                            <i class="fa-solid text-[10px]"
                                               :class="adjuntoPath.toLowerCase().endsWith('.pdf') ? 'fa-file-pdf text-red-500' : 'fa-image text-blue-500'"></i>
                                        </div>
                                        <span class="truncate flex-1" x-text="'Anexo Opcional N° ' + (idx + 1)"></span>
                                        <i class="fa-solid fa-arrow-down text-[9px] text-slate-400 group-hover:text-slate-600 transition-colors mr-1"></i>
                                    </a>
                                </template>
                            </div>
                        </div>

                        <a :href="'/storage/' + current.file_path" target="_blank"
                           class="w-full bg-red-600 hover:bg-red-500 text-white text-xs font-black uppercase tracking-wider py-3 rounded-xl flex items-center justify-center gap-2 transition shadow-md hover:shadow-lg hover:-translate-y-0.5 transform shrink-0">
                            <i class="fa-solid fa-file-arrow-down text-sm"></i> Descargar Documento Principal
                        </a>
                    </div>

                </div>

            </div>
        </template>

    </div>
</div>
@endif


{{-- ════════════════════════════════════════════════════════════ --}}
{{-- HEADER                                                       --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-red-600 backdrop-blur-xl border-b border-white/10 shadow-2xl" style="height:var(--header-h);">
    <div class="h-full px-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <button id="sidebar-toggle" class="lg:hidden w-10 h-10 rounded-xl bg-white/08 hover:bg-white/14 border border-white/10 flex items-center justify-center transition">
                <i class="fa-solid fa-bars text-white text-sm"></i>
            </button>
            <div class="bg-white/10 p-1.5 rounded-xl border border-white/15 shadow-lg">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-9 h-9 object-contain">
            </div>
            <div class="hidden sm:block">
                <p class="text-white font-black text-base leading-tight tracking-tight" style="font-family:'Sora',sans-serif;">Portal Oficial de Actividades</p>
                <p class="text-red-200 text-[10px] font-semibold uppercase tracking-widest">DRTPE Puno · Perú</p>
            </div>
        </div>
        <a href="{{ route('login') }}" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 hover:border-white/40 px-5 py-2 rounded-xl text-xs font-bold text-white transition-all">
            <i class="fa-solid fa-lock text-white text-sm"></i>
            <span class="hidden sm:inline">Acceso Interno</span>
        </a>
    </div>
</header>


{{-- ════════════════════════════════════════════════════════════ --}}
{{-- TOP NAVBAR                                                   --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<div id="top-navbar"
     class="w-full bg-red-600 border-b border-red-700 relative z-40 shadow-xl hidden lg:block"
     style="top:var(--header-h);">
    <div class="max-w-full px-4 lg:px-6">
        <div class="flex items-center justify-around h-[var(--navbar-h)] text-xs font-black uppercase tracking-wider relative"
             x-data="{ openMenu: null }" style="height:var(--navbar-h);">

            <a href="#" class="text-white hover:bg-white/10 h-full flex items-center gap-2 transition-all px-4 shrink-0 font-black">
                <i class="fa-solid fa-house text-sm"></i> Inicio
            </a>

            <div class="h-full flex items-center relative" @mouseenter="openMenu='institucional'" @mouseleave="openMenu=null">
                <button class="text-white hover:bg-white/10 h-full flex items-center gap-1.5 transition-all px-4 font-black" :class="openMenu==='institucional'?'bg-white/10':''">
                    Institucional <i class="fa-solid fa-chevron-down text-[10px] text-white/80 transition-transform" :class="openMenu==='institucional'?'rotate-180':''"></i>
                </button>
                <div x-show="openMenu==='institucional'" x-cloak x-transition class="absolute top-full left-0 bg-slate-950 border border-white/10 shadow-2xl py-4 w-72 text-sm text-slate-300 font-bold normal-case z-50">
                    <a href="#" class="flex items-center gap-3 px-6 py-3 hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-circle-info text-base text-red-500"></i> Sobre Nosotros</a>
                    <a href="#" class="flex items-center gap-3 px-6 py-3 hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-sitemap text-base text-red-500"></i> Organigrama</a>
                    <a href="#" class="flex items-center gap-3 px-6 py-3 hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-address-book text-base text-red-500"></i> Directorio</a>
                    <a href="#" class="flex items-center gap-3 px-6 py-3 hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-scale-balanced text-base text-red-500"></i> Marco Legal</a>
                </div>
            </div>

            <div class="h-full flex items-center relative" @mouseenter="openMenu='organica'" @mouseleave="openMenu=null" x-data="{ subMenu: null }">
                <button class="text-white hover:bg-white/10 h-full flex items-center gap-1.5 transition-all px-4 font-black" :class="openMenu==='organica'?'bg-white/10':''">
                    Estructura Orgánica <i class="fa-solid fa-chevron-down text-[10px] text-white/80 transition-transform" :class="openMenu==='organica'?'rotate-180':''"></i>
                </button>
                <div x-show="openMenu==='organica'" x-cloak x-transition class="absolute top-full left-0 bg-slate-950 border border-white/10 shadow-2xl py-4 w-80 text-sm text-slate-300 font-bold normal-case z-50">
                    <a href="#" class="flex items-center gap-3 px-6 py-3 hover:bg-white/5 hover:text-white transition-colors border-b border-white/5 pb-3 mb-1"><i class="fa-solid fa-user-tie text-base text-red-500"></i> Gerencia Regional</a>
                    <div class="relative" @mouseenter="subMenu='admin'" @mouseleave="subMenu=null">
                        <div class="flex items-center justify-between px-6 py-3 hover:bg-white/5 hover:text-white cursor-pointer transition-colors" :class="subMenu==='admin'?'bg-white/5 text-white':''">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-calculator text-base text-red-500"></i> Oficina de Administración</span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-500"></i>
                        </div>
                        <div x-show="subMenu==='admin'" x-cloak class="absolute top-0 left-full ml-px bg-slate-950 border border-white/10 shadow-2xl py-3 w-52 text-slate-400 font-medium">
                            <a href="#" class="block px-6 py-2 hover:bg-white/5 hover:text-white transition-colors">Personal</a>
                            <a href="#" class="block px-6 py-2 hover:bg-white/5 hover:text-white transition-colors">Contabilidad</a>
                            <a href="#" class="block px-6 py-2 hover:bg-white/5 hover:text-white transition-colors">Abastecimiento</a>
                            <a href="#" class="block px-6 py-2 hover:bg-white/5 hover:text-white transition-colors">Presupuesto</a>
                        </div>
                    </div>
                    <div class="relative" @mouseenter="subMenu='empleo'" @mouseleave="subMenu=null">
                        <div class="flex items-center justify-between px-6 py-3 hover:bg-white/5 hover:text-white cursor-pointer transition-colors" :class="subMenu==='empleo'?'bg-white/5 text-white':''">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-passport text-base text-red-500"></i> Dirección del Empleo</span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-500"></i>
                        </div>
                        <div x-show="subMenu==='empleo'" x-cloak class="absolute top-0 left-full ml-px bg-slate-950 border border-white/10 shadow-2xl py-3 w-64 text-slate-400 font-medium">
                            <a href="#" class="block px-6 py-2 hover:bg-white/5 hover:text-white transition-colors">Información General</a>
                            <a href="#" class="block px-6 py-2 hover:bg-white/5 hover:text-white transition-colors">Registros Administrativos</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-full flex items-center relative" @mouseenter="openMenu='servicios'" @mouseleave="openMenu=null">
                <button class="text-white hover:bg-white/10 h-full flex items-center gap-1.5 transition-all px-4 font-black" :class="openMenu==='servicios'?'bg-white/10':''">
                    Servicios <i class="fa-solid fa-chevron-down text-[10px] text-white/80 transition-transform" :class="openMenu==='servicios'?'rotate-180':''"></i>
                </button>
                <div x-show="openMenu==='servicios'" x-cloak x-transition class="absolute top-full left-0 bg-slate-950 border border-white/10 shadow-2xl py-4 w-72 text-sm text-slate-300 font-bold normal-case z-50">
                    <a href="#" class="flex items-center gap-3 px-6 py-3 hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-briefcase text-base text-red-400"></i> Centro de Empleo Puno</a>
                    <a href="#" class="flex items-center gap-3 px-6 py-3 hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-receipt text-base text-red-400"></i> Fraccionamiento de Multas</a>
                    <a href="#" class="flex items-center gap-3 px-6 py-3 hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-user-graduate text-base text-red-400"></i> Capacitación</a>
                    <a href="#" class="flex items-center gap-3 px-6 py-3 hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-gavel text-base text-red-400"></i> Defensa Legal</a>
                </div>
            </div>

            <div class="h-full flex items-center relative" @mouseenter="openMenu='talleres'" @mouseleave="openMenu=null">
                <button class="text-white hover:bg-white/10 h-full flex items-center gap-1.5 transition-all px-4 font-black" :class="openMenu==='talleres'?'bg-white/10':''">
                    Talleres <i class="fa-solid fa-chevron-down text-[10px] text-white/80 transition-transform" :class="openMenu==='talleres'?'rotate-180':''"></i>
                </button>
                <div x-show="openMenu==='talleres'" x-cloak x-transition class="absolute top-full right-0 bg-slate-950 border border-white/10 shadow-2xl py-4 w-80 text-sm text-slate-300 font-bold normal-case z-50">
                    <button onclick="scrollToSection('seccion-por-hacer')" class="w-full text-left flex items-center justify-between px-6 py-3 hover:bg-white/5 hover:text-white transition-colors bg-transparent border-none font-bold text-slate-300 group">
                        <div class="flex items-center gap-3"><i class="fa-regular fa-clock text-base text-red-400 w-5 text-center"></i> Capacitaciones por Hacer</div>
                        <span class="text-[10px] font-mono font-black px-2 py-0.5 rounded {{ isset($capacitacionesPorHacer) && $capacitacionesPorHacer->count() > 0 ? 'bg-red-600 text-white animate-pulse' : 'bg-white/10 text-slate-500' }}">{{ isset($capacitacionesPorHacer) ? $capacitacionesPorHacer->count() : 0 }}</span>
                    </button>
                    <button onclick="scrollToSection('seccion-hechas')" class="w-full text-left flex items-center justify-between px-6 py-3 hover:bg-white/5 hover:text-white transition-colors bg-transparent border-none font-bold text-slate-300">
                        <div class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-base text-red-400 w-5 text-center"></i> Capacitaciones Hechas</div>
                        <span class="text-[10px] font-mono font-black px-2 py-0.5 bg-white/10 text-slate-400 rounded">{{ isset($capacitacionesHechas) ? $capacitacionesHechas->count() : 0 }}</span>
                    </button>
                    <button onclick="scrollToSection('seccion-coordinaciones')" class="w-full text-left flex items-center justify-between px-6 py-3 hover:bg-white/5 hover:text-white transition-colors bg-transparent border-none font-bold text-slate-300">
                        <div class="flex items-center gap-3"><i class="fa-solid fa-handshake text-base text-red-400 w-5 text-center"></i> Coordinaciones Hechas</div>
                        <span class="text-[10px] font-mono font-black px-2 py-0.5 bg-white/10 text-slate-400 rounded">{{ isset($coordinacionesHechas) ? $coordinacionesHechas->count() : 0 }}</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════════════════════ --}}
{{-- SIDEBAR                                                      --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<aside id="sidebar">
    <div class="lg:hidden px-4 py-2 border-b border-white/5 space-y-1" x-data="{ openMobileSec: null }">
        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest px-2 mb-2">Navegación del Portal</p>

        <div class="bg-white/5 rounded-xl overflow-hidden">
            <button @click="openMobileSec = openMobileSec === 'inst' ? null : 'inst'" class="w-full px-4 py-3 flex items-center justify-between font-bold text-xs text-slate-200 hover:text-white">
                <span class="flex items-center gap-2.5"><i class="fa-solid fa-building text-red-500"></i> Institucional</span>
                <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="openMobileSec === 'inst' ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="openMobileSec === 'inst'" x-cloak class="bg-black/40 px-4 py-2 space-y-2 text-[11px] font-medium text-slate-400 border-t border-white/5 flex flex-col">
                <a href="#" class="py-1.5 hover:text-white flex items-center gap-2"><i class="fa-solid fa-circle-info text-[9px] text-red-500"></i> Sobre Nosotros</a>
                <a href="#" class="py-1.5 hover:text-white flex items-center gap-2"><i class="fa-solid fa-sitemap text-[9px] text-red-500"></i> Organigrama</a>
                <a href="#" class="py-1.5 hover:text-white flex items-center gap-2"><i class="fa-solid fa-address-book text-[9px] text-red-500"></i> Directorio</a>
                <a href="#" class="py-1.5 hover:text-white flex items-center gap-2"><i class="fa-solid fa-scale-balanced text-[9px] text-red-500"></i> Marco Legal</a>
            </div>
        </div>

        <div class="bg-white/5 rounded-xl overflow-hidden">
            <button @click="openMobileSec = openMobileSec === 'org' ? null : 'org'" class="w-full px-4 py-3 flex items-center justify-between font-bold text-xs text-slate-200 hover:text-white">
                <span class="flex items-center gap-2.5"><i class="fa-solid fa-sitemap text-red-500"></i> Estructura Orgánica</span>
                <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="openMobileSec === 'org' ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="openMobileSec === 'org'" x-cloak class="bg-black/40 px-4 py-2 space-y-2 text-[11px] font-medium text-slate-400 border-t border-white/5 flex flex-col">
                <a href="#" class="py-1.5 hover:text-white flex items-center gap-2"><i class="fa-solid fa-user-tie text-[9px] text-red-500"></i> Gerencia Regional</a>
                <p class="font-black text-[9px] text-slate-500 uppercase tracking-wider pt-1 border-t border-white/5">Áreas Internas</p>
                <a href="#" class="py-1 hover:text-white pl-2">&bull; Administración</a>
                <a href="#" class="py-1 hover:text-white pl-2">&bull; Dirección del Empleo</a>
            </div>
        </div>

        <div class="bg-white/5 rounded-xl overflow-hidden">
            <button @click="openMobileSec = openMobileSec === 'serv' ? null : 'serv'" class="w-full px-4 py-3 flex items-center justify-between font-bold text-xs text-slate-200 hover:text-white">
                <span class="flex items-center gap-2.5"><i class="fa-solid fa-briefcase text-red-500"></i> Servicios</span>
                <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="openMobileSec === 'serv' ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="openMobileSec === 'serv'" x-cloak class="bg-black/40 px-4 py-2 space-y-2 text-[11px] font-medium text-slate-400 border-t border-white/5 flex flex-col">
                <a href="#" class="py-1.5 hover:text-white">&bull; Centro de Empleo Puno</a>
                <a href="#" class="py-1.5 hover:text-white">&bull; Fraccionamiento de Multas</a>
                <a href="#" class="py-1.5 hover:text-white">&bull; Capacitaciones Externas</a>
            </div>
        </div>

        <div class="bg-white/5 rounded-xl overflow-hidden mb-4">
            <button @click="openMobileSec = openMobileSec === 'tal' ? null : 'tal'" class="w-full px-4 py-3 flex items-center justify-between font-bold text-xs text-slate-200 hover:text-white">
                <span class="flex items-center gap-2.5"><i class="fa-solid fa-user-graduate text-red-500"></i> Talleres</span>
                <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="openMobileSec === 'tal' ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="openMobileSec === 'tal'" x-cloak class="bg-black/40 px-2 py-2 text-[11px] font-bold border-t border-white/5 flex flex-col">
                <button onclick="scrollToSection('seccion-por-hacer'); closeSidebar()" class="w-full text-left py-2 px-2 hover:bg-white/5 text-slate-300 rounded flex justify-between items-center bg-transparent border-none">
                    <span>Capacitaciones por Hacer</span>
                    <span class="text-[9px] font-mono font-black px-1.5 py-0.5 rounded bg-red-600 text-white">{{ isset($capacitacionesPorHacer) ? $capacitacionesPorHacer->count() : 0 }}</span>
                </button>
                <button onclick="scrollToSection('seccion-hechas'); closeSidebar()" class="w-full text-left py-2 px-2 hover:bg-white/5 text-slate-300 rounded flex justify-between items-center bg-transparent border-none">
                    <span>Capacitaciones Hechas</span>
                    <span class="text-[9px] font-mono font-black px-1.5 py-0.5 rounded bg-white/10 text-slate-400">{{ isset($capacitacionesHechas) ? $capacitacionesHechas->count() : 0 }}</span>
                </button>
                <button onclick="scrollToSection('seccion-coordinaciones'); closeSidebar()" class="w-full text-left py-2 px-2 hover:bg-white/5 text-slate-300 rounded flex justify-between items-center bg-transparent border-none">
                    <span>Coordinaciones Hechas</span>
                    <span class="text-[9px] font-mono font-black px-1.5 py-0.5 rounded bg-white/10 text-slate-400">{{ isset($coordinacionesHechas) ? $coordinacionesHechas->count() : 0 }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Logo + contacto -->
    <div class="p-5 pb-4">
        <div class="flex items-center gap-3 mb-5 pt-1">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-12 object-contain rounded-xl bg-white/08 p-1.5 border border-white/10">
            <div>
                <p class="text-white font-black text-sm leading-tight" style="font-family:'Sora',sans-serif;">DRTPE Puno</p>
                <p class="text-slate-500 text-[10px] uppercase tracking-wider">Dirección Regional</p>
            </div>
        </div>
        <div class="space-y-3 mb-4">
            <div class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-red-600/20 border border-red-500/25 flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fa-solid fa-location-dot text-red-400 text-[11px]"></i></div>
                <div><p class="text-slate-200 text-xs font-bold">Sede Puno</p><p class="text-slate-500 text-[11px] leading-snug">Jr. Ayacucho N° 658, Puno</p></div>
            </div>
            <div class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-blue-600/20 border border-blue-500/25 flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fa-solid fa-location-dot text-blue-400 text-[11px]"></i></div>
                <div><p class="text-slate-200 text-xs font-bold">Sede Juliaca</p><p class="text-slate-500 text-[11px] leading-snug">Jr. Santiago Mamani N° 200, Juliaca</p></div>
            </div>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="https://www.facebook.com/DRTPEPunoOFICIAL/?locale=es_LA" target="_blank" class="social-badge badge-fb"><i class="fa-brands fa-facebook"></i> Facebook</a>
            <a href="#" target="_blank" class="social-badge badge-tt"><i class="fa-brands fa-tiktok"></i> TikTok</a>
        </div>
    </div>

    <!-- Reportes Fotográficos -->
    <p class="sb-label"><i class="fa-solid fa-images mr-1.5 text-blue-400"></i>Reportes Fotográficos</p>
    @if(isset($difusiones) && $difusiones->count())
        <div class="sb-sep"><span class="text-blue-400">Difusión</span></div>
        @foreach($difusiones->take(5) as $dif)
        <div class="sb-item" onclick="window.dispatchEvent(new CustomEvent('open-modal',{detail:{report:{{ $dif->toJson() }}}}))">
            <div class="sb-thumb">@if(isset($dif->photos[0]))<img src="{{ asset('storage/'.$dif->photos[0]) }}" alt="">@else<i class="fa-solid fa-image text-slate-600 text-xs"></i>@endif</div>
            <div class="flex-1 min-w-0"><p class="text-slate-200 text-xs font-semibold leading-snug truncate">{{ $dif->title }}</p><p class="text-blue-400 text-[9px] mt-0.5 font-bold uppercase tracking-wider">Difusión</p></div>
        </div>
        @endforeach
    @endif
    @if(isset($institucionales) && $institucionales->count())
        <div class="sb-sep mt-1"><span class="text-red-400">Institucional</span></div>
        @foreach($institucionales->take(5) as $inst)
        <div class="sb-item" onclick="window.dispatchEvent(new CustomEvent('open-modal',{detail:{report:{{ $inst->toJson() }}}}))">
            <div class="sb-thumb">@if(isset($inst->photos[0]))<img src="{{ asset('storage/'.$inst->photos[0]) }}" alt="">@else<i class="fa-solid fa-image text-slate-600 text-xs"></i>@endif</div>
            <div class="flex-1 min-w-0"><p class="text-slate-200 text-xs font-semibold leading-snug truncate">{{ $inst->title }}</p><p class="text-red-400 text-[9px] mt-0.5 font-bold uppercase tracking-wider">Institucional</p></div>
        </div>
        @endforeach
    @endif

    <!-- Actividades -->
    <p class="sb-label"><i class="fa-solid fa-timeline mr-1.5 text-amber-400"></i>Actividades Registradas</p>
    @if(isset($actividades))
    @foreach($actividades->take(12) as $idx => $act)
    @if($act->subEvents->count() > 0)
    <div class="sb-item" onclick="scrollToActivity('actividad-{{ $idx }}')">
        <div class="sb-dot bg-amber-400 mt-1.5"></div>
        <div class="flex-1 min-w-0">
            <p class="text-slate-200 text-xs font-semibold leading-snug line-clamp-2">{{ $act->description }}</p>
            <p class="text-slate-500 text-[9px] mt-0.5"><span class="text-amber-400 font-bold">PP:{{ $act->category->pp_code ?? '—' }}</span> · {{ $act->subEvents->count() }} registro(s)</p>
        </div>
    </div>
    @endif
    @endforeach
    @endif
    <div class="h-8"></div>
</aside>


{{-- ════════════════════════════════════════════════════════════ --}}
{{-- MAIN CONTENT                                                 --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<div id="main-content">

    {{-- ── SLIDERS ─────────────────────────────────────────────── --}}
    <div class="bg-scene relative">

        @if(isset($difusiones) && $difusiones->count() > 0)
        <section class="relative w-full overflow-hidden clip-top z-30"
                 style="height:clamp(340px,62vh,680px);background:rgba(15,28,80,.40);"
                 x-data="autoSlider({{ $difusiones->toJson() }}, 5000)">
            <div class="absolute top-5 left-5 z-30 flex items-center gap-3 flex-wrap">
                <span class="bg-blue-600/85 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full border border-blue-400/30 shadow-lg">
                    <i class="fa-solid fa-radio mr-1.5"></i> Actividades de Difusión
                </span>
                <div class="flex gap-1.5">
                    <template x-for="(item,i) in items" :key="i">
                        <button @click="active=i;progress=0" :class="active===i?'slider-dot is-active':'slider-dot'"></button>
                    </template>
                </div>
            </div>
            <template x-for="(item,index) in items" :key="index">
                <div x-show="active===index"
                     x-transition:enter="transition-opacity duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-700" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="absolute inset-0 cursor-pointer group" @click="$dispatch('open-modal',{report:item})">
                    <img :src="'{{ asset('storage') }}/'+item.photos[0]" class="w-full h-full object-cover ken-burns" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-950 via-blue-900/30 to-blue-950/10"></div>
                    <div class="absolute left-5 sm:left-12 max-w-2xl" style="bottom:calc(var(--diag) + 2.5rem);">
                        <div class="bg-blue-900/45 backdrop-blur-md border border-blue-400/20 rounded-2xl p-4 sm:p-6">
                            <h2 class="text-xl sm:text-4xl font-black text-white leading-tight" x-text="item.title"></h2>
                            <p class="text-blue-300/80 mt-2 text-xs font-medium flex items-center gap-1.5"><i class="fa-solid fa-hand-pointer animate-pulse"></i> Presione para ver descripción y galería</p>
                        </div>
                    </div>
                </div>
            </template>
            <div class="slider-progress-wrap"><div class="slider-progress-fill bg-blue-400 shadow-[0_0_7px_#60a5fa]" :style="'width:'+progress+'%'"></div></div>
        </section>
        @endif

        @if(isset($institucionales) && $institucionales->count() > 0)
        <section class="relative w-full overflow-hidden clip-bottom z-20"
                 style="height:clamp(340px,62vh,680px);margin-top:calc(-1*var(--diag));background:rgba(70,8,8,.40);"
                 x-data="autoSlider({{ $institucionales->toJson() }}, 5000)">
            <div class="absolute z-30 flex flex-col items-end gap-2" style="top:calc(var(--diag) + 14px);right:1.25rem;">
                <span class="bg-red-600/85 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full border border-red-400/30 shadow-lg">
                    <i class="fa-solid fa-calendar-star mr-1.5"></i> Eventos Institucionales
                </span>
                <div class="flex gap-1.5">
                    <template x-for="(item,i) in items" :key="i">
                        <button @click="active=i;progress=0" :class="active===i?'slider-dot is-active':'slider-dot'"></button>
                    </template>
                </div>
            </div>
            <template x-for="(item,index) in items" :key="index">
                <div x-show="active===index"
                     x-transition:enter="transition-opacity duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-700" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="absolute inset-0 cursor-pointer group" @click="$dispatch('open-modal',{report:item})">
                    <img :src="'{{ asset('storage') }}/'+item.photos[0]" class="w-full h-full object-cover ken-burns" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-red-950 via-red-900/30 to-red-950/10"></div>
                    <div class="absolute left-5 sm:left-12 max-w-2xl" style="bottom:calc(var(--diag) + 2.5rem);">
                        <div class="bg-red-900/45 backdrop-blur-md border border-red-400/20 rounded-2xl p-4 sm:p-6">
                            <h2 class="text-xl sm:text-4xl font-black text-white leading-tight" x-text="item.title"></h2>
                            <p class="text-red-300/80 mt-2 text-xs font-medium flex items-center gap-1.5"><i class="fa-solid fa-hand-pointer animate-pulse"></i> Presione para ver descripción y galería</p>
                        </div>
                    </div>
                </div>
            </template>
            <div class="slider-progress-wrap"><div class="slider-progress-fill bg-red-400 shadow-[0_0_7px_#f87171]" :style="'width:'+progress+'%'"></div></div>
        </section>
        @endif
    </div>
    {{-- /sliders --}}

    {{-- ── ÚLTIMOS REGISTROS ──────────────────────────────────── --}}
    @if(isset($ultimos3) && $ultimos3->count() > 0)
    <div class="section-after-sliders pb-14">
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0"><i class="fa-solid fa-bolt text-white"></i></div>
                <div>
                    <h2 class="text-xl font-black text-white">Últimos Registros</h2>
                    <p class="text-slate-400 text-xs font-medium">Actividades recientes con evidencia fotográfica · clic para ir al registro</p>
                </div>
                <div class="flex-1 h-px bg-gradient-to-r from-red-700/40 to-transparent hidden sm:block"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($ultimos3 as $se)
                <div class="record-card" onclick="scrollToSubEvent('subevent-{{ $se->id }}', {{ $se->activity_index }})">
                    <div class="relative overflow-hidden" style="height:155px;">
                        <img src="{{ asset('storage/'.$se->cover) }}" class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" loading="lazy" alt="">
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

    {{-- ── NOTICIAS ──────────────────────────────────────────── --}}
    @if(isset($noticias) && $noticias->count() > 0)
    <div id="seccion-noticias" class="section-dark py-14">
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10">
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
                        <img src="{{ asset('storage/'.$noticia->photo) }}" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105" loading="lazy" alt="">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 to-transparent"></div>
                        <div class="absolute top-3 left-3"><span class="bg-emerald-600/90 text-white text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-wider">Noticia</span></div>
                    </div>
                    @endif
                    <div class="p-5">
                        <p class="text-slate-400 text-[10px] font-bold mb-2"><i class="fa-regular fa-calendar text-emerald-400 mr-1"></i>{{ \Carbon\Carbon::parse($noticia->published_at)->format('d M. Y') }}</p>
                        <h3 class="text-base font-black text-slate-100 leading-snug mb-3">{{ $noticia->title }}</h3>
                        @if($noticia->description)<p class="text-slate-400 text-xs leading-relaxed line-clamp-4">{{ $noticia->description }}</p>@endif
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>
    @endif

@php
    $processActivityCollection = function($collection, $defaultType, $isPastEvent) {
        return $collection->map(function($item) use ($defaultType, $isPastEvent) {

            $toAbsoluteUrl = function($path) {
                if (empty($path)) return '';
                $path = str_replace('\\', '/', trim($path));
                $path = ltrim($path, '/');
                $path = preg_replace('#^(public/|storage/)#', '', $path);
                return asset('storage/' . $path);
            };

            $docUrl = $toAbsoluteUrl($item->document_path ?? '');
            $reqUrl = $toAbsoluteUrl($item->requirements_path ?? '');

            $rawPhotos = $item->photos;
            if (is_string($rawPhotos)) { $rawPhotos = json_decode($rawPhotos, true); }
            $photosArr = is_array($rawPhotos) ? $rawPhotos : [];

            $cleanPhotos = [];
            foreach ($photosArr as $p) {
                $pUrl = $toAbsoluteUrl($p);
                if (!empty($pUrl)) { $cleanPhotos[] = $pUrl; }
            }

            $cardCover = '';
            if ($isPastEvent) {
                if (!empty($cleanPhotos)) {
                    $cardCover = $cleanPhotos[0];
                } else {
                    $cardCover = $docUrl;
                }
            } else {
                $cardCover = $docUrl;
            }

            return [
                'title'       => $item->title,
                'description' => $item->description ?? 'Sin descripción adicional.',
                'type'        => $item->type ?? $defaultType,
                'date'        => $item->scheduled_at ? $item->scheduled_at->format('d/m/Y h:i A') : 'Fecha no definida',
                'document'    => $docUrl,
                'requirements'=> $reqUrl,
                'photos'      => array_values($cleanPhotos),
                'cover'       => $cardCover,
                'isPast'      => $isPastEvent,
            ];
        })->values()->toArray();
    };

    $jsonPorHacer     = isset($capacitacionesPorHacer) ? $processActivityCollection($capacitacionesPorHacer, 'capacitacion', false) : [];
    $jsonHechas       = isset($capacitacionesHechas)   ? $processActivityCollection($capacitacionesHechas,   'capacitacion', true)  : [];
    $jsonCoordinaciones = isset($coordinacionesHechas) ? $processActivityCollection($coordinacionesHechas,   'coordinacion', true)  : [];
@endphp

<script>
    window.portalPorHacer      = @json($jsonPorHacer);
    window.portalHechas        = @json($jsonHechas);
    window.portalCoordinaciones = @json($jsonCoordinaciones);
</script>

{{-- ════════════════ ACTIVIDADES + VISUALIZADOR INTEGRADO ════════ --}}
{{-- FIX: un único x-data contenedor para viewModal + isImageUrl   --}}
@if(isset($capacitacionesPorHacer) || isset($capacitacionesHechas) || isset($coordinacionesHechas))
<div class="section-deep py-16"
     x-data="{
         limitPorHacer: 3,
         limitHechas: 3,
         limitCoordinaciones: 3,
         viewModal: false,
         selectedWorkshop: null,
         galleryIndex: 0,

         openViewer(data) {
             this.selectedWorkshop = data;
             this.galleryIndex = 0;
             this.viewModal = true;
             document.body.style.overflow = 'hidden';
         },
         closeViewer() {
             this.viewModal = false;
             this.selectedWorkshop = null;
             document.body.style.overflow = '';
         },
         isImageUrl(url) {
             if (!url) return false;
             const clean = url.split('?')[0].split('#')[0].toLowerCase();
             return clean.endsWith('.png') || clean.endsWith('.jpg') || clean.endsWith('.jpeg') || clean.endsWith('.webp');
         }
     }">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        {{-- ════════ SECCIÓN A: PRÓXIMAS / POR HACER ════════ --}}
        @if(isset($capacitacionesPorHacer) && $capacitacionesPorHacer->count() > 0)
        <div id="seccion-por-hacer" class="space-y-6">
            <div class="flex items-center gap-3 bg-slate-950/50 border border-white/05 p-4 rounded-2xl shadow-xl">
                <div class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-ping flex-shrink-0"></div>
                <h3 class="text-sm font-black text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-hourglass-start text-blue-500"></i> Próximos Talleres y Capacitaciones Programadas
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($capacitacionesPorHacer as $item)
                <div x-show="{{ $loop->index }} < limitPorHacer"
                     @click="openViewer(window.portalPorHacer[{{ $loop->index }}])"
                     class="relative rounded-2xl overflow-hidden group cursor-pointer shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-slate-950 h-[260px]">

                    <div class="absolute inset-0 w-full h-full z-0">
                        @php $wPH = $jsonPorHacer[$loop->index] ?? null; @endphp
                        @if($wPH && $wPH['cover'] && preg_match('/\.(png|jpg|jpeg|webp)$/i', $wPH['cover']))
                            <img src="{{ $wPH['cover'] }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @elseif($wPH && $wPH['cover'])
                            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 to-blue-950 flex flex-col items-center justify-center p-4 text-center select-none">
                                <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-red-400 mb-2"><i class="fa-solid fa-file-pdf text-xl"></i></div>
                                <span class="text-[10px] font-mono font-black text-slate-400 uppercase tracking-widest">Documento Convocatoria PDF</span>
                            </div>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-950 via-slate-900 to-slate-950 flex items-center justify-center">
                                <i class="fa-solid fa-calendar-days text-white/5 text-6xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/65 to-blue-950/20 z-10"></div>

                    <div class="absolute bottom-0 left-0 right-0 z-20 p-4 space-y-2">
                        <p class="text-slate-400 text-[10px] font-bold"><i class="fa-regular fa-clock mr-1 text-blue-400"></i>{{ $item->scheduled_at->format('d/m/Y') }}</p>
                        <h4 class="text-white font-black text-sm uppercase leading-snug line-clamp-2 group-hover:text-blue-300 transition-colors tracking-tight">{{ $item->title }}</h4>
                        <div class="flex items-center justify-between pt-1 border-t border-white/10 text-[10px] text-blue-400 font-black">
                            <span>Ver requisitos e inscripción</span>
                            <i class="fa-solid fa-arrow-right text-[8px]"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($capacitacionesPorHacer->count() > 3)
            <div class="text-center pt-2" x-show="limitPorHacer < {{ $capacitacionesPorHacer->count() }}">
                <button type="button" @click="limitPorHacer += 3" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-950 border border-white/10 hover:border-blue-500/50 text-slate-400 hover:text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all shadow-md">
                    <i class="fa-solid fa-plus text-blue-500"></i> Ver 3 capacitaciones más
                </button>
            </div>
            @endif
        </div>
        @endif

        {{-- ════════ SECCIÓN B: RECIÉN EJECUTADAS ════════ --}}
        @if(isset($capacitacionesHechas) && $capacitacionesHechas->count() > 0)
        <div id="seccion-hechas" class="space-y-6">
            <div class="flex items-center gap-3 bg-slate-950/50 border border-white/05 p-4 rounded-2xl shadow-xl">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 flex-shrink-0"></div>
                <h3 class="text-sm font-black text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i> Registro de Capacitaciones Ejecutadas con Éxito
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($capacitacionesHechas as $taller)
                <div x-show="{{ $loop->index }} < limitHechas"
                     @click="openViewer(window.portalHechas[{{ $loop->index }}])"
                     class="relative rounded-2xl overflow-hidden group cursor-pointer shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-slate-950 h-[260px]">

                    <div class="absolute inset-0 w-full h-full z-0">
                        @php $wH = $jsonHechas[$loop->index] ?? null; @endphp
                        @if($wH && $wH['cover'] && preg_match('/\.(png|jpg|jpeg|webp)$/i', $wH['cover']))
                            <img src="{{ $wH['cover'] }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                        @elseif($wH && $wH['cover'])
                            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 to-emerald-950 flex flex-col items-center justify-center p-4 text-center">
                                <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-emerald-400 mb-2"><i class="fa-solid fa-file-contract text-xl"></i></div>
                                <span class="text-[10px] font-mono font-black text-slate-400 uppercase tracking-widest">Documento de Informe</span>
                            </div>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-slate-800 via-slate-700 to-slate-900 flex items-center justify-center">
                                <i class="fa-solid fa-camera text-white/5 text-6xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-slate-900/20 z-10"></div>

                    <div class="absolute bottom-0 left-0 right-0 z-20 p-4 space-y-2">
                        <p class="text-slate-400 text-[10px] font-bold"><i class="fa-regular fa-calendar-check mr-1 text-emerald-400"></i>{{ $taller->scheduled_at->format('d/m/Y') }}</p>
                        <h4 class="text-white font-black text-sm uppercase leading-snug line-clamp-2 group-hover:text-emerald-300 transition-colors">{{ $taller->title }}</h4>
                        <div class="flex items-center justify-between pt-1 border-t border-white/10 text-[10px] text-emerald-400 font-black">
                            <span>Ver evidencias de cumplimiento</span>
                            <i class="fa-solid fa-arrow-right text-[8px]"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($capacitacionesHechas->count() > 3)
            <div class="text-center pt-2" x-show="limitHechas < {{ $capacitacionesHechas->count() }}">
                <button type="button" @click="limitHechas += 3" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-950 border border-white/10 hover:border-emerald-500/50 text-slate-400 hover:text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all shadow-md">
                    <i class="fa-solid fa-plus text-emerald-500"></i> Ver 3 históricos más
                </button>
            </div>
            @endif
        </div>
        @endif

        {{-- ════════ SECCIÓN C: COORDINACIONES ════════ --}}
        @if(isset($coordinacionesHechas) && $coordinacionesHechas->count() > 0)
        <div id="seccion-coordinaciones" class="space-y-6">
            <div class="flex items-center gap-3 bg-slate-950/50 border border-white/05 p-4 rounded-2xl shadow-xl">
                <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 flex-shrink-0"></div>
                <h3 class="text-sm font-black text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-handshake text-indigo-400"></i> Coordinaciones e Informes Interinstitucionales
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($coordinacionesHechas as $coor)
                <div x-show="{{ $loop->index }} < limitCoordinaciones"
                     @click="openViewer(window.portalCoordinaciones[{{ $loop->index }}])"
                     class="relative rounded-2xl overflow-hidden group cursor-pointer shadow-xl hover:-translate-y-1.5 transition-all duration-300 bg-slate-950 h-[260px]">

                    <div class="absolute inset-0 w-full h-full z-0">
                        @php $wC = $jsonCoordinaciones[$loop->index] ?? null; @endphp
                        @if($wC && $wC['cover'] && preg_match('/\.(png|jpg|jpeg|webp)$/i', $wC['cover']))
                            <img src="{{ $wC['cover'] }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                        @elseif($wC && $wC['cover'])
                            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 to-indigo-950 flex flex-col items-center justify-center p-4 text-center">
                                <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-indigo-400 mb-2"><i class="fa-solid fa-file-invoice text-xl"></i></div>
                                <span class="text-[10px] font-mono font-black text-slate-400 uppercase tracking-widest">Acta Digital PDF</span>
                            </div>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-950 flex items-center justify-center">
                                <i class="fa-solid fa-handshake text-white/5 text-5xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/55 to-indigo-950/20 z-10"></div>

                    <div class="absolute bottom-0 left-0 right-0 z-20 p-4 space-y-2">
                        <p class="text-slate-400 text-[10px] font-bold"><i class="fa-regular fa-calendar mr-1 text-indigo-400"></i>{{ $coor->scheduled_at->format('d/m/Y') }}</p>
                        <h4 class="text-white font-black text-sm uppercase leading-snug line-clamp-2 group-hover:text-indigo-300 transition-colors pl-2 border-l-2 border-indigo-500">{{ $coor->title }}</h4>
                        <div class="flex items-center justify-between pt-1 border-t border-white/10 text-[10px] text-indigo-400 font-black">
                            <span>Revisar actas y acuerdos</span>
                            <i class="fa-solid fa-arrow-right text-[8px]"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($coordinacionesHechas->count() > 3)
            <div class="text-center pt-2" x-show="limitCoordinaciones < {{ $coordinacionesHechas->count() }}">
                <button type="button" @click="limitCoordinaciones += 3" class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-950 border border-white/10 hover:border-indigo-500/50 text-slate-400 hover:text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all shadow-md">
                    <i class="fa-solid fa-plus text-indigo-500"></i> Ver 3 actas más
                </button>
            </div>
            @endif
        </div>
        @endif

    </div>{{-- /max-w-7xl --}}

    {{-- ════════ VISUALIZADOR MULTIMEDIA (único, dentro del x-data padre) ════════ --}}
    <div class="fixed inset-0 w-full h-full z-[9999] flex items-center justify-center p-2 sm:p-4 bg-slate-950/85 backdrop-blur-md"
         x-show="viewModal" x-cloak x-transition
         @keydown.escape.window="closeViewer()">

        <div class="relative bg-white rounded-2xl w-full max-w-5xl shadow-2xl flex flex-col overflow-hidden border border-slate-200 h-[82vh] max-h-[620px]"
             @click.away="closeViewer()">

            <template x-if="selectedWorkshop">
                <div class="w-full h-full flex flex-col justify-between">

                    <div class="bg-slate-950 px-5 py-3.5 border-b border-white/5 flex items-center justify-between shrink-0">
                        <span class="text-[9px] font-mono font-black text-red-500 uppercase tracking-widest flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span>
                            Ficha de Actividades Institucionales DRTPE
                        </span>
                        <button @click="closeViewer()" class="text-slate-400 hover:text-white transition text-xs font-black uppercase tracking-wider flex items-center gap-1 bg-transparent border-none cursor-pointer">
                            Cerrar <i class="fa-solid fa-xmark text-sm text-red-500"></i>
                        </button>
                    </div>

                    <div class="flex-1 flex flex-col md:flex-row items-stretch overflow-hidden bg-slate-950">

                        {{-- PANEL IZQUIERDO: VISUALIZADOR --}}
                        <div class="w-full md:w-[62%] bg-slate-950 flex items-center justify-center relative overflow-hidden h-1/2 md:h-full border-b md:border-b-0 md:border-r border-white/5">

                            {{-- Evento pasado: mostrar galería de fotos --}}
                            <template x-if="selectedWorkshop.isPast">
                                <div class="w-full h-full flex flex-col justify-between p-4">
                                    <div class="flex-1 relative flex items-center justify-center">

                                        <template x-if="selectedWorkshop.photos.length === 0">
                                            <div class="w-full h-full flex items-center justify-center">
                                                <template x-if="selectedWorkshop.document && isImageUrl(selectedWorkshop.document)">
                                                    <img :src="selectedWorkshop.document" class="max-w-full max-h-full object-contain rounded-xl shadow-2xl">
                                                </template>
                                                <template x-if="selectedWorkshop.document && !isImageUrl(selectedWorkshop.document)">
                                                    <iframe :src="selectedWorkshop.document + '#toolbar=0&navpanes=0'" class="w-full h-full border-none bg-white rounded-xl"></iframe>
                                                </template>
                                                <template x-if="!selectedWorkshop.document">
                                                    <p class="text-slate-500 font-bold text-xs uppercase tracking-wider"><i class="fa-solid fa-images mr-1"></i> Evidencias fotográficas en proceso</p>
                                                </template>
                                            </div>
                                        </template>

                                        <template x-if="selectedWorkshop.photos.length > 0">
                                            <div class="w-full h-full flex items-center justify-center relative">
                                                <button x-show="selectedWorkshop.photos.length > 1"
                                                        @click="galleryIndex = (galleryIndex - 1 + selectedWorkshop.photos.length) % selectedWorkshop.photos.length"
                                                        class="absolute left-2 w-9 h-9 rounded-xl bg-black/50 hover:bg-black/70 text-white flex items-center justify-center border-none cursor-pointer z-10">
                                                    <i class="fa-solid fa-chevron-left text-xs"></i>
                                                </button>
                                                <img :src="selectedWorkshop.photos[galleryIndex]" class="max-w-full max-h-[50vh] object-contain shadow-2xl rounded-xl border border-white/5">
                                                <button x-show="selectedWorkshop.photos.length > 1"
                                                        @click="galleryIndex = (galleryIndex + 1) % selectedWorkshop.photos.length"
                                                        class="absolute right-2 w-9 h-9 rounded-xl bg-black/50 hover:bg-black/70 text-white flex items-center justify-center border-none cursor-pointer z-10">
                                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <template x-if="selectedWorkshop.photos.length > 1">
                                        <div class="h-14 flex items-center justify-center gap-2 overflow-x-auto py-1 border-t border-white/5 shrink-0 scrollbar-none">
                                            <template x-for="(pic, idx) in selectedWorkshop.photos" :key="idx">
                                                <div @click="galleryIndex = idx"
                                                     class="h-10 aspect-video rounded-md overflow-hidden border cursor-pointer transition-all shrink-0"
                                                     :class="galleryIndex === idx ? 'border-amber-500 scale-105 shadow-md' : 'border-white/10 opacity-40 hover:opacity-100'">
                                                    <img :src="pic" class="w-full h-full object-cover">
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            {{-- Evento futuro: mostrar afiche/documento --}}
                            <template x-if="!selectedWorkshop.isPast">
                                <div class="w-full h-full flex items-center justify-center">

                                    <template x-if="selectedWorkshop.document && isImageUrl(selectedWorkshop.document)">
                                        <div class="w-full h-full p-4 flex items-center justify-center">
                                            <img :src="selectedWorkshop.document" class="w-full h-full object-contain shadow-2xl rounded-lg">
                                        </div>
                                    </template>

                                    <template x-if="selectedWorkshop.document && !isImageUrl(selectedWorkshop.document)">
                                        <iframe :key="selectedWorkshop.title" :src="selectedWorkshop.document + '#toolbar=0&navpanes=0&view=Fit'" class="w-full h-full border-none bg-white" allow="autoplay"></iframe>
                                    </template>

                                    <template x-if="!selectedWorkshop.document && selectedWorkshop.photos && selectedWorkshop.photos.length > 0">
                                        <div class="w-full h-full p-4 flex items-center justify-center">
                                            <img :src="selectedWorkshop.photos[0]" class="w-full h-full object-contain shadow-2xl rounded-lg">
                                        </div>
                                    </template>

                                    <template x-if="!selectedWorkshop.document && (!selectedWorkshop.photos || selectedWorkshop.photos.length === 0)">
                                        <div class="p-8 text-center text-slate-500 font-medium space-y-2">
                                            <div class="w-12 h-12 bg-white/5 border border-white/10 rounded-full flex items-center justify-center mx-auto text-slate-400"><i class="fa-solid fa-file-circle-xmark text-lg"></i></div>
                                            <p class="text-xs font-mono font-black uppercase tracking-wider">Afiche promocional no cargado</p>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- PANEL DERECHO: DATOS INSTITUCIONALES --}}
                        <div class="w-full md:w-[38%] bg-white flex flex-col justify-between p-5 sm:p-6 overflow-y-auto h-1/2 md:h-full text-slate-800">
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <span class="bg-slate-100 text-slate-600 border border-slate-200/60 font-mono text-[9px] font-black uppercase px-2 py-0.5 rounded" x-text="selectedWorkshop.date"></span>
                                    <h3 class="text-slate-900 font-black text-base sm:text-lg tracking-tight uppercase leading-snug break-words" x-text="selectedWorkshop.title"></h3>
                                    <hr class="border-slate-100 my-2">
                                    <p class="text-slate-600 text-xs font-medium leading-relaxed bg-slate-50 p-3 border border-slate-100 rounded-xl max-h-40 overflow-y-auto scrollbar-thin" x-text="selectedWorkshop.description"></p>
                                </div>

                                <div x-show="selectedWorkshop.isPast && selectedWorkshop.document" class="space-y-2 pt-3 border-t border-slate-100">
                                    <p class="text-slate-400 text-[9px] font-mono font-black uppercase tracking-wider">
                                        <i class="fa-solid fa-file-invoice text-red-600"></i> Afiche / Convocatoria de Origen:
                                    </p>
                                    <template x-if="selectedWorkshop.document && isImageUrl(selectedWorkshop.document)">
                                        <a :href="selectedWorkshop.document" target="_blank" class="block w-32 aspect-video border border-slate-200 bg-slate-50 rounded-lg overflow-hidden shadow-sm hover:border-amber-500/50 transition">
                                            <img :src="selectedWorkshop.document" class="w-full h-full object-contain">
                                        </a>
                                    </template>
                                    <template x-if="selectedWorkshop.document && !isImageUrl(selectedWorkshop.document)">
                                        <a :href="selectedWorkshop.document" target="_blank" class="inline-flex items-center gap-2 bg-slate-50 border border-slate-200 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:text-red-600 transition">
                                            <i class="fa-solid fa-file-pdf text-red-500"></i> Ver Convocatoria Base
                                        </a>
                                    </template>
                                </div>

                                <div x-show="!selectedWorkshop.isPast && selectedWorkshop.requirements" class="pt-2">
                                    <a :href="selectedWorkshop.requirements" target="_blank"
                                       class="w-full bg-slate-900 hover:bg-red-600 text-white text-[10px] font-black uppercase tracking-wider py-2.5 rounded-xl flex items-center justify-center gap-2 transition shadow">
                                        <i class="fa-solid fa-file-pdf"></i> Descargar Bases de Inscripción
                                    </a>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 mt-4 md:mt-0" x-show="selectedWorkshop.document">
                                <a :href="selectedWorkshop.document" target="_blank"
                                   class="w-full bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-black uppercase tracking-wider py-3 rounded-xl flex items-center justify-center gap-2 transition border border-slate-200 shadow-sm">
                                    <i class="fa-solid fa-file-arrow-down text-red-500"></i> Descargar Archivo Oficial
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </template>
        </div>
    </div>{{-- /visualizador --}}

</div>{{-- /section-deep x-data --}}
@endif


    {{-- ── CRONOLOGÍA ────────────────────────────────────────── --}}
    <div class="bg-scene relative">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-[1px]"></div>
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
            @if(isset($actividades))
            @foreach($actividades as $aIdx => $actividad)
            @if($actividad->subEvents->count() > 0)
            @php
                $latestSub = $actividad->subEvents->first();
                $restSub   = $actividad->subEvents->skip(1)->values();
            @endphp
            <article id="actividad-{{ $aIdx }}"
                     x-show="{{ $aIdx }} < limit"
                     x-transition.opacity.duration.500ms
                     style="display:{{ $aIdx < 10 ? 'block' : 'none' }}">

                <div class="activity-header p-5 shadow-xl mb-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 relative z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-400/20 border border-amber-400/30 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-folder-open text-amber-400 text-sm"></i></div>
                            <h3 class="text-base sm:text-lg font-black text-white leading-snug">{{ $actividad->description }}</h3>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap flex-shrink-0">
                            <span class="bg-amber-400/18 border border-amber-400/30 text-amber-300 text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest">PP: {{ $actividad->category->pp_code ?? '000' }}</span>
                            <span class="bg-white/10 border border-white/15 text-blue-200 text-[9px] font-bold px-3 py-1.5 rounded-lg">{{ $actividad->subEvents->count() }} {{ $actividad->subEvents->count()===1?'registro':'registros' }}</span>
                        </div>
                    </div>
                </div>

                <div class="relative timeline-rail ml-5 sm:ml-8 pl-6 sm:pl-10 space-y-5 pt-5 pb-3"
                     x-data="{ expanded:false }" id="timeline-section-{{ $aIdx }}">

                    <!-- Latest sub-event (always visible) -->
                    <div id="subevent-{{ $latestSub->id }}"
                         data-activity-idx="{{ $aIdx }}" data-is-latest="1"
                         class="relative reporte-wrapper group">
                        <div class="timeline-node"></div>
                        <div class="subevent-card">
                            <div class="bg-gradient-to-r from-red-600 to-red-700 px-5 py-2 flex items-center gap-2">
                                <i class="fa-solid fa-star text-amber-300 text-xs"></i>
                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Registro más reciente</span>
                                <div class="ml-auto text-white/60 text-[10px] font-medium">{{ \Carbon\Carbon::parse($latestSub->event_date)->format('d M. Y') }}</div>
                            </div>
                            <div class="p-5 sm:p-7">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200"><i class="fa-regular fa-calendar text-red-600 mr-1"></i>{{ \Carbon\Carbon::parse($latestSub->event_date)->format('d M. Y') }}</span>
                                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200"><i class="fa-solid fa-users mr-1"></i>{{ $latestSub->attendees_count }} Asistentes</span>
                                </div>
                                <h4 class="text-xl sm:text-2xl font-black text-slate-900 mb-4 leading-tight">{{ $latestSub->report_title }}</h4>
                                @if($latestSub->comment)
                                <div class="bg-slate-50 border-l-4 border-slate-300 rounded-r-xl p-4 mb-5"><p class="text-slate-700 text-sm leading-relaxed font-medium">{{ $latestSub->comment }}</p></div>
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
                                    <button type="button" class="btn-mostrar-mas mt-3 w-full py-3 bg-white border border-slate-200 text-slate-600 text-xs font-bold uppercase rounded-xl flex items-center justify-center gap-2 shadow-sm hover:bg-slate-50 transition">
                                        <i class="fa-solid fa-images text-red-500"></i><span>Ver {{ count($latestSub->photos_sorted)-4 }} fotografías adicionales</span>
                                    </button>
                                    @endif
                                </div>
                                @endif

                                @if($latestSub->youtube_url ?? null)
                                @php preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $latestSub->youtube_url, $mYt); $ytId = $mYt[1] ?? null; @endphp
                                @if($ytId)
                                <div class="mt-6 video-preview-container rounded-2xl shadow-lg border-4 border-slate-100 bg-slate-900 overflow-hidden" id="vc-{{ $latestSub->id }}">
                                    <img src="https://img.youtube.com/vi/{{ $ytId }}/maxresdefault.jpg" onerror="this.src='https://img.youtube.com/vi/{{ $ytId }}/hqdefault.jpg'" class="video-thumbnail w-full h-56 sm:h-72 object-cover opacity-90" loading="lazy">
                                    <div class="play-button" onclick="playVideo(this,'{{ $ytId }}','vc-{{ $latestSub->id }}')"><svg class="w-10 h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div>
                                    <iframe class="video-iframe w-full h-56 sm:h-72" style="display:none;" allow="autoplay;encrypted-media;picture-in-picture" allowfullscreen></iframe>
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($restSub->count() > 0)
                    <button id="expand-toggle-{{ $aIdx }}" @click="expanded=!expanded"
                            class="w-full py-2.5 flex items-center justify-center gap-2 rounded-xl border text-xs font-bold uppercase tracking-wide transition-all"
                            :class="expanded?'bg-slate-700/40 border-slate-600/40 text-slate-300 hover:bg-slate-600/40':'bg-white/06 border-white/10 text-slate-400 hover:bg-white/10 hover:text-slate-200'">
                        <i class="fa-solid transition-transform duration-300" :class="expanded?'fa-chevron-up':'fa-list-ul'"></i>
                        <span x-text="expanded?'Ocultar registros anteriores':'Ver {{ $restSub->count() }} registro(s) anterior(es) de esta actividad'"></span>
                    </button>

                    <div x-show="expanded" x-transition class="space-y-5">
                        @foreach($restSub as $reporte)
                        <div id="subevent-{{ $reporte->id }}"
                             data-activity-idx="{{ $aIdx }}" data-is-latest="0"
                             class="relative reporte-wrapper group">
                            <div class="timeline-node" style="background:#475569;"></div>
                            <div class="subevent-card border border-slate-100">
                                <div class="p-5 sm:p-7">
                                    <div class="flex flex-wrap items-center gap-3 mb-4">
                                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200"><i class="fa-regular fa-calendar text-slate-500 mr-1"></i>{{ \Carbon\Carbon::parse($reporte->event_date)->format('d M. Y') }}</span>
                                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200"><i class="fa-solid fa-users mr-1"></i>{{ $reporte->attendees_count }} Asistentes</span>
                                    </div>
                                    <h4 class="text-lg sm:text-xl font-black text-slate-800 mb-4 leading-snug">{{ $reporte->report_title }}</h4>
                                    @if($reporte->comment)<div class="bg-slate-50 border-l-4 border-slate-200 rounded-r-xl p-4 mb-5"><p class="text-slate-600 text-sm leading-relaxed font-medium">{{ $reporte->comment }}</p></div>@endif

                                    @if(isset($reporte->photos_sorted) && count($reporte->photos_sorted) > 0)
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                        <div class="grid grid-cols-2 gap-2 sm:gap-3 galeria-fotos">
                                            @foreach($reporte->photos_sorted as $pi=>$foto)
                                            <div class="foto-item relative overflow-hidden bg-slate-200 rounded-xl {{ $pi>=4?'foto-extra':'' }} border-2 border-white shadow-sm">
                                                <img src="{{ asset('storage/'.$foto) }}" class="foto-galeria w-full h-36 sm:h-52 object-cover" loading="lazy">
                                            </div>
                                            @endforeach
                                        </div>
                                        @if(count($reporte->photos_sorted) > 4)
                                        <button type="button" class="btn-mostrar-mas mt-3 w-full py-3 bg-white border border-slate-200 text-slate-600 text-xs font-bold uppercase rounded-xl flex items-center justify-center gap-2 hover:bg-slate-50 transition">
                                            <i class="fa-solid fa-images text-red-500"></i><span>Ver {{ count($reporte->photos_sorted)-4 }} fotografías adicionales</span>
                                        </button>
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
            @endif
            </div>

            @if(isset($actividades) && count($actividades) > 10)
            <div class="text-center mt-14" x-show="limit < {{ count($actividades) }}">
                <button @click="limit+=10" class="bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest px-10 py-4 rounded-full border border-red-500/30 shadow-lg hover:-translate-y-0.5 transition-all">
                    <i class="fa-solid fa-plus mr-2"></i> Cargar 10 actividades más
                </button>
            </div>
            @endif
        </section>
    </div>

    {{-- ── COMUNICADOS TABLÓN ────────────────────────────────── --}}
    @if(isset($comunicadosActivos) && $comunicadosActivos->count() > 0)
    <section class="bg-slate-900/50 backdrop-blur-md border-t border-white/10 py-14"
             x-data="{
                 active: 0,
                 count: {{ $comunicadosActivos->count() }},
                 init() { if(this.count > 1) { setInterval(() => { this.active = (this.active+1)%this.count; }, 5000); } }
             }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6 bg-slate-950/50 p-4 rounded-2xl border border-white/05">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse flex-shrink-0"></div>
                    <h2 class="text-sm font-black text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-bullhorn text-amber-500"></i> Tablón de Comunicados Oficiales
                    </h2>
                </div>
                <div class="text-xs font-mono text-slate-400 font-bold bg-black/40 px-3 py-1 rounded-md border border-white/05">
                    <span x-text="active+1"></span> / <span x-text="count"></span>
                </div>
            </div>

            <div class="relative bg-slate-950/70 border border-white/10 rounded-3xl overflow-hidden shadow-2xl h-[580px] sm:h-[450px] md:h-[360px]">

                @foreach($comunicadosActivos as $index => $comunicado)
                <div x-show="active === {{ $index }}"
                     x-transition:enter="transition-opacity duration-500 ease-in-out"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-400 ease-in-out"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 w-full h-full flex flex-col md:flex-row items-stretch"
                     x-cloak>

                    {{-- Preview del comunicado --}}
                    <div class="w-full md:w-[45%] flex-shrink-0 bg-slate-950 border-b md:border-b-0 md:border-r border-white/05 flex items-center justify-center relative overflow-hidden h-48 sm:h-64 md:h-full">
                        @if($comunicado->file_type === 'image')
                            <img src="{{ asset('storage/'.$comunicado->file_path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-900">
                                <iframe src="{{ asset('storage/'.$comunicado->file_path) }}#toolbar=0&navpanes=0&scrollbar=0"
                                        class="w-full h-full border-none"
                                        allow="autoplay"></iframe>
                            </div>
                        @endif
                    </div>

                    {{-- Info + adjuntos --}}
                    <div class="flex-1 p-6 sm:p-8 flex flex-col justify-between overflow-y-auto scrollbar-thin">
                        <div>
                            <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 font-mono text-[9px] font-black uppercase px-2.5 py-1 rounded-md">Comunicado Activo</span>
                            <h3 class="text-white font-black text-xl sm:text-2xl leading-tight mt-3 mb-3">{{ $comunicado->title }}</h3>
                            <p class="text-slate-400 text-xs sm:text-sm font-medium leading-relaxed line-clamp-3 mb-4">{{ $comunicado->description ?? 'Comunicado oficial de la institución.' }}</p>

                            @if(isset($comunicado->attachments) && is_array($comunicado->attachments) && count($comunicado->attachments) > 0)
                            <div class="space-y-2 mb-4">
                                <p class="text-slate-500 text-[10px] font-black uppercase tracking-wider mb-2">
                                    <i class="fa-solid fa-paperclip mr-1"></i> Documentos adjuntos
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($comunicado->attachments as $indexAnexo => $adj)
                                    <a href="{{ asset('storage/'.$adj) }}" target="_blank"
                                       class="flex items-center gap-2.5 bg-slate-800/60 hover:bg-slate-800 border border-white/08 hover:border-white/18 rounded-xl px-3 py-2 text-xs font-bold text-slate-300 hover:text-white transition group truncate">
                                        <div class="w-7 h-7 bg-red-600/15 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid {{ str_ends_with(strtolower($adj), '.pdf') ? 'fa-file-pdf text-red-400' : 'fa-image text-blue-400' }} text-[11px]"></i>
                                        </div>
                                        <span class="truncate flex-1">Anexo N° {{ $indexAnexo + 1 }}</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-slate-500 group-hover:text-white text-[10px]"></i>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-white/05 mt-auto">
                            <a href="{{ asset('storage/'.$comunicado->file_path) }}" target="_blank"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-black uppercase tracking-wider shadow transition">
                                <i class="fa-solid fa-file-arrow-down"></i> Descargar Principal
                            </a>
                            <span class="text-slate-500 text-[10px] font-bold"><i class="fa-regular fa-calendar mr-1"></i>{{ $comunicado->published_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            @if($comunicadosActivos->count() > 1)
            <div class="flex justify-center gap-1.5 mt-4">
                @foreach($comunicadosActivos as $index => $c)
                <button @click="active = {{ $index }}"
                        class="h-2 rounded-full transition-all duration-300"
                        :class="active === {{ $index }} ? 'bg-amber-500 w-5 shadow-[0_0_8px_#f59e0b]' : 'bg-white/20 w-2'"></button>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- ── FOOTER INFO ───────────────────────────────────────── --}}
    <section class="footer-light border-t border-slate-300">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-12">
            <div class="flex items-center gap-4 mb-10"><h2 class="text-2xl font-black text-slate-800">Medios e Información</h2><div class="flex-1 h-px bg-slate-300"></div></div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Facebook -->
                <div class="lg:col-span-4">
                    <div class="flex items-center gap-2 mb-4"><i class="fa-brands fa-facebook text-blue-600 text-lg"></i><h4 class="text-xs font-black text-slate-600 uppercase tracking-wider">Facebook</h4></div>
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-slate-200" style="height:480px;">
                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FDRTPEPunoOFICIAL&tabs=timeline&width=340&height=480&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true&appId"
                                width="100%" height="480" style="border:none;overflow:hidden;" scrolling="no" frameborder="0"
                                allowfullscreen allow="autoplay;clipboard-write;encrypted-media;picture-in-picture;web-share"></iframe>
                    </div>
                </div>

                <!-- TikTok + Boletines + Contacto -->
                <div class="lg:col-span-8 lg:pl-10 lg:border-l lg:border-slate-300 space-y-8">

                    <!-- TikTok -->
                    <div>
                        <div class="flex items-center gap-2 mb-4"><i class="fa-brands fa-tiktok text-slate-900 text-lg"></i><h4 class="text-xs font-black text-slate-600 uppercase tracking-wider">TikTok</h4></div>
                        <a href="#" target="_blank" class="flex items-center gap-5 bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 border border-slate-700/60 hover:border-slate-500/80 transition group shadow-lg">
                            <div class="w-14 h-14 rounded-2xl bg-black flex items-center justify-center border border-white/10 flex-shrink-0"><i class="fa-brands fa-tiktok text-white text-2xl"></i></div>
                            <div class="flex-1 min-w-0"><p class="text-white font-black text-base group-hover:text-slate-200 transition">@DTREPuno</p><p class="text-slate-400 text-xs mt-1">Síganos en TikTok para ver nuestras actividades en formato corto.</p></div>
                            <div class="w-9 h-9 rounded-xl bg-white/08 border border-white/12 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-arrow-up-right-from-square text-white/60 text-xs"></i></div>
                        </a>
                    </div>

                    <!-- Boletines -->
                    <div>
                        <div class="flex items-center gap-2 mb-4"><i class="fa-solid fa-newspaper text-red-600 text-lg"></i><h4 class="text-xs font-black text-slate-600 uppercase tracking-wider">Boletines Informativos</h4></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if(isset($bulletins) && $bulletins->count())
                                @forelse($bulletins as $boletin)
                                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-md flex flex-col" style="height:360px;">
                                    <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 flex justify-between items-center shrink-0">
                                        <span class="text-xs font-black text-slate-800 truncate max-w-[70%]"><i class="fa-solid fa-file-pdf text-red-600 mr-1.5"></i>{{ $boletin->title }}</span>
                                        <a href="{{ asset('storage/'.$boletin->file_path) }}" target="_blank" class="text-[10px] bg-red-600 text-white px-2 py-0.5 rounded font-bold uppercase hover:bg-red-700"><i class="fa-solid fa-expand"></i></a>
                                    </div>
                                    <div class="flex-1 w-full bg-slate-100"><iframe src="{{ asset('storage/'.$boletin->file_path) }}#toolbar=0&navpanes=0&scrollbar=0" width="100%" height="100%" class="border-none"></iframe></div>
                                </div>
                                @empty
                                <div class="col-span-2 bg-white border border-slate-200 rounded-2xl flex flex-col items-center justify-center p-8 text-slate-400 text-xs font-bold uppercase tracking-wider"><i class="fa-solid fa-folder-open text-xl mb-2 text-slate-300"></i> No hay boletines publicados</div>
                                @endforelse
                            @else
                                <div class="bg-white border border-slate-200 rounded-2xl flex flex-col items-center justify-center p-8 shadow-sm hover:shadow-lg transition group cursor-pointer hover:-translate-y-1">
                                    <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mb-3 border border-red-100"><i class="fa-solid fa-file-pdf text-2xl text-red-500"></i></div>
                                    <p class="text-slate-800 font-bold text-sm">Boletín 001</p><p class="text-slate-400 text-xs mt-1">Próximamente disponible</p>
                                </div>
                                <div class="bg-white border border-slate-200 rounded-2xl flex flex-col items-center justify-center p-8 shadow-sm hover:shadow-lg transition group cursor-pointer hover:-translate-y-1">
                                    <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mb-3 border border-red-100"><i class="fa-solid fa-file-pdf text-2xl text-red-500"></i></div>
                                    <p class="text-slate-800 font-bold text-sm">Boletín 002</p><p class="text-slate-400 text-xs mt-1">Próximamente disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="bg-slate-800 rounded-2xl p-6 shadow-lg">
                        <h5 class="text-white font-black text-xs uppercase tracking-wider mb-5 flex items-center gap-2"><i class="fa-solid fa-headset text-red-400"></i> Contáctenos</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                            <div class="flex items-start gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-red-600/20 border border-red-500/25 flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fa-solid fa-location-dot text-red-400 text-xs"></i></div>
                                <div><p class="text-slate-200 text-xs font-bold">Sede Puno</p><p class="text-slate-500 text-xs leading-snug mt-0.5">Jr. Ayacucho N° 658, Puno</p></div>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-blue-600/20 border border-blue-500/25 flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fa-solid fa-location-dot text-blue-400 text-xs"></i></div>
                                <div><p class="text-slate-200 text-xs font-bold">Sede Juliaca</p><p class="text-slate-500 text-xs leading-snug mt-0.5">Jr. Santiago Mamani N° 200, Juliaca</p></div>
                            </div>
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            <a href="https://www.facebook.com/DRTPEPunoOFICIAL/?locale=es_LA" target="_blank" class="social-badge badge-fb"><i class="fa-brands fa-facebook text-base"></i> Facebook</a>
                            <a href="#" target="_blank" class="social-badge badge-tt"><i class="fa-brands fa-tiktok text-base"></i> TikTok</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer bottom -->
    <footer class="bg-slate-950 text-slate-600 py-8 text-center border-t border-white/05">
        <div class="max-w-5xl mx-auto px-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-7 h-7 object-contain mx-auto mb-3 opacity-25">
            <p class="font-black uppercase tracking-widest text-slate-500 text-[10px] mb-1">Dirección Regional de Trabajo y Promoción del Empleo Puno</p>
            <p class="text-xs">&copy; {{ date('Y') }} Todos los derechos reservados.</p>
        </div>
    </footer>

</div>{{-- /main-content --}}


{{-- ════════════════════════════════════════════════════════════ --}}
{{-- PHOTO REPORT MODAL (sliders)                                --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<div x-data="{ open:false, report:null, photoIndex:0 }"
     @open-modal.window="report=$event.detail.report;photoIndex=0;open=true;document.body.style.overflow='hidden';"
     x-show="open"
     class="fixed inset-0 z-[100] flex items-center justify-center p-4"
     style="display:none;" x-transition>
    <div class="absolute inset-0 bg-slate-950/95 backdrop-blur-lg cursor-pointer" @click="open=false;document.body.style.overflow='';"></div>
    <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row z-10" style="height:min(85vh,640px);">
        <div class="w-full md:w-3/5 h-56 md:h-full bg-slate-900 relative flex items-center justify-center flex-shrink-0">
            <template x-if="report&&report.photos&&report.photos.length>0">
                <img :src="'{{ asset('storage') }}/'+report.photos[photoIndex]" class="max-w-full max-h-full object-contain">
            </template>
            <button @click="photoIndex=photoIndex===0?report.photos.length-1:photoIndex-1" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-red-600 text-white rounded-full flex items-center justify-center border border-white/20 transition shadow-lg"><i class="fa-solid fa-chevron-left"></i></button>
            <button @click="photoIndex=photoIndex===report.photos.length-1?0:photoIndex+1" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-red-600 text-white rounded-full flex items-center justify-center border border-white/20 transition shadow-lg"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        <div class="w-full md:w-2/5 p-6 flex flex-col overflow-y-auto bg-slate-50 relative">
            <button @click="open=false;document.body.style.overflow='';" class="absolute top-4 right-4 w-9 h-9 bg-slate-400 rounded-full hover:bg-red-600 hover:text-white flex items-center justify-center transition"><i class="fa-solid fa-xmark"></i></button>
            <div x-show="report" class="mt-4">
                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase text-white tracking-widest" :class="report?.type==='evento'?'bg-red-600':'bg-blue-600'" x-text="report?.type==='evento'?'Evento Institucional':'Actividad de Difusión'"></span>
                <h3 class="text-2xl font-black text-slate-900 mt-4 mb-4 leading-tight" x-text="report?.title"></h3>
                <div class="h-1 w-10 bg-red-600 rounded-full mb-4"></div>
                <p class="text-slate-600 text-sm font-medium leading-relaxed" x-text="report?.description"></p>
                <div class="mt-6 pt-5 border-t border-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-camera text-slate-400 text-sm"></i>
                    <p class="text-xs font-bold text-slate-500">Foto <span x-text="photoIndex+1" class="text-slate-900"></span> de <span x-text="report?.photos?.length" class="text-slate-900"></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════ --}}
{{-- LIGHTBOX                                                     --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<div id="lightbox" class="fixed inset-0 z-[110] bg-slate-950/97 backdrop-blur-xl flex flex-col items-center justify-center">
    <div class="absolute top-0 left-0 w-full p-5 flex justify-between items-center z-50">
        <span id="lb-counter" class="text-white font-bold text-[10px] tracking-widest bg-white/10 px-4 py-2 rounded-full border border-white/15"></span>
        <button id="lb-close" class="w-11 h-11 bg-white/10 hover:bg-red-600 text-white rounded-full flex items-center justify-center border border-white/15 transition"><i class="fa-solid fa-xmark text-lg"></i></button>
    </div>
    <button id="lb-prev" class="absolute left-3 sm:left-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/06 hover:bg-red-600 text-white rounded-full flex items-center justify-center border border-white/10 z-50 transition"><i class="fa-solid fa-chevron-left text-xl"></i></button>
    <div class="relative max-w-6xl max-h-[82vh] w-full px-4 sm:px-24 flex items-center justify-center">
        <img id="lb-img" src="" class="max-w-full max-h-[80vh] object-contain rounded-xl shadow-2xl" style="transition:opacity .2s,transform .2s;">
    </div>
    <button id="lb-next" class="absolute right-3 sm:right-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/06 hover:bg-red-600 text-white rounded-full flex items-center justify-center border border-white/10 z-50 transition"><i class="fa-solid fa-chevron-right text-xl"></i></button>
</div>


{{-- ════════════════════════════════════════════════════════════ --}}
{{-- SCRIPTS                                                      --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<script>
// ── SIDEBAR ──────────────────────────────────────────────────────
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebar-overlay');
document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
});
function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('open');    }
function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('open'); }

// ── SCROLL HELPERS ────────────────────────────────────────────────
function scrollToSection(id) {
    document.getElementById(id)?.scrollIntoView({ behavior:'smooth', block:'start' });
}
function scrollToActivity(id) {
    document.getElementById(id)?.scrollIntoView({ behavior:'smooth', block:'start' });
}

function scrollToSubEvent(seId, activityIdx) {
    const el = document.getElementById(seId);
    if (!el) return;
    if (el.dataset.isLatest === '1') {
        _ensureArticleVisible(activityIdx, () => _doScrollToEl(el));
        return;
    }
    _ensureArticleVisible(activityIdx, () => {
        if (isElHidden(el)) {
            const btn = document.getElementById('expand-toggle-' + activityIdx);
            if (btn) {
                btn.click();
                setTimeout(() => _doScrollToEl(el), 550);
            } else {
                _doScrollToEl(el);
            }
        } else {
            _doScrollToEl(el);
        }
    });
}

function _ensureArticleVisible(aIdx, callback) {
    const article = document.getElementById('actividad-' + aIdx);
    if (!article || isElHidden(article)) {
        article?.scrollIntoView({ behavior:'smooth', block:'start' });
        setTimeout(callback, 300);
    } else {
        callback();
    }
}

function isElHidden(el) {
    let node = el;
    while (node && node !== document.body) {
        const s = window.getComputedStyle(node);
        if (s.display === 'none' || s.visibility === 'hidden') return true;
        node = node.parentElement;
    }
    return false;
}

function _doScrollToEl(el) {
    el.scrollIntoView({ behavior:'smooth', block:'center' });
    el.classList.add('highlight-target');
    setTimeout(() => el.classList.remove('highlight-target'), 2800);
}

// ── YOUTUBE ───────────────────────────────────────────────────────
function playVideo(playButton, youtubeId, containerId) {
    const c = document.getElementById(containerId);
    c.querySelector('.video-thumbnail').style.display = 'none';
    playButton.style.display = 'none';
    const iframe = c.querySelector('.video-iframe');
    iframe.src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0`;
    iframe.style.display = 'block';
}

// ── GALLERY VER MÁS ───────────────────────────────────────────────
document.querySelectorAll('.btn-mostrar-mas').forEach(btn => {
    btn.addEventListener('click', function () {
        const wrap = this.closest('.rounded-2xl') || this.closest('.bg-slate-50');
        const grid = wrap?.querySelector('.galeria-fotos');
        if (!grid) return;
        const extras = grid.querySelectorAll('.foto-extra').length;
        const span   = this.querySelector('span');
        const icon   = this.querySelector('i');
        const show   = grid.classList.toggle('mostrar-todas');
        span.textContent = show ? 'Ocultar fotografías adicionales' : `Ver ${extras} fotografías adicionales`;
        icon.classList.toggle('fa-images',     !show);
        icon.classList.toggle('fa-chevron-up',  show);
    });
});

// ── LIGHTBOX ─────────────────────────────────────────────────────
let gallery=[], lbIdx=0;
const lb    = document.getElementById('lightbox');
const lbImg = document.getElementById('lb-img');
const lbCtr = document.getElementById('lb-counter');

document.querySelectorAll('.foto-galeria').forEach(img => {
    img.addEventListener('click', function () {
        const grid = this.closest('.galeria-fotos');
        gallery = Array.from(grid.querySelectorAll('.foto-galeria'));
        lbIdx   = gallery.indexOf(this);
        openLB();
    });
});
function updateLB() {
    lbImg.style.opacity = '.4';
    setTimeout(() => {
        lbImg.src         = gallery[lbIdx].src;
        lbCtr.textContent = `IMAGEN ${lbIdx+1} DE ${gallery.length}`;
        lbImg.style.opacity = '1';
    }, 160);
}
function openLB()  { updateLB(); lb.classList.add('active');    document.body.style.overflow='hidden'; }
function closeLB() { lb.classList.remove('active'); document.body.style.overflow=''; }

document.getElementById('lb-close').addEventListener('click', closeLB);
document.getElementById('lb-next').addEventListener('click',  () => { lbIdx=(lbIdx+1)%gallery.length; updateLB(); });
document.getElementById('lb-prev').addEventListener('click',  () => { lbIdx=(lbIdx-1+gallery.length)%gallery.length; updateLB(); });
lb.addEventListener('click', e => { if(e.target===lb) closeLB(); });
document.addEventListener('keydown', e => {
    if(!lb.classList.contains('active')) return;
    if(e.key==='Escape')     closeLB();
    if(e.key==='ArrowRight') document.getElementById('lb-next').click();
    if(e.key==='ArrowLeft')  document.getElementById('lb-prev').click();
});

// ── ALPINE AUTO-SLIDER ────────────────────────────────────────────
document.addEventListener('alpine:init', () => {
    Alpine.data('autoSlider', (items, totalMs) => ({
        items, active:0, progress:0, tick:50,
        init() { if(this.items.length > 1) this.startTimer(); },
        startTimer() {
            const step = 100 / (totalMs / this.tick);
            setInterval(() => {
                this.progress += step;
                if(this.progress >= 100) {
                    this.progress = 0;
                    this.active = (this.active + 1) % this.items.length;
                }
            }, this.tick);
        }
    }));
});
</script>
</body>
</html>
