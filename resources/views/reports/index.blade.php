<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-gradient-to-br from-slate-800 to-slate-950 rounded-xl text-white shadow-md">
                    <i class="fa-solid fa-chart-line text-lg"></i>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-slate-800 leading-tight tracking-tight">
                        {{ __('Generador de Reportes Analíticos') }}
                    </h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Exportación Documental de Metas y Coberturas POI</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="reportForm()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
           
            {{-- ALERTAS DE LOG --}}
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl flex items-center gap-2 font-bold text-xs">
                    <i class="fa-solid fa-circle-exclamation text-red-500"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl flex items-center gap-2 font-bold text-xs">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
                </div>
            @endif

            {{-- CONTENEDOR MATRIZ CON CONTROL DE PESTAÑAS --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
                
                <div class="border-b border-slate-200 bg-slate-50/50 p-1">
                    <nav class="flex gap-2">
                        <button @click="activeTab = 'general'"
                                :class="activeTab === 'general' ? 'bg-white text-slate-900 shadow-sm font-black border-slate-200' : 'text-slate-500 font-semibold hover:text-slate-800 border-transparent'"
                                class="w-1/2 py-3.5 px-4 flex items-center justify-center gap-2 rounded-xl border font-bold text-xs sm:text-sm transition-all duration-200">
                            <i class="fa-solid fa-layer-group text-xs" :class="activeTab === 'general' ? 'text-emerald-500' : ''"></i>
                            <span>Reporte GeneralPOI</span>
                        </button>
                        <button @click="activeTab = 'specific'"
                                :class="activeTab === 'specific' ? 'bg-white text-slate-900 shadow-sm font-black border-slate-200' : 'text-slate-500 font-semibold hover:text-slate-800 border-transparent'"
                                class="w-1/2 py-3.5 px-4 flex items-center justify-center gap-2 rounded-xl border font-bold text-xs sm:text-sm transition-all duration-200">
                            <i class="fa-solid fa-briefcase text-xs" :class="activeTab === 'specific' ? 'text-emerald-500' : ''"></i>
                            <span>Reporte por Actividad Específica</span>
                        </button>
                    </nav>
                </div>

                {{-- CONTENIDO: REPORTE GENERAL --}}
                <div x-show="activeTab === 'general'" x-transition.opacity.duration.300ms class="p-6 sm:p-10 space-y-6">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-sliders text-emerald-500 text-sm"></i>
                        <h3 class="text-base font-black text-slate-800">Parámetros del Informe General</h3>
                    </div>

                    <form action="{{ route('reports.generate.general') }}" method="GET" novalidate class="space-y-6">
                        
                        <div class="space-y-3">
                            <label class="block text-slate-700 text-xs font-black uppercase tracking-wider">Filtrar Fuente de Financiamiento</label>
                            <input type="hidden" name="funding_source" :value="general.funding_source">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div @click="general.funding_source = 'all'" :class="general.funding_source === 'all' ? 'border-indigo-600 bg-indigo-50/20 ring-2 ring-indigo-500/10' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50'" class="border-2 rounded-xl p-4 flex items-center gap-3 cursor-pointer transition-all relative">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 bg-white border border-slate-200"><i class="fa-solid fa-cubes text-xs"></i></div>
                                    <span class="text-xs font-bold text-slate-800">Consolidado POI</span>
                                    <div class="absolute top-2 right-2 text-indigo-600 text-xs" x-show="general.funding_source === 'all'"><i class="fa-solid fa-circle-check"></i></div>
                                </div>
                                <div @click="general.funding_source = 'gobierno_regional'" :class="general.funding_source === 'gobierno_regional' ? 'border-indigo-600 bg-indigo-50/20 ring-2 ring-indigo-500/10' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50'" class="border-2 rounded-xl p-4 flex items-center gap-3 cursor-pointer transition-all relative">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-indigo-600 bg-white border border-slate-200"><i class="fa-solid fa-building-government text-xs"></i></div>
                                    <span class="text-xs font-bold text-slate-800">Gobierno Regional</span>
                                    <div class="absolute top-2 right-2 text-indigo-600 text-xs" x-show="general.funding_source === 'gobierno_regional'"><i class="fa-solid fa-circle-check"></i></div>
                                </div>
                                <div @click="general.funding_source = 'gobierno_central'" :class="general.funding_source === 'gobierno_central' ? 'border-amber-500 bg-amber-50/30 ring-2 ring-amber-500/10' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50'" class="border-2 rounded-xl p-4 flex items-center gap-3 cursor-pointer transition-all relative">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-amber-600 bg-white border border-slate-200"><i class="fa-solid fa-building-shield text-xs"></i></div>
                                    <span class="text-xs font-bold text-slate-800">SUNAFIL / Central</span>
                                    <div class="absolute top-2 right-2 text-amber-500 text-xs" x-show="general.funding_source === 'gobierno_central'"><i class="fa-solid fa-circle-check"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-slate-700 text-xs font-black uppercase tracking-wider">Selecciona el Rango de Tiempo <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                                <button type="button" @click="general.period = 'day'" :class="general.period === 'day' ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="py-2.5 rounded-xl font-bold text-xs transition-all flex flex-col items-center gap-1">
                                    <i class="fa-solid fa-calendar-day text-sm"></i> Día
                                </button>
                                <button type="button" @click="general.period = 'week'" :class="general.period === 'week' ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="py-2.5 rounded-xl font-bold text-xs transition-all flex flex-col items-center gap-1">
                                    <i class="fa-solid fa-calendar-week text-sm"></i> Semana
                                </button>
                                <button type="button" @click="general.period = 'month'" :class="general.period === 'month' ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="py-2.5 rounded-xl font-bold text-xs transition-all flex flex-col items-center gap-1">
                                    <i class="fa-solid fa-calendar-days text-sm"></i> Mes
                                </button>
                                <button type="button" @click="general.period = 'quarter'" :class="general.period === 'quarter' ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="py-2.5 rounded-xl font-bold text-xs transition-all flex flex-col items-center gap-1">
                                    <i class="fa-solid fa-chart-pie text-sm"></i> Trimestre
                                </button>
                                <button type="button" @click="general.period = 'year'" :class="general.period === 'year' ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="col-span-2 sm:col-span-1 py-2.5 rounded-xl font-bold text-xs transition-all flex flex-col items-center gap-1">
                                    <i class="fa-solid fa-calendar text-sm"></i> Año Fiscal
                                </button>
                            </div>
                            <input type="hidden" name="period" x-model="general.period">
                        </div>

                        <div class="space-y-2">
                            <label for="general_date" class="block text-sm font-bold text-slate-700">Fecha de Referencia Cronológica <span class="text-red-500">*</span></label>
                            <input type="date" x-model="general.date" id="general_date" name="date" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner">
                        </div>

                        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-xs sm:text-sm text-emerald-800 font-bold flex items-center gap-2 shadow-inner" x-text="getRangeDescription(general.period, general.date)"></div>

                        <div class="flex justify-end pt-5 border-t border-slate-100">
                            <button type="submit" class="w-full sm:w-auto bg-slate-900 hover:bg-indigo-600 text-white font-black text-xs uppercase tracking-wider py-3.5 px-8 rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                                <i class="fa-solid fa-file-excel text-sm"></i> Generar Excel Consolidado
                            </button>
                        </div>
                    </form>
                </div>

                {{-- CONTENIDO: REPORTE ESPECÍFICO --}}
                <div x-show="activeTab === 'specific'" x-transition.opacity.duration.300ms class="p-6 sm:p-10 space-y-6" style="display: none;">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-sliders text-emerald-500 text-sm"></i>
                        <h3 class="text-base font-black text-slate-800">Parámetros del Informe por Actividad</h3>
                    </div>

                    <form action="{{ route('reports.generate.specific') }}" method="GET" novalidate class="space-y-6">
                        
                        <div class="space-y-3">
                            <label class="block text-slate-700 text-xs font-black uppercase tracking-wider">Paso 1: Filtrar Tipo de Tarea Operativa</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div @click="specificFundingFilter = 'all'" :class="specificFundingFilter === 'all' ? 'border-indigo-600 bg-indigo-50/20 ring-2 ring-indigo-500/10' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50'" class="border-2 rounded-xl p-4 flex items-center gap-3 cursor-pointer transition-all relative">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 bg-white border border-slate-200"><i class="fa-solid fa-cubes text-xs"></i></div>
                                    <span class="text-xs font-bold text-slate-800">Ver Todas</span>
                                </div>
                                <div @click="specificFundingFilter = 'gobierno_regional'" :class="specificFundingFilter === 'gobierno_regional' ? 'border-indigo-600 bg-indigo-50/20 ring-2 ring-indigo-500/10' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50'" class="border-2 rounded-xl p-4 flex items-center gap-3 cursor-pointer transition-all relative">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-indigo-600 bg-white border border-slate-200"><i class="fa-solid fa-building-government text-xs"></i></div>
                                    <span class="text-xs font-bold text-slate-800">Sede Regional</span>
                                </div>
                                <div @click="specificFundingFilter = 'gobierno_central'" :class="specificFundingFilter === 'gobierno_central' ? 'border-amber-500 bg-amber-50/30 ring-2 ring-amber-500/10' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50'" class="border-2 rounded-xl p-4 flex items-center gap-3 cursor-pointer transition-all relative">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-amber-600 bg-white border border-slate-200"><i class="fa-solid fa-building-shield text-xs"></i></div>
                                    <span class="text-xs font-bold text-slate-800">SUNAFIL</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="specific_event" class="block text-sm font-bold text-slate-700">Paso 2: Selecciona la Actividad Operativa <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select x-model="specific.event_id" id="specific_event" name="event_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>-- Elige una tarea de la lista --</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" 
                                                x-show="specificFundingFilter === 'all' || specificFundingFilter === '{{ $event->funding_source }}'"
                                                {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                            [{{ $event->event_code }}] · {{ \Illuminate\Support\Str::limit($event->name ?? $event->description, 70) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400"><i class="fa-solid fa-chevron-down text-xs"></i></div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-slate-700 text-xs font-black uppercase tracking-wider">Paso 3: Rango de Tiempo</label>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                                <button type="button" @click="specific.period = 'day'" :class="specific.period === 'day' ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="py-2.5 rounded-xl font-bold text-xs transition-all flex flex-col items-center gap-1">
                                    <i class="fa-solid fa-calendar-day text-sm"></i> Día
                                </button>
                                <button type="button" @click="specific.period = 'week'" :class="specific.period === 'week' ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="py-2.5 rounded-xl font-bold text-xs transition-all flex flex-col items-center gap-1">
                                    <i class="fa-solid fa-calendar-week text-sm"></i> Semana
                                </button>
                                <button type="button" @click="specific.period = 'month'" :class="specific.period === 'month' ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="py-2.5 rounded-xl font-bold text-xs transition-all flex flex-col items-center gap-1">
                                    <i class="fa-solid fa-calendar-days text-sm"></i> Mes
                                </button>
                                <button type="button" @click="specific.period = 'quarter'" :class="specific.period === 'quarter' ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="py-2.5 rounded-xl font-bold text-xs transition-all flex flex-col items-center gap-1">
                                    <i class="fa-solid fa-chart-pie text-sm"></i> Trimestre
                                </button>
                                <button type="button" @click="specific.period = 'year'" :class="specific.period === 'year' ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100'" class="col-span-2 sm:col-span-1 py-2.5 rounded-xl font-bold text-xs transition-all flex flex-col items-center gap-1">
                                    <i class="fa-solid fa-calendar text-sm"></i> Año Fiscal
                                </button>
                            </div>
                            <input type="hidden" name="period" x-model="specific.period">
                        </div>

                        <div class="space-y-2">
                            <label for="specific_date" class="block text-sm font-bold text-slate-700">Paso 4: Fecha de Referencia <span class="text-red-500">*</span></label>
                            <input type="date" x-model="specific.date" id="specific_date" name="date" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner">
                        </div>

                        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-xs sm:text-sm text-emerald-800 font-bold flex items-center gap-2 shadow-inner" x-text="getRangeDescription(specific.period, specific.date)"></div>

                        <div class="flex justify-end pt-5 border-t border-slate-100">
                            <button type="submit" class="w-full sm:w-auto bg-slate-900 hover:bg-indigo-600 text-white font-black text-xs uppercase tracking-wider py-3.5 px-8 rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                                <i class="fa-solid fa-file-excel text-sm"></i> Generar Excel por Actividad
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            {{-- SECCIÓN COMPLEMENTARIA: DASHBOARD DE ESTADO GLOBAL PRE-COMPILADO --}}
            @if(isset($metaFisicaTotal))
                <div class="mt-12 space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-200 pb-3">
                        <div class="w-2 h-6 bg-indigo-600 rounded-full"></div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Dashboard de Monitoreo Global</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-2xl shadow-md border border-slate-100 flex flex-col justify-between">
                            <h3 class="text-center font-black text-xs text-slate-500 uppercase tracking-wider mb-4">Progreso de la Meta Anual</h3>
                            <div class="relative h-48 w-full"><canvas id="chartMeta"></canvas></div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-md border border-slate-100 flex flex-col justify-between">
                            <h3 class="text-center font-black text-xs text-slate-500 uppercase tracking-wider mb-4">Eficiencia por Actividad</h3>
                            <div class="relative h-48 w-full"><canvas id="chartBarras"></canvas></div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl shadow-md border border-slate-100 flex flex-col justify-between">
                            <h3 class="text-center font-black text-xs text-slate-500 uppercase tracking-wider mb-4">Distribución del Esfuerzo</h3>
                            <div class="relative h-48 w-full"><canvas id="chartTorta"></canvas></div>
                        </div>
                    </div>
                </div>

                {{-- Chart.js Configuración Estilizada Premium --}}
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Chart.defaults.font.family = "'DM Sans', 'Inter', sans-serif";
                        Chart.defaults.color = '#94a3b8';

                        const colores = ['#4f46e5', '#0284c7', '#10b981', '#f59e0b', '#ef4444', '#6366f1'];
                        
                        new Chart(document.getElementById('chartMeta'), {
                            type: 'doughnut',
                            data: {
                                labels: ['Avance Realizado', 'Faltante'],
                                datasets: [{ data: [{{ $avanceHistorico }}, {{ $metaFaltante }}], backgroundColor: ['#10b981', '#f1f5f9'], borderWidth: 0, cutout: '75%' }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 6, font: { weight: 'bold', size: 11 } } } } }
                        });

                        new Chart(document.getElementById('chartBarras'), {
                            type: 'bar',
                            data: {
                                labels: {!! json_encode($barrasLabels) !!},
                                datasets: [{ label: 'Asistentes', data: {!! json_encode($barrasData) !!}, backgroundColor: '#4f46e5', borderRadius: 4 }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } } } }
                        });

                        new Chart(document.getElementById('chartTorta'), {
                            type: 'pie',
                            data: {
                                labels: {!! json_encode($tortaLabels) !!},
                                datasets: [{ data: {!! json_encode($tortaData) !!}, backgroundColor: colores, borderWidth: 3, borderColor: '#ffffff' }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 6, font: { weight: 'bold', size: 10 } } } } }
                        });
                    });
                </script>
            @endif
           
        </div>
    </div>

    <script>
        function reportForm() {
            return {
                activeTab: 'general',
                specificFundingFilter: 'all', // Estado intermedio para el select de la pestaña 2
                general: {
                    period: 'month',
                    funding_source: 'all', // Parámetro cruzado añadido
                    date: new Date().toISOString().split('T')[0]
                },
                specific: {
                    event_id: '',
                    period: 'month',
                    date: new Date().toISOString().split('T')[0]
                },
                getRangeDescription(period, dateStr) {
                    if (!dateStr) return 'Seleccione una fecha para procesar el rango.';
                    const date = new Date(dateStr + 'T00:00:00');
                    const year = date.getFullYear();
                    const month = date.getMonth();
                    const day = date.getDate();
                   
                    const formatter = new Intl.DateTimeFormat('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
                   
                    switch(period) {
                        case 'day':
                            return `📅 Día de auditoría seleccionado: ${formatter.format(date)}`;
                        case 'week':
                            const startOfWeek = new Date(date);
                            startOfWeek.setDate(day - date.getDay() + 1);
                            const endOfWeek = new Date(startOfWeek);
                            endOfWeek.setDate(startOfWeek.getDate() + 6);
                            return `📆 Semana operativa del ${formatter.format(startOfWeek)} al ${formatter.format(endOfWeek)}`;
                        case 'month':
                            const monthName = new Intl.DateTimeFormat('es-ES', { month: 'long' }).format(date);
                            return `📅 Mes consolidado de ${monthName.toUpperCase()} de ${year}`;
                        case 'quarter':
                            const quarter = Math.floor(month / 3);
                            const startMonth = quarter * 3;
                            const endMonth = startMonth + 2;
                            const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            return `📊 Período Trimestral: ${monthNames[startMonth]} - ${monthNames[endMonth]} del ${year}`;
                        case 'year':
                            return `📅 Año Fiscal Consolidado completo: ${year}`;
                        default:
                            return '';
                    }
                }
            }
        }
    </script>
</x-app-layout>
