<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"></path>
                </svg>
                {{ __('Panel de Control') }}
            </h2>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('trash.index') }}" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition">
                    <i class="fa-solid fa-trash-arrow-up"></i> Papelera de Reciclaje
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tarjetas de resumen --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white shadow-sm rounded-xl p-5 border border-slate-100">
                    <div class="text-slate-500 text-xs font-semibold mb-1 uppercase">Meta Global</div>
                    <div class="text-2xl font-black text-slate-800">{{ number_format($totalMetas) }}</div>
                    <div class="text-xs text-slate-400 mt-1">Personas programadas</div>
                </div>

                <div class="bg-white shadow-sm rounded-xl p-5 border border-slate-100">
                    <div class="text-emerald-600 text-xs font-semibold mb-1 uppercase">Avance Total</div>
                    <div class="text-2xl font-black text-emerald-700">{{ number_format($totalAvance) }}</div>
                    <div class="text-xs text-slate-400 mt-1">Personas alcanzadas</div>
                </div>

                <div class="bg-gradient-to-br from-indigo-600 to-blue-700 shadow-sm rounded-xl p-5 text-white">
                    <div class="text-indigo-100 text-xs font-semibold mb-1 uppercase">Progreso</div>
                    <div class="text-2xl font-black">{{ $porcentajeGlobal }}%</div>
                    <div class="w-full bg-indigo-900/50 rounded-full h-2 mt-2">
                        <div class="bg-white h-2 rounded-full transition-all duration-700" style="width: {{ min($porcentajeGlobal, 100) }}%"></div>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-xl p-5 border border-slate-100">
                    <div class="text-amber-600 text-xs font-semibold mb-1 uppercase">Actividades</div>
                    <div class="text-2xl font-black text-amber-700">{{ $completadas }}/{{ $totalActividades }}</div>
                    <div class="text-xs text-slate-400 mt-1">Completadas</div>
                </div>
            </div>

            {{-- Fila de gráficos principales --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Barras: Progreso por actividad --}}
                <div class="bg-white shadow-sm rounded-xl p-6 border border-slate-100">
                    <h3 class="text-base font-bold text-slate-800 mb-4">Progreso por Actividad</h3>
                    <div class="h-64">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>

                {{-- Dona: Distribución por categoría --}}
                <div class="bg-white shadow-sm rounded-xl p-6 border border-slate-100">
                    <h3 class="text-base font-bold text-slate-800 mb-4">Avance por Categoría</h3>
                    <div class="h-64">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Segunda fila de gráficos --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Línea: Evolución mensual --}}
                <div class="lg:col-span-2 bg-white shadow-sm rounded-xl p-6 border border-slate-100">
                    <h3 class="text-base font-bold text-slate-800 mb-4">Evolución Mensual de Asistentes</h3>
                    <div class="h-64">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>

                {{-- Torta: Cumplimiento global --}}
                <div class="bg-white shadow-sm rounded-xl p-6 border border-slate-100">
                    <h3 class="text-base font-bold text-slate-800 mb-4">Cumplimiento Global</h3>
                    <div class="h-64">
                        <canvas id="gaugeChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Tabla resumen de actividades --}}
            <div class="bg-white shadow-sm rounded-xl p-6 border border-slate-100">
                <h3 class="text-base font-bold text-slate-800 mb-4">Detalle de Actividades</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actividad</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Avance</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Progreso</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($eventos as $evento)
                                @php
                                    $avance = $evento->total_attendees ?? 0;
                                    $meta = $evento->goal_people;
                                    $pct = $meta > 0 ? min(100, round(($avance / $meta) * 100, 1)) : 0;
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-2 font-medium text-indigo-700">{{ $evento->event_code }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ Str::limit($evento->description, 50) }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ $evento->category->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 font-semibold">{{ $avance }} / {{ $meta }}</td>
                                    <td class="px-4 py-2 w-32">
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-500 mt-0.5 block">{{ $pct }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Datos ligeros desde PHP
            const chartBar = @json($chartBar);
            const catData = @json($catData);
            const monthly = @json($monthly);
            const porcentajeGlobal = {{ $porcentajeGlobal }};

            // 1. Gráfico de barras
            const ctx1 = document.getElementById('progressChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: chartBar.map(i => i.code),
                    datasets: [
                        {
                            label: 'Avance',
                            data: chartBar.map(i => i.avance),
                            backgroundColor: 'rgba(99, 102, 241, 0.7)',
                            borderRadius: 4,
                        },
                        {
                            label: 'Meta',
                            data: chartBar.map(i => i.meta),
                            backgroundColor: 'rgba(203, 213, 225, 0.5)',
                            borderColor: 'rgba(148, 163, 184, 1)',
                            borderWidth: 1,
                            type: 'line',
                            fill: false,
                            pointRadius: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Personas' } }
                    },
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // 2. Dona por categoría
            const ctx2 = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(catData),
                    datasets: [{
                        data: Object.values(catData),
                        backgroundColor: [
                            'rgba(79, 70, 229, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // 3. Evolución mensual
            const ctx3 = document.getElementById('evolutionChart').getContext('2d');
            const monthlyLabels = monthly.map(m => {
                const [y, mo] = m.mes.split('-');
                return new Date(y, mo - 1, 1).toLocaleDateString('es', { month: 'short', year: 'numeric' });
            });
            const monthlyValues = monthly.map(m => m.total);
            new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Asistentes',
                        data: monthlyValues,
                        fill: true,
                        backgroundColor: 'rgba(99, 102, 241, 0.2)',
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });

            // 4. Torta de cumplimiento (avance vs restante)
            const ctx4 = document.getElementById('gaugeChart').getContext('2d');
            const restante = Math.max(0, 100 - porcentajeGlobal);
            new Chart(ctx4, {
                type: 'doughnut',
                data: {
                    labels: ['Alcanzado', 'Restante'],
                    datasets: [{
                        data: [porcentajeGlobal, restante],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(203, 213, 225, 0.5)',
                        ],
                        borderWidth: 0,
                        cutout: '75%',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                },
                plugins: [{
                    id: 'centerText',
                    afterDraw: (chart) => {
                        const { ctx, width, height } = chart;
                        ctx.restore();
                        ctx.font = 'bold 24px Inter, sans-serif';
                        ctx.fillStyle = '#1e293b';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText(porcentajeGlobal + '%', width / 2, height / 2 - 5);
                        ctx.font = '12px Inter, sans-serif';
                        ctx.fillStyle = '#64748b';
                        ctx.fillText('cumplimiento', width / 2, height / 2 + 20);
                        ctx.save();
                    }
                }]
            });
        });
    </script>
</x-app-layout>