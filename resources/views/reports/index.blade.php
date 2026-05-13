


<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ __('Generador de Reportes') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
           
            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded shadow-sm">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded shadow-sm">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Pestañas --}}
            <div x-data="reportForm()" class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button @click="activeTab = 'general'"
                                :class="{ 'border-emerald-500 text-emerald-600': activeTab === 'general', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'general' }"
                                class="w-1/2 py-3 sm:py-4 px-1 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 border-b-2 font-medium text-xs sm:text-sm transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>Reporte General</span>
                        </button>
                        <button @click="activeTab = 'specific'"
                                :class="{ 'border-emerald-500 text-emerald-600': activeTab === 'specific', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'specific' }"
                                class="w-1/2 py-3 sm:py-4 px-1 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 border-b-2 font-medium text-xs sm:text-sm transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Reporte por Actividad</span>
                        </button>
                    </nav>
                </div>

                {{-- Contenido pestaña General --}}
                <div x-show="activeTab === 'general'" x-transition.duration.300ms class="p-4 sm:p-8">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-6 flex items-center flex-wrap gap-2">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Informe general por período</span>
                    </h3>

                    <form action="{{ route('reports.generate.general') }}" method="GET" novalidate>
                        {{-- Selector de período visual --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Selecciona el período <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 sm:gap-3">
                                <button type="button" @click="general.period = 'day'"
                                        :class="general.period === 'day' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                        class="px-2 sm:px-4 py-2 sm:py-3 border rounded-xl font-medium text-xs sm:text-sm transition-all flex flex-col items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Día
                                </button>
                                <button type="button" @click="general.period = 'week'"
                                        :class="general.period === 'week' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                        class="px-2 sm:px-4 py-2 sm:py-3 border rounded-xl font-medium text-xs sm:text-sm transition-all flex flex-col items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M3 8h18M3 12h18M3 16h18M3 20h18"></path></svg>
                                    Semana
                                </button>
                                <button type="button" @click="general.period = 'month'"
                                        :class="general.period === 'month' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                        class="px-2 sm:px-4 py-2 sm:py-3 border rounded-xl font-medium text-xs sm:text-sm transition-all flex flex-col items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4zM4 8h16M8 4v4M16 4v4"></path></svg>
                                    Mes
                                </button>
                                <button type="button" @click="general.period = 'quarter'"
                                        :class="general.period === 'quarter' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                        class="px-2 sm:px-4 py-2 sm:py-3 border rounded-xl font-medium text-xs sm:text-sm transition-all flex flex-col items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 3v18M3 6h18M3 18h18"></path></svg>
                                    Trimestre
                                </button>
                                <button type="button" @click="general.period = 'year'"
                                        :class="general.period === 'year' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                        class="col-span-2 sm:col-span-1 px-2 sm:px-4 py-2 sm:py-3 border rounded-xl font-medium text-xs sm:text-sm transition-all flex flex-col items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zm0 6h14M7 3v2m4-2v2m4-2v2"></path></svg>
                                    Año
                                </button>
                            </div>
                            <input type="hidden" name="period" x-model="general.period">
                        </div>

                        {{-- Selector de fecha --}}
                        <div class="mb-4">
                            <label for="general_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de referencia <span class="text-red-500">*</span>
                            </label>
                            <input type="date" x-model="general.date" id="general_date" name="date" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 focus:outline-none transition-all">
                        </div>

                        {{-- Mensaje de rango seleccionado --}}
                        <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs sm:text-sm text-emerald-800 break-words"
                             x-text="getRangeDescription(general.period, general.date)"></div>

                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 mt-4">
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                El informe incluirá:
                            </p>
                            <ul class="mt-2 text-xs sm:text-sm text-gray-500 list-disc list-inside space-y-1 ml-1">
                                <li>Tabla de actividades con reportes en el período</li>
                                <li>Avance del período y progreso acumulado por actividad</li>
                                <li>Gráfico de barras del avance por actividad</li>
                                <li>Resumen global de metas vs avance acumulado</li>
                            </ul>
                        </div>

                        <div class="flex justify-end pt-6">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Generar Excel
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Contenido pestaña Específica --}}
                <div x-show="activeTab === 'specific'" x-transition.duration.300ms class="p-4 sm:p-8" style="display: none;">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-6 flex items-center flex-wrap gap-2">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Informe de una actividad por período</span>
                    </h3>

                    <form action="{{ route('reports.generate.specific') }}" method="GET" novalidate>
                        <div class="mb-6 w-full">
                            <label for="specific_event" class="block text-sm font-medium text-gray-700 mb-2">
                                Actividad Operativa <span class="text-red-500">*</span>
                            </label>
                            <select x-model="specific.event_id" id="specific_event" name="event_id" required
                                class="w-full px-3 sm:px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 focus:outline-none transition-all text-xs sm:text-sm text-ellipsis overflow-hidden">
                                    <option value="" disabled selected>-- Selecciona una actividad --</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">
                                            {{ $event->event_code }} - {{ \Illuminate\Support\Str::limit($event->name ?? $event->description, 65) }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>

                        {{-- Selector de período visual --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Selecciona el período <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 sm:gap-3">
                                <button type="button" @click="specific.period = 'day'"
                                        :class="specific.period === 'day' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                        class="px-2 sm:px-4 py-2 sm:py-3 border rounded-xl font-medium text-xs sm:text-sm transition-all flex flex-col items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Día
                                </button>
                                <button type="button" @click="specific.period = 'week'"
                                        :class="specific.period === 'week' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                        class="px-2 sm:px-4 py-2 sm:py-3 border rounded-xl font-medium text-xs sm:text-sm transition-all flex flex-col items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M3 8h18M3 12h18M3 16h18M3 20h18"></path></svg>
                                    Semana
                                </button>
                                <button type="button" @click="specific.period = 'month'"
                                        :class="specific.period === 'month' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                        class="px-2 sm:px-4 py-2 sm:py-3 border rounded-xl font-medium text-xs sm:text-sm transition-all flex flex-col items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4zM4 8h16M8 4v4M16 4v4"></path></svg>
                                    Mes
                                </button>
                                <button type="button" @click="specific.period = 'quarter'"
                                        :class="specific.period === 'quarter' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                        class="px-2 sm:px-4 py-2 sm:py-3 border rounded-xl font-medium text-xs sm:text-sm transition-all flex flex-col items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 3v18M3 6h18M3 18h18"></path></svg>
                                    Trimestre
                                </button>
                                <button type="button" @click="specific.period = 'year'"
                                        :class="specific.period === 'year' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                        class="col-span-2 sm:col-span-1 px-2 sm:px-4 py-2 sm:py-3 border rounded-xl font-medium text-xs sm:text-sm transition-all flex flex-col items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zm0 6h14M7 3v2m4-2v2m4-2v2"></path></svg>
                                    Año
                                </button>
                            </div>
                            <input type="hidden" name="period" x-model="specific.period">
                        </div>

                        <div class="mb-4">
                            <label for="specific_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de referencia <span class="text-red-500">*</span>
                            </label>
                            <input type="date" x-model="specific.date" id="specific_date" name="date" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 focus:outline-none transition-all">
                        </div>

                        <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs sm:text-sm text-emerald-800 break-words"
                             x-text="getRangeDescription(specific.period, specific.date)"></div>

                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 mt-4">
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                El informe incluirá:
                            </p>
                            <ul class="mt-2 text-xs sm:text-sm text-gray-500 list-disc list-inside space-y-1 ml-1">
                                <li>Estado del avance antes del período</li>
                                <li>Reportes realizados durante el período</li>
                                <li>Estado después del período (acumulado actualizado)</li>
                                <li>Gráfico de torta del avance vs meta de la actividad</li>
                            </ul>
                        </div>

                        <div class="flex justify-end pt-6">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Generar Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($metaFisicaTotal))
            <div class="mt-12">
                <h2 class="text-2xl font-extrabold text-slate-800 mb-6 border-b pb-2">Dashboard de Estado Global</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="text-center font-bold text-slate-700 mb-4">Progreso de la Meta Anual</h3>
                        <div class="relative h-48">
                            <canvas id="chartMeta"></canvas>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="text-center font-bold text-slate-700 mb-4">Eficiencia por Actividad</h3>
                        <div class="relative h-48">
                            <canvas id="chartBarras"></canvas>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <h3 class="text-center font-bold text-slate-700 mb-4">Distribución del Esfuerzo</h3>
                        <div class="relative h-48">
                            <canvas id="chartTorta"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const colores = ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#3B82F6'];
                    new Chart(document.getElementById('chartMeta'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Avance Realizado', 'Faltante para Meta'],
                            datasets: [{ data: [{{ $avanceHistorico }}, {{ $metaFaltante }}], backgroundColor: ['#10B981', '#E2E8F0'], borderWidth: 0 }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
                    });
                    new Chart(document.getElementById('chartBarras'), {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($barrasLabels) !!},
                            datasets: [{ label: 'Personas Asistidas', data: {!! json_encode($barrasData) !!}, backgroundColor: '#4F46E5', borderRadius: 4 }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                    });
                    new Chart(document.getElementById('chartTorta'), {
                        type: 'pie',
                        data: {
                            labels: {!! json_encode($tortaLabels) !!},
                            datasets: [{ data: {!! json_encode($tortaData) !!}, backgroundColor: colores, borderWidth: 0 }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
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
                general: {
                    period: 'month',
                    date: new Date().toISOString().split('T')[0]
                },
                specific: {
                    event_id: '',
                    period: 'month',
                    date: new Date().toISOString().split('T')[0]
                },
                getRangeDescription(period, dateStr) {
                    if (!dateStr) return 'Seleccione una fecha para ver el rango.';
                    const date = new Date(dateStr + 'T00:00:00');
                    const year = date.getFullYear();
                    const month = date.getMonth();
                    const day = date.getDate();
                   
                    const formatter = new Intl.DateTimeFormat('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
                   
                    switch(period) {
                        case 'day':
                            return `📅 Día seleccionado: ${formatter.format(date)}`;
                        case 'week':
                            const startOfWeek = new Date(date);
                            startOfWeek.setDate(day - date.getDay() + 1);
                            const endOfWeek = new Date(startOfWeek);
                            endOfWeek.setDate(startOfWeek.getDate() + 6);
                            return `📆 Semana del ${formatter.format(startOfWeek)} al ${formatter.format(endOfWeek)}`;
                        case 'month':
                            const monthName = new Intl.DateTimeFormat('es-ES', { month: 'long' }).format(date);
                            return `📅 Mes de ${monthName} de ${year}`;
                        case 'quarter':
                            const quarter = Math.floor(month / 3);
                            const startMonth = quarter * 3;
                            const endMonth = startMonth + 2;
                            const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                            return `📊 Trimestre: ${monthNames[startMonth]} - ${monthNames[endMonth]} de ${year}`;
                        case 'year':
                            return `📅 Año completo: ${year}`;
                        default:
                            return '';
                    }
                }
            }
        }
    </script>
</x-app-layout>


