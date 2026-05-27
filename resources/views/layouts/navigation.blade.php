<nav x-data="{ openSidebar: false }" class="relative z-50">
   
    {{-- BARRA SUPERIOR MÓVIL --}}
    <div class="sm:hidden flex items-center justify-between bg-white border-b border-slate-200 px-4 py-3 shadow-sm sticky top-0 z-40">
        <button @click="openSidebar = true" class="text-slate-500 hover:text-indigo-600 focus:outline-none transition-colors p-2 -ml-2">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
       
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
            <span class="font-black text-slate-800 text-sm tracking-widest">DRTPE</span>
        </a>

        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
    </div>

    {{-- CAPA OSCURA MÓVIL --}}
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 sm:hidden"
         x-show="openSidebar"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="openSidebar = false"
         style="display: none;">
    </div>

    {{-- SIDEBAR ESTRUCTURAL --}}
    <aside :class="openSidebar ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 flex flex-col transition-transform duration-300 ease-in-out sm:translate-x-0 shadow-2xl border-r border-slate-800 h-screen">
       
        <div class="h-20 flex items-center justify-between px-6 bg-slate-950/50 border-b border-slate-800 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group w-full bg-white p-2 rounded-xl shadow-lg border border-slate-200 transition-transform hover:scale-105">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto object-contain">
                <div class="flex flex-col text-left">
                    <span class="text-slate-900 font-black text-xs leading-none uppercase tracking-widest">DRTPE</span>
                    <span class="text-slate-500 font-bold text-[9px] uppercase tracking-widest">Puno Perú</span>
                </div>
            </a>
            <button @click="openSidebar = false" class="sm:hidden text-slate-400 hover:text-white p-2 ml-2">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5 scrollbar-thin scrollbar-thumb-slate-700">
           
            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-2 mt-2">Principal</div>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold transition-all group {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie text-lg {{ request()->routeIs('dashboard') ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
                <span>{{ __('Página Principal') }}</span>
            </a>

            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-2 mt-6">Operaciones</div>

            <a href="{{ route('categories.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold transition-all group {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-layer-group text-lg {{ request()->routeIs('categories.*') ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
                <span>{{ __('Actividades (PP)') }}</span>
            </a>

            <a href="{{ route('events.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold transition-all group {{ request()->routeIs('events.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-briefcase text-lg {{ request()->routeIs('events.*') ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
                <span>{{ __('Act. Operativas (A01)') }}</span>
            </a>

            <a href="{{ route('subevents.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold transition-all group {{ request()->routeIs('subevents.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-file-signature text-lg {{ request()->routeIs('subevents.*') ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
                <span>{{ __('Reportes') }}</span>
            </a>

            <a href="{{ route('workshops.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold transition-all group {{ request()->routeIs('workshops.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chalkboard-user text-lg {{ request()->routeIs('workshops.*') ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
                <span>{{ __('Talleres y Capacitaciones') }}</span>
            </a>

            <a href="{{ route('reports.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold transition-all group {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-column text-lg {{ request()->routeIs('reports.*') ? '' : 'group-hover:scale-110 transition-transform' }}"></i>
                <span>Analisis Gráfico</span>
            </a>

            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-2 mt-6">Alertas y Avisos</div>

            <a href="{{ route('announcements.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold transition-all duration-300 group {{ request()->routeIs('announcements.*') ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-[0_0_20px_rgba(220,38,38,0.4)] border border-red-500/50' : 'text-slate-300 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                <i class="fa-solid fa-bullhorn text-lg {{ request()->routeIs('announcements.*') ? '' : 'group-hover:text-red-400 transition-colors' }}"></i>
                <span>Comunicados Oficiales</span>
            </a>

            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-2 mt-6">Galería e Impacto</div>

            <a href="{{ route('photo-reports.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold transition-all duration-300 group mb-2 {{ request()->routeIs('photo-reports.index') ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-[0_0_20px_rgba(220,38,38,0.4)] border border-red-500/50' : 'text-slate-300 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                <i class="fa-solid fa-images text-lg {{ request()->routeIs('photo-reports.index') ? '' : 'group-hover:text-red-400 transition-colors' }}"></i>
                <span>Ver Galería General</span>
            </a>

            <a href="{{ route('photo-reports.create', ['type' => 'evento']) }}"
               class="flex items-center justify-between px-4 py-3.5 rounded-xl font-bold transition-all duration-300 group text-slate-300 hover:bg-white/10 hover:text-white hover:translate-x-1">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-calendar-plus text-lg group-hover:text-red-400 transition-colors"></i>
                    <span>Registrar Evento</span>
                </div>
            </a>

            <a href="{{ route('photo-reports.create', ['type' => 'difusion']) }}"
               class="flex items-center justify-between px-4 py-3.5 rounded-xl font-bold transition-all duration-300 group text-slate-300 hover:bg-white/10 hover:text-white hover:translate-x-1">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-radio text-lg group-hover:text-red-400 transition-colors"></i>
                    <span>Registrar Difusión</span>
                </div>
            </a>

            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-2 mt-6">Publicaciones Oficiales</div>

            <a href="{{ route('bulletins.index') }}"
               class="flex items-center gap-3 px-4 py-3.5 rounded-xl font-bold transition-all duration-300 group {{ request()->routeIs('bulletins.*') ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                <i class="fa-solid fa-file-pdf text-lg {{ request()->routeIs('bulletins.*') ? '' : 'group-hover:text-amber-400 transition-colors' }}"></i>
                <span>Boletines Informativos</span>
            </a>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-950/30 shrink-0">
            <div class="flex items-center gap-3 mb-3 px-2">
                <div class="w-10 h-10 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 flex items-center justify-center font-black">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold text-slate-300 bg-slate-800 hover:bg-indigo-600 hover:text-white rounded-lg transition-colors border border-slate-700 hover:border-indigo-500">
                    <i class="fa-solid fa-user-gear"></i> Perfil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-bold text-slate-300 bg-slate-800 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-slate-700 hover:border-red-500">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Salir
                    </button>
                </form>
            </div>
        </div>
    </aside>
</nav>
