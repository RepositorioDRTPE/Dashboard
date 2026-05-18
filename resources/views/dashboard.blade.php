<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-slate-800 leading-tight flex items-center gap-3 tracking-tight">
                <div class="p-2 bg-indigo-100 rounded-xl text-indigo-700">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                {{ __('Panel de Control Operativo') }}
            </h2>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('trash.index') }}" class="group bg-white hover:bg-red-50 text-slate-600 hover:text-red-600 border border-slate-200 hover:border-red-200 font-bold py-2.5 px-5 rounded-xl flex items-center gap-2 transition-all shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-trash-arrow-up group-hover:-translate-y-0.5 transition-transform"></i> 
                    <span>Papelera</span>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
           
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            {{-- TARJETAS DE RESUMEN --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white shadow-sm rounded-2xl p-6 border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-slate-50 rounded-full group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="text-slate-400 text-xs font-bold mb-1 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-bullseye"></i> Meta Global
                        </div>
                        <div class="text-3xl font-black text-slate-800">{{ number_format($totalMetas) }}</div>
                        <div class="text-xs text-slate-500 mt-1 font-medium">Personas programadas en total</div>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-2xl p-6 border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="text-emerald-600 text-xs font-bold mb-1 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-users"></i> Avance Total
                        </div>
                        <div class="text-3xl font-black text-emerald-700 flex items-baseline gap-2">
                            {{ number_format($totalAvance) }}
                            @if($totalAvance > $totalMetas)
                                <span class="text-xs font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">
                                    +{{ number_format($totalAvance - $totalMetas) }} excedentes
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 mt-1 font-medium">Personas alcanzadas (incluye excedentes)</div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-700 to-indigo-900 shadow-md rounded-2xl p-6 text-white relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="text-indigo-200 text-xs font-bold mb-1 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-chart-line"></i> Progreso Oficial
                        </div>
                        <div class="text-3xl font-black flex items-baseline gap-1">
                            {{ min($porcentajeGlobal, 100) }}<span class="text-lg text-indigo-300">%</span>
                        </div>
                        <div class="w-full bg-indigo-950/50 rounded-full h-2.5 mt-3 overflow-hidden border border-indigo-500/30">
                            <div class="bg-gradient-to-r from-blue-400 to-indigo-300 h-full rounded-full shadow-[0_0_10px_rgba(165,180,252,0.5)]" style="width: {{ min($porcentajeGlobal, 100) }}%"></div>
                        </div>
                        <div class="text-[10px] text-indigo-300 mt-1.5 font-medium">*El cálculo global no suma excedentes</div>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-2xl p-6 border border-slate-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="text-amber-600 text-xs font-bold mb-1 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-check"></i> Actividades
                        </div>
                        <div class="text-3xl font-black text-amber-700">{{ $completadas }}<span class="text-lg text-slate-300 mx-1">/</span><span class="text-xl text-slate-600">{{ $totalActividades }}</span></div>
                        <div class="text-xs text-slate-500 mt-1 font-medium">Eventos ejecutados vs planeados</div>
                    </div>
                </div>
            </div>

            {{-- FILA 1 DE GRÁFICOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm rounded-2xl p-6 border border-slate-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-black text-slate-800">Cumplimiento por Actividad</h3>
                        <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-1 rounded-md uppercase">Top Rendimiento</span>
                    </div>
                    <div class="h-[300px]">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-2xl p-6 border border-slate-100">
                    <h3 class="text-lg font-black text-slate-800 mb-4">Distribución por Categoría</h3>
                    <div class="h-[300px]">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- FILA 2 DE GRÁFICOS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white shadow-sm rounded-2xl p-6 border border-slate-100">
                    <h3 class="text-lg font-black text-slate-800 mb-4">Evolución Mensual de Cobertura</h3>
                    <div class="h-[280px]">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-2xl p-6 border border-slate-100 flex flex-col justify-center">
                    <h3 class="text-lg font-black text-slate-800 mb-2 text-center">Meta General</h3>
                    <div class="h-[220px] relative">
                        <canvas id="gaugeChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- TABLA DETALLADA CON BARRAS DOBLES --}}
            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-black text-slate-800">Detalle Operativo de Actividades</h3>
                    <p class="text-xs text-slate-500 mt-1">Desglose de metas base y participación excedente.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-slate-100 text-slate-500 text-xs uppercase font-black tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Código</th>
                                <th class="px-6 py-4">Descripción de Actividad</th>
                                <th class="px-6 py-4">Categoría</th>
                                <th class="px-6 py-4">Personas (Avance / Meta)</th>
                                <th class="px-6 py-4 w-48">Nivel de Progreso</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($eventos as $evento)
                                @php
                                    $avance = $evento->total_attendees ?? 0;
                                    $meta = $evento->goal_people;
                                    
                                    // Cálculo matemático para la barra en la tabla
                                    $maxVal = max($meta, $avance > 0 ? $avance : 1);
                                    // Ancho de la barra base (hasta donde pedía la meta)
                                    $w_base = $avance >= $meta ? ($meta / $maxVal * 100) : ($avance / $meta * 100);
                                    // Ancho de la barra excedente (lo que se logró extra)
                                    $w_exceso = $avance > $meta ? (($avance - $meta) / $maxVal * 100) : 0;
                                    
                                    // Porcentaje real en texto
                                    $pct_real = $meta > 0 ? round(($avance / $meta) * 100, 1) : 0;
                                @endphp
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    <td class="px-6 py-4 font-bold text-indigo-700 whitespace-nowrap">{{ $evento->event_code }}</td>
                                    <td class="px-6 py-4 text-slate-700 font-medium">{{ Str::limit($evento->description, 60) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md text-xs font-bold border border-slate-200">
                                            {{ $evento->category->name ?? 'General' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-black text-slate-800">{{ $avance }}</span>
                                            <span class="text-slate-400">/</span>
                                            <span class="text-slate-500 font-medium">{{ $meta }}</span>
                                            @if($avance > $meta)
                                                <span class="ml-2 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">
                                                    +{{ $avance - $meta }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="w-full bg-slate-100 rounded-full h-2.5 flex overflow-hidden border border-slate-200/50">
                                            <div class="bg-indigo-600 h-full transition-all duration-500" style="width: {{ $w_base }}%"></div>
                                            @if($w_exceso > 0)
                                                <div class="bg-indigo-300 h-full transition-all duration-500 border-l border-indigo-400/50" style="width: {{ $w_exceso }}%"></div>
                                            @endif
                                        </div>
                                        <div class="flex justify-between items-center mt-1.5">
                                            <span class="text-[10px] font-black {{ $pct_real >= 100 ? 'text-emerald-600' : 'text-slate-500' }}">
                                                {{ $pct_real }}% Total
                                            </span>
                                            @if($pct_real > 100)
                                                <span class="text-[10px] font-bold text-indigo-400">Meta superada</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js Configuración --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#64748b';

            const chartBar = @json($chartBar);
            const catData = @json($catData);
            const monthly = @json($monthly);
            const porcentajeGlobal = {{ min($porcentajeGlobal, 100) }}; 

            // ==============================================
            // 1. Gráfico de Barras Apiladas (Base y Excedente)
            // ==============================================
            const labelsBar = chartBar.map(i => i.code);
            const dataMetaLine = chartBar.map(i => i.meta);
            
            // LÓGICA: Separar el avance base y el excedente
            const dataBaseIntensa = chartBar.map(i => Math.min(i.avance, i.meta));
            const dataExcedenteSuave = chartBar.map(i => Math.max(0, i.avance - i.meta));

            const ctx1 = document.getElementById('progressChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: labelsBar,
                    datasets: [
                        {
                            label: 'Logro Base (Hasta la meta)',
                            data: dataBaseIntensa,
                            backgroundColor: '#4f46e5', // Indigo intenso
                            borderRadius: function(context) {
                                // Redondea solo si no hay excedente arriba
                                const index = context.dataIndex;
                                return dataExcedenteSuave[index] > 0 ? {topLeft: 0, topRight: 0, bottomLeft: 4, bottomRight: 4} : 4;
                            },
                            stack: 'Stack 0',
                        },
                        {
                            label: 'Logro Excedente',
                            data: dataExcedenteSuave,
                            backgroundColor: '#a5b4fc', // Indigo suave
                            borderRadius: {topLeft: 4, topRight: 4, bottomLeft: 0, bottomRight: 0},
                            stack: 'Stack 0',
                        },
                        {
                            label: 'Línea de Meta',
                            data: dataMetaLine,
                            backgroundColor: 'transparent',
                            borderColor: '#ef4444', // Rojo para marcar el límite
                            borderWidth: 2,
                            borderDash: [5, 5],
                            type: 'line',
                            pointBackgroundColor: '#ef4444',
                            pointRadius: 4,
                            fill: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        x: { stacked: true, grid: { display: false } },
                        y: { stacked: true, beginAtZero: true, border: { dash: [4, 4] } }
                    },
                    plugins: { 
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
                        tooltip: {
                            callbacks: {
                                footer: (tooltipItems) => {
                                    let total = 0;
                                    tooltipItems.forEach(function(tooltipItem) {
                                        if(tooltipItem.datasetIndex === 0 || tooltipItem.datasetIndex === 1) {
                                            total += tooltipItem.parsed.y;
                                        }
                                    });
                                    return 'Total Alcanzado: ' + total;
                                }
                            }
                        }
                    }
                }
            });

            // ==============================================
            // 2. Dona por Categoría
            // ==============================================
            const ctx2 = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(catData),
                    datasets: [{
                        data: Object.values(catData),
                        backgroundColor: ['#4f46e5', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8 } } },
                    cutout: '65%'
                }
            });

            // ==============================================
            // 3. Línea Evolutiva Suavizada
            // ==============================================
            const ctx3 = document.getElementById('evolutionChart').getContext('2d');
            // Crear gradiente para la línea
            let gradientFill = ctx3.createLinearGradient(0, 0, 0, 300);
            gradientFill.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
            gradientFill.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

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
                        label: 'Asistentes Alcanzados',
                        data: monthlyValues,
                        fill: true,
                        backgroundColor: gradientFill,
                        borderColor: '#4f46e5',
                        borderWidth: 3,
                        tension: 0.4, // Curvas suaves
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    scales: { 
                        y: { beginAtZero: true, border: { dash: [4, 4] } },
                        x: { grid: { display: false } }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // ==============================================
            // 4. Dona de Cumplimiento Global (Capped)
            // ==============================================
            const ctx4 = document.getElementById('gaugeChart').getContext('2d');
            const restante = Math.max(0, 100 - porcentajeGlobal);
            new Chart(ctx4, {
                type: 'doughnut',
                data: {
                    labels: ['Logrado', 'Restante'],
                    datasets: [{
                        data: [porcentajeGlobal, restante],
                        backgroundColor: ['#10b981', '#f1f5f9'],
                        borderWidth: 0,
                        cutout: '80%',
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } }
                },
                plugins: [{
                    id: 'centerText',
                    beforeDraw: function(chart) {
                        const { width, height, ctx } = chart;
                        ctx.restore();
                        
                        // Porcentaje
                        ctx.font = '900 36px Inter, sans-serif';
                        ctx.fillStyle = '#0f172a';
                        ctx.textBaseline = 'middle';
                        const text = porcentajeGlobal + "%";
                        const textX = Math.round((width - ctx.measureText(text).width) / 2);
                        const textY = height / 2;
                        ctx.fillText(text, textX, textY - 5);
                        
                        // Subtítulo
                        ctx.font = '600 12px Inter, sans-serif';
                        ctx.fillStyle = '#64748b';
                        const subText = "Cumplido";
                        const subTextX = Math.round((width - ctx.measureText(subText).width) / 2);
                        ctx.fillText(subText, subTextX, textY + 22);
                        ctx.save();
                    }
                }]
            });
        });
    </script>
</x-app-layout>

