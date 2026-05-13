<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mis Reportes de Avance') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('subevents.trashed') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Papelera
                </a>
                <a href="{{ route('subevents.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo Reporte
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ showDeleteModal: false, selectedSubEventId: null, selectedSubEventName: '' }" @toggle-delete-modal.window="showDeleteModal = !showDeleteModal; if ($event.detail) { selectedSubEventId = $event.detail.id; selectedSubEventName = $event.detail.name; }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Mensaje de éxito con fade out progresivo --}}
            @if(session('success'))
                <div x-data="{ show: true, fade: false }"
                     x-show="show"
                     x-transition:leave="transition-opacity ease-out duration-1000"
                     x-init="setTimeout(() => { fade = true; setTimeout(() => show = false, 1000); }, 4000)"
                     class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded shadow-sm"
                     :class="{ 'opacity-0': fade }"
                     style="transition: opacity 1s ease-out;">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($subEvents->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay reportes</h3>
                        <p class="mt-1 text-sm text-gray-500">Comienza registrando un nuevo reporte de avance.</p>
                        <div class="mt-6">
                            <a href="{{ route('subevents.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Nuevo Reporte
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="reportes-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition select-none" data-sort="fecha">
                                            <div class="flex items-center gap-1">
                                                Fecha
                                                <span class="sort-indicator text-indigo-500 opacity-0 transition" id="fecha-indicator">↓</span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition select-none" data-sort="actividad">
                                            <div class="flex items-center gap-1">
                                                Actividad / Título
                                                <span class="sort-indicator text-indigo-500 opacity-0 transition" id="actividad-indicator"></span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition select-none" data-sort="avance">
                                            <div class="flex items-center gap-1">
                                                Avance
                                                <span class="sort-indicator text-indigo-500 opacity-0 transition" id="avance-indicator"></span>
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evidencia</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="table-body">
                                    {{-- Se llena dinámicamente con JS --}}
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($subEvents, 'links'))
                            <div class="mt-6">
                                {{ $subEvents->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Modal de confirmación para eliminar --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false"></div>

                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">¿Estás seguro de mover este reporte a la papelera?</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" x-text="'Estás a punto de mover «' + selectedSubEventName + '» a la papelera. Podrás restaurarlo después.'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form method="POST" :action="'{{ url('subevents') }}/' + selectedSubEventId">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                                Mover a papelera
                            </button>
                        </form>
                        <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Datos originales desde Blade
        const reportesData = @json($subEvents);
        
        // Estado de ordenación
        let currentSort = 'fecha';
        let sortDirection = 'asc';
        let avanceDirection = 'desc';

        const tbody = document.getElementById('table-body');
        const fechaHeader = document.querySelector('[data-sort="fecha"]');
        const actividadHeader = document.querySelector('[data-sort="actividad"]');
        const avanceHeader = document.querySelector('[data-sort="avance"]');
        const fechaIndicator = document.getElementById('fecha-indicator');
        const actividadIndicator = document.getElementById('actividad-indicator');
        const avanceIndicator = document.getElementById('avance-indicator');

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
            
            for (const eid in grouped) {
                const group = grouped[eid];
                if (group.reports && Array.isArray(group.reports)) {
                    group.reports.sort((a, b) => new Date(a.event_date) - new Date(b.event_date));
                }
            }
            
            const acumMap = {};
            for (const eid in grouped) {
                const data = grouped[eid];
                const reportsArray = data.reports || [];
                let running = 0;
                reportsArray.forEach(r => {
                    running += r.attendees_count || 0;
                    const meta = data.goal || 0;
                    const porcentaje = meta > 0 ? Math.min(100, Math.round((running / meta) * 100 * 10) / 10) : 0;
                    acumMap[r.id] = {
                        acumulado: running,
                        meta: meta,
                        porcentaje: porcentaje
                    };
                });
            }
            
            return { grouped, acumMap };
        }

        // Función para abrir el modal de eliminación (llamada desde los botones generados)
        window.confirmDelete = function(id, title) {
            window.dispatchEvent(new CustomEvent('toggle-delete-modal', {
                detail: { id: id, name: title }
            }));
        };

        function renderTable() {
            let sortedReports = [...reportesData];

            if (currentSort === 'fecha') {
                sortedReports.sort((a, b) => {
                    return sortDirection === 'asc' 
                        ? new Date(a.event_date) - new Date(b.event_date)
                        : new Date(b.event_date) - new Date(a.event_date);
                });
            } else if (currentSort === 'actividad') {
                sortedReports.sort((a, b) => {
                    const codeA = a.event?.event_code || '';
                    const codeB = b.event?.event_code || '';
                    if (codeA === codeB) {
                        return sortDirection === 'asc'
                            ? new Date(a.event_date) - new Date(b.event_date)
                            : new Date(b.event_date) - new Date(a.event_date);
                    }
                    return sortDirection === 'asc'
                        ? codeA.localeCompare(codeB)
                        : codeB.localeCompare(codeA);
                });
            } else if (currentSort === 'avance') {
                const { grouped } = groupAndCalculateProgress(sortedReports);
                const progressMap = {};
                for (const [eventId, data] of Object.entries(grouped)) {
                    const reportsArray = data.reports || [];
                    if (reportsArray.length > 0) {
                        let running = 0;
                        reportsArray.forEach(r => running += r.attendees_count || 0);
                        const goal = data.goal || 0;
                        const percentage = goal > 0 ? (running / goal) * 100 : 0;
                        progressMap[eventId] = percentage;
                    }
                }
                sortedReports.sort((a, b) => {
                    const progA = progressMap[a.event_id] || 0;
                    const progB = progressMap[b.event_id] || 0;
                    if (progA !== progB) {
                        return avanceDirection === 'desc' ? progB - progA : progA - progB;
                    }
                    return new Date(a.event_date) - new Date(b.event_date);
                });
            }

            const { acumMap } = groupAndCalculateProgress(sortedReports);
            
            let html = '';
            for (const reporte of sortedReports) {
                const acum = acumMap[reporte.id];
                const eventCode = reporte.event?.event_code ?? 'N/A';
                const eventName = reporte.event?.name ?? '';
                const fecha = new Date(reporte.event_date).toLocaleDateString('es-ES');
                const fotosCount = reporte.photos ? reporte.photos.length : 0;
                const reportTitle = reporte.report_title.replace(/'/g, "\\'");
                
                html += `
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                ${fecha}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    ${eventCode}
                                </span>
                                <p class="mt-1 text-gray-900 font-medium">${reporte.report_title}</p>
                                ${eventName ? `<p class="text-xs text-gray-500">${eventName}</p>` : ''}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col space-y-1 min-w-[180px]">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        +${reporte.attendees_count}
                                    </span>
                                </div>
                                ${acum ? `
                                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden shadow-inner">
                                        <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-2 rounded-full transition-all duration-700 ease-out" style="width: ${acum.porcentaje}%" title="${acum.acumulado} de ${acum.meta} personas"></div>
                                    </div>
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="font-medium text-gray-700">
                                            <span class="text-indigo-700 font-bold">${acum.acumulado}</span> 
                                            <span class="text-gray-500">/ ${acum.meta}</span>
                                        </span>
                                        <span class="font-semibold ${acum.porcentaje >= 100 ? 'text-green-600' : 'text-gray-600'}">
                                            ${acum.porcentaje}%
                                        </span>
                                    </div>
                                ` : '<span class="text-xs text-gray-400">Meta no definida</span>'}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            ${fotosCount > 0 ? `
                                <span class="inline-flex items-center text-blue-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    ${fotosCount} foto(s)
                                </span>
                            ` : `
                                <span class="inline-flex items-center text-gray-400">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Sin fotos
                                </span>
                            `}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="/subevents/${reporte.id}" class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 p-2 rounded-full transition" title="Ver detalles">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="/subevents/${reporte.id}/edit" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-full transition" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button onclick="confirmDelete(${reporte.id}, '${reportTitle}')" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-full transition" title="Mover a papelera">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }
            tbody.innerHTML = html;
            updateSortIndicators();
        }

        function updateSortIndicators() {
            fechaIndicator.style.opacity = '0';
            actividadIndicator.style.opacity = '0';
            avanceIndicator.style.opacity = '0';
            fechaIndicator.textContent = '↓';
            actividadIndicator.textContent = '';
            avanceIndicator.textContent = '';
            
            if (currentSort === 'fecha') {
                fechaIndicator.style.opacity = '1';
                fechaIndicator.textContent = sortDirection === 'asc' ? '↓' : '↑';
            } else if (currentSort === 'actividad') {
                actividadIndicator.style.opacity = '1';
                actividadIndicator.textContent = sortDirection === 'asc' ? '↓ (A-Z)' : '↑ (Z-A)';
            } else if (currentSort === 'avance') {
                avanceIndicator.style.opacity = '1';
                avanceIndicator.textContent = avanceDirection === 'desc' ? '↓ (Mayor progreso)' : '↑ (Menor progreso)';
            }
        }

        fechaHeader.addEventListener('click', () => {
            if (currentSort === 'fecha') {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = 'fecha';
                sortDirection = 'asc';
            }
            renderTable();
        });

        actividadHeader.addEventListener('click', () => {
            if (currentSort === 'actividad') {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = 'actividad';
                sortDirection = 'asc';
            }
            renderTable();
        });

        avanceHeader.addEventListener('click', () => {
            if (currentSort === 'avance') {
                avanceDirection = avanceDirection === 'desc' ? 'asc' : 'desc';
            } else {
                currentSort = 'avance';
                avanceDirection = 'desc';
            }
            renderTable();
        });

        renderTable();
    </script>
</x-app-layout>