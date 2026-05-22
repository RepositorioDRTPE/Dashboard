<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-gradient-to-br from-slate-800 to-slate-950 rounded-xl text-white shadow-md">
                    <i class="fa-solid fa-folder-open text-lg"></i>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-slate-800 leading-tight tracking-tight">
                        {{ __('Mis Reportes de Avance') }}
                    </h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Historial de Evidencias Documentadas (A01)</p>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('subevents.trashed') }}" class="group bg-white hover:bg-red-50 text-slate-600 hover:text-red-600 border border-slate-200 hover:border-red-200 font-bold py-2.5 px-4 rounded-xl flex items-center gap-2 transition-all text-xs shadow-sm">
                    <i class="fa-solid fa-trash-can text-slate-400 group-hover:text-red-500 transition-colors"></i>
                    Papelera
                </a>
                <a href="{{ route('subevents.create') }}" class="bg-slate-900 hover:bg-indigo-600 text-white font-black text-xs uppercase tracking-wider py-3 px-5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-circle-plus"></i>
                    Nuevo Reporte
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ showDeleteModal: false, selectedSubEventId: null, selectedSubEventName: '' }" @toggle-delete-modal.window="showDeleteModal = !showDeleteModal; if ($event.detail) { selectedSubEventId = $event.detail.id; selectedSubEventName = $event.detail.name; }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
           
            {{-- MENSAJES DE ÉXITO ESTILIZADOS --}}
            @if(session('success'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-500 opacity-0"
                     class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            {{-- FILTRO AVANZADO DE FUENTES DE FINANCIAMIENTO (TABS SEGMENTADOS) --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-2 text-slate-700">
                    <i class="fa-solid fa-filter text-xs text-slate-400"></i>
                    <span class="text-xs font-black uppercase tracking-wider">Filtrar por Origen de Fondos:</span>
                </div>
                
                <div class="inline-flex p-1 bg-slate-100 rounded-xl w-full sm:w-auto">
                    <button onclick="filterByFunding('all', this)" class="funding-filter-btn px-4 py-2 rounded-lg text-xs font-black uppercase tracking-wider transition-all bg-white text-slate-900 shadow-sm">
                        Todos
                    </button>
                    <button onclick="filterByFunding('gobierno_regional', this)" class="funding-filter-btn px-4 py-2 rounded-lg text-xs font-bold text-slate-500 uppercase tracking-wider transition-all hover:text-slate-800">
                        <i class="fa-solid fa-building-government mr-1 text-indigo-500"></i> Regional
                    </button>
                    <button onclick="filterByFunding('gobierno_central', this)" class="funding-filter-btn px-4 py-2 rounded-lg text-xs font-bold text-slate-500 uppercase tracking-wider transition-all hover:text-slate-800">
                        <i class="fa-solid fa-building-shield mr-1 text-amber-500"></i> SUNAFIL / Central
                    </button>
                </div>
            </div>

            {{-- CONTROL DE ESTADO VACÍO --}}
            @if($subEvents->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-100 p-16 text-center shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 shadow-inner rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i class="fa-regular fa-folder-open text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-800">No se encontraron reportes operativos</h3>
                    <p class="text-sm text-slate-400 font-medium max-w-sm mx-auto mt-1">Comience registrando una nueva evidencia física de avance vinculada al POI.</p>
                    <div class="mt-6">
                        <a href="{{ route('subevents.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-wider hover:bg-indigo-600 transition-colors shadow">
                            <i class="fa-solid fa-plus"></i> Registrar Primer Avance
                        </a>
                    </div>
                </div>
            @else
                {{-- ESTRUCTURA MATRIZ DE LA TABLA PREMIUM --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left divide-y divide-slate-100" id="reportes-table">
                            <thead class="bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider">
                                <tr>
                                    <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-slate-100/80 transition select-none w-36" data-sort="fecha">
                                        <div class="flex items-center gap-1.5">
                                            Fecha de Meta
                                            <span class="text-indigo-600 transition-opacity" id="fecha-indicator">↓</span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-slate-100/80 transition select-none" data-sort="actividad">
                                        <div class="flex items-center gap-1.5">
                                            ActividadPOI / Título Descriptivo
                                            <span class="text-indigo-600 transition-opacity" id="actividad-indicator"></span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 cursor-pointer hover:bg-slate-100/80 transition select-none w-64" data-sort="avance">
                                        <div class="flex items-center gap-1.5">
                                            Métrica de Cobertura Capped
                                            <span class="text-indigo-600 transition-opacity" id="avance-indicator"></span>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 w-32">Evidencia</th>
                                    <th scope="col" class="px-6 py-4 text-center w-36">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="table-body">
                                {{-- Inyección de Nodos Dinámicos controlada por JavaScript --}}
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($subEvents, 'links'))
                        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
                            {{ $subEvents->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- CONFIRMACIÓN DE ELIMINACIÓN MODAL ALTA ESTÉTICA --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm" role="dialog" aria-modal="true">
            <div class="bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-100 max-w-md w-full p-6 sm:p-8 space-y-6" @click.away="showDeleteModal = false" x-transition>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 border border-red-100 text-red-600 flex items-center justify-center shrink-0 shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation text-lg animate-bounce"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-lg font-black text-slate-900">¿Mover reporte a la papelera?</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed" x-text="'Se archivará el registro «' + selectedSubEventName + '». Podrá ser restaurado desde la papelera corporativa.'"></p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button @click="showDeleteModal = false" type="button" class="px-4 py-2.5 rounded-xl text-slate-500 hover:text-slate-800 font-bold text-xs uppercase tracking-wider transition-colors">
                        Cancelar
                    </button>
                    <form method="POST" :action="'{{ url('subevents') }}/' + selectedSubEventId">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl transition-all shadow-md">
                            Confirmar Archivo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- LÓGICA DE CONTROL AVANZADA (Filtro Cruzado de Financiamiento y Puntuaciones Capped) --}}
    <script>
        const reportesData = @json($subEvents);
       
        // Estados analíticos de orden y filtrado
        let currentSort = 'fecha';
        let sortDirection = 'desc'; 
        let currentFundingFilter = 'all'; // 'all', 'gobierno_regional', 'gobierno_central'

        const tbody = document.getElementById('table-body');
        const indicators = {
            fecha: document.getElementById('fecha-indicator'),
            actividad: document.getElementById('actividad-indicator'),
            avance: document.getElementById('avance-indicator')
        };

        // FILTRADO DINÁMICO DE PESTAÑAS (Origen de Fondos)
        function filterByFunding(filterType, buttonEl) {
            currentFundingFilter = filterType;
            
            // Re-estilizar botones de control de pestañas
            document.querySelectorAll('.funding-filter-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-slate-900', 'shadow-sm', 'font-black');
                btn.classList.add('text-slate-500', 'font-bold');
            });
            buttonEl.classList.remove('text-slate-500', 'font-bold');
            buttonEl.classList.add('bg-white', 'text-slate-900', 'shadow-sm', 'font-black');
            
            renderTable();
        }

        // CONTROL MATEMÁTICO INTEGRADO (Evita desvíos acumulativos por excedentes)
        function groupAndCalculateProgress(reports) {
            const grouped = {};
            reports.forEach(r => {
                const eid = r.event_id;
                if (!grouped[eid]) {
                    grouped[eid] = {
                        event: r.event || null,
                        goal: (r.event && r.event.goal_people) ? r.event.goal_people : 0,
                        reports: []
                    };
                }
                grouped[eid].reports.push(r);
            });
           
            // Ordenamiento por fechas antes del cálculo progresivo
            for (const eid in grouped) {
                grouped[eid].reports.sort((a, b) => new Date(a.event_date) - new Date(b.event_date));
            }
           
            const acumMap = {};
            for (const eid in grouped) {
                const data = grouped[eid];
                const reportsArray = data.reports || [];
                let runningSum = 0; // Sumador acumulativo en la línea de tiempo

                reportsArray.forEach(r => {
                    const actualAttendees = r.attendees_count || 0;
                    runningSum += actualAttendees;
                    const meta = data.goal || 1;

                    // RECTIFICACIÓN CLAVE: El avance de la barra base se topa rigurosamente al 100%
                    const maxVal = Math.max(meta, runningSum);
                    const w_base = runningSum >= meta ? (meta / maxVal * 100) : (runningSum / meta * 100);
                    const w_exceso = runningSum > meta ? ((runningSum - meta) / maxVal * 100) : 0;
                    const porcentajeNetoCapped = Math.min(100, Math.round((runningSum / meta) * 100));

                    acumMap[r.id] = {
                        acumulado: runningSum,
                        meta: meta,
                        w_base: w_base,
                        w_exceso: w_exceso,
                        porcentaje: porcentajeNetoCapped,
                        isSuperado: runningSum > meta,
                        excedenteIndividual: Math.max(0, runningSum - meta)
                    };
                });
            }
            return { grouped, acumMap };
        }

        window.confirmDelete = function(id, title) {
            window.dispatchEvent(new CustomEvent('toggle-delete-modal', {
                detail: { id: id, name: title }
            }));
        };

        function renderTable() {
            if(!tbody) return;
            
            // 1. Clonamos y aplicamos el filtro por fuente de financiamiento
            let filteredReports = [...reportesData];
            if (currentFundingFilter !== 'all') {
                filteredReports = filteredReports.filter(r => r.event?.funding_source === currentFundingFilter);
            }

            // 2. Ordenamiento Matriz
            if (currentSort === 'fecha') {
                filteredReports.sort((a, b) => sortDirection === 'asc' ? new Date(a.event_date) - new Date(b.event_date) : new Date(b.event_date) - new Date(a.event_date));
            } else if (currentSort === 'actividad') {
                filteredReports.sort((a, b) => {
                    const codeA = a.event?.event_code || '';
                    const codeB = b.event?.event_code || '';
                    return sortDirection === 'asc' ? codeA.localeCompare(codeB) : codeB.localeCompare(codeA);
                });
            } else if (currentSort === 'avance') {
                const { acumMap } = groupAndCalculateProgress(filteredReports);
                filteredReports.sort((a, b) => {
                    const pctA = acumMap[a.id]?.porcentaje || 0;
                    const pctB = acumMap[b.id]?.porcentaje || 0;
                    return sortDirection === 'asc' ? pctA - pctB : pctB - pctA;
                });
            }

            const { acumMap } = groupAndCalculateProgress(filteredReports);
           
            let html = '';
            if (filteredReports.length === 0) {
                html = `<tr><td colspan="5" class="text-center py-10 text-slate-400 font-medium">No hay reportes bajo los filtros seleccionados.</td></tr>`;
            } else {
                for (const reporte of filteredReports) {
                    const acum = acumMap[reporte.id];
                    const eventCode = reporte.event?.event_code ?? 'N/A';
                    const eventName = reporte.event?.description ?? '';
                    const fundingSource = reporte.event?.funding_source ?? 'gobierno_regional';
                    const fecha = new Date(reporte.event_date).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
                    const fotosCount = reporte.photos ? (Array.isArray(reporte.photos) ? reporte.photos.length : JSON.parse(reporte.photos).length) : 0;
                    const cleanTitle = reporte.report_title.replace(/'/g, "\\'");

                    // Insignia dinámica de Financiamiento
                    const fundingBadge = fundingSource === 'gobierno_regional' 
                        ? `<span class="bg-indigo-50 border border-indigo-100 text-indigo-700 text-[9px] font-black px-1.5 py-0.5 rounded uppercase tracking-wider">Regional</span>`
                        : `<span class="bg-amber-50 border border-amber-100 text-amber-700 text-[9px] font-black px-1.5 py-0.5 rounded uppercase tracking-wider">SUNAFIL</span>`;

                    html += `
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-600">
                                <div class="flex items-center gap-2">
                                    <i class="fa-regular fa-calendar-days text-slate-400"></i>
                                    ${fecha}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md font-mono font-black bg-slate-900 text-white shadow-inner">${eventCode}</span>
                                        ${fundingBadge}
                                    </div>
                                    <p class="mt-1.5 text-slate-900 font-bold text-sm leading-snug">${reporte.report_title}</p>
                                    ${eventName ? `<p class="text-[11px] text-slate-400 font-medium mt-0.5 line-clamp-1">${eventName}</p>` : ''}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1.5 min-w-[200px]">
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="font-bold text-slate-700">
                                            <i class="fa-solid fa-square-plus text-emerald-500 mr-1"></i>+${reporte.attendees_count} <span class="text-slate-400 font-normal">(&Sigma; ${numberWithCommas(acum.acumulado)})</span>
                                        </span>
                                        <span class="font-black text-slate-900 bg-slate-100 border border-slate-200 px-1.5 py-0.5 rounded">${acum.porcentaje}% Real</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 flex overflow-hidden border border-slate-200/50 shadow-inner">
                                        <div class="h-full transition-all duration-700 ${acum.porcentaje >= 100 ? 'bg-emerald-500' : 'bg-indigo-600'}" style="width: ${acum.w_base}%"></div>
                                        <div class="bg-amber-400 h-full transition-all duration-700" style="width: ${acum.w_exceso}%"></div>
                                    </div>
                                    <div class="flex justify-between items-center text-[10px]">
                                        <span class="text-slate-400 font-semibold">Meta Base: ${numberWithCommas(acum.meta)}</span>
                                        ${acum.isSuperado ? `<span class="font-black text-amber-600 bg-amber-50 border border-amber-100 px-1 rounded"><i class="fa-solid fa-arrow-trend-up animate-pulse"></i> +${numberWithCommas(acum.excedenteIndividual)} Sobrecumplido</span>` : ''}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-xs font-bold ${fotosCount > 0 ? 'text-blue-600' : 'text-slate-400'}">
                                    <i class="fa-regular fa-image"></i>
                                    ${fotosCount > 0 ? `${fotosCount} Captura(s)` : 'Sin Evidencia'}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="/subevents/${reporte.id}" class="p-2 bg-slate-50 text-slate-600 rounded-xl hover:bg-slate-100 border border-slate-200 transition-colors" title="Auditar Reporte"><i class="fa-solid fa-eye text-xs"></i></a>
                                    <a href="/subevents/${reporte.id}/edit" class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-100 border border-indigo-100/50 transition-colors" title="Modificar"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                                    <button onclick="confirmDelete(${reporte.id}, '${cleanTitle}')" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 border border-red-100/50 transition-colors" title="Mover a Papelera"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                }
            }
            tbody.innerHTML = html;
            updateSortIndicators();
        }

        function updateSortIndicators() {
            Object.keys(indicators).forEach(key => {
                if(indicators[key]) {
                    indicators[key].style.opacity = '0';
                    indicators[key].textContent = '';
                }
            });

            if (indicators[currentSort]) {
                indicators[currentSort].style.opacity = '1';
                if (currentSort === 'fecha') indicators[currentSort].textContent = sortDirection === 'asc' ? '↓' : '↑';
                if (currentSort === 'actividad') indicators[currentSort].textContent = sortDirection === 'asc' ? '↓ (A-Z)' : '↑ (Z-A)';
                if (currentSort === 'avance') indicators[currentSort].textContent = sortDirection === 'asc' ? '↓ (Menor)' : '↑ (Mayor)';
            }
        }

        function numberWithCommas(x) { return x.toString().replace(/\B(?=(\d{3})+(WARN\d)?)/g, ","); }

        // Mapeo de eventos clic de cabecera para reordenamiento
        document.querySelectorAll('[data-sort]').forEach(header => {
            header.addEventListener('click', () => {
                const sortKey = header.dataset.sort;
                if (currentSort === sortKey) {
                    sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSort = sortKey;
                    sortDirection = 'asc';
                }
                renderTable();
            });
        });

        // Inicialización Forzada
        renderTable();
    </script>
</x-app-layout>

