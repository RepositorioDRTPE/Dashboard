<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-gradient-to-br from-slate-800 to-slate-950 rounded-xl text-white shadow-md">
                    <i class="fa-solid fa-briefcase text-lg"></i>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-slate-800 leading-tight tracking-tight">
                        {{ __('Actividades Operativas (Metas Físicas)') }}
                    </h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Plan Operativo Institucional (POI) Año Fiscal 2026</p>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('events.trashed') }}" class="group bg-white hover:bg-red-50 text-slate-600 hover:text-red-600 border border-slate-200 hover:border-red-200 font-bold py-2.5 px-4 rounded-xl flex items-center gap-2 transition-all text-xs shadow-sm">
                    <i class="fa-solid fa-trash-can text-slate-400 group-hover:text-red-500 transition-colors"></i>
                    Papelera
                </a>
                <a href="{{ route('events.create') }}" class="bg-slate-900 hover:bg-indigo-600 text-white font-black text-xs uppercase tracking-wider py-3 px-5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-circle-plus"></i>
                    Nueva Actividad
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ showDeleteModal: false, selectedEventId: null, selectedEventName: '', currentFilter: 'all' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
           
            {{-- NOTIFICACIONES DE ÉXITO CON FADE OUT --}}
            @if(session('success'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 3500)"
                     x-transition:leave="transition ease-in duration-500 opacity-0"
                     class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            {{-- SELECTOR SEGMENTADO DE FILTRADO (RADIO TABS) --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-2 text-slate-700">
                    <i class="fa-solid fa-filter text-xs text-slate-400"></i>
                    <span class="text-xs font-black uppercase tracking-wider">Filtrar por Asignación Presupuestal:</span>
                </div>
                
                <div class="inline-flex p-1 bg-slate-100 rounded-xl w-full sm:w-auto">
                    <button @click="currentFilter = 'all'" :class="currentFilter === 'all' ? 'bg-white text-slate-900 shadow-sm font-black' : 'text-slate-500 font-bold hover:text-slate-800'" class="px-4 py-2 rounded-lg text-xs uppercase tracking-wider transition-all">
                        Todos
                    </button>
                    <button @click="currentFilter = 'gobierno_regional'" :class="currentFilter === 'gobierno_regional' ? 'bg-white text-slate-900 shadow-sm font-black' : 'text-slate-500 font-bold hover:text-slate-800'" class="px-4 py-2 rounded-lg text-xs uppercase tracking-wider transition-all">
                        <i class="fa-solid fa-building-government mr-1 text-indigo-500"></i> Regional
                    </button>
                    <button @click="currentFilter = 'gobierno_central'" :class="currentFilter === 'gobierno_central' ? 'bg-white text-slate-900 shadow-sm font-black' : 'text-slate-500 font-bold hover:text-slate-800'" class="px-4 py-2 rounded-lg text-xs uppercase tracking-wider transition-all">
                        <i class="fa-solid fa-building-shield mr-1 text-amber-500"></i> SUNAFIL
                    </button>
                </div>
            </div>

            @if($events->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-100 p-16 text-center shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 shadow-inner rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i class="fa-regular fa-folder-open text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-800">No hay actividades operativas</h3>
                    <p class="text-sm text-slate-400 font-medium max-w-sm mx-auto mt-1">Comienza formulando tu primera tarea analítica dentro del Plan Operativo.</p>
                </div>
            @else
                {{-- MATRIZ DE LA TABLA CORREGIDA Y RE-ESTILIZADA --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider">
                                <tr>
                                    <th scope="col" class="px-6 py-4 w-32">Código</th>
                                    <th scope="col" class="px-6 py-4 w-56">Actividad General (PP)</th>
                                    <th scope="col" class="px-6 py-4">Descripción Operativa</th>
                                    <th scope="col" class="px-6 py-4 w-64">Progreso / Meta Física</th>
                                    <th scope="col" class="px-6 py-4 text-center w-36">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($events as $event)
                                    @php
                                        $totalAvance = $event->subEvents->sum('attendees_count') ?? 0;
                                        $meta = $event->goal_people > 0 ? $event->goal_people : 1;
                                        
                                        // RECTIFICACIÓN GRÁFICA DE BARRAS DOBLES PROPORCIONALES
                                        $maxVal = max($meta, $totalAvance);
                                        $w_base = $totalAvance >= $meta ? ($meta / $maxVal * 100) : ($totalAvance / $meta * 100);
                                        $w_exceso = $totalAvance > $meta ? (($totalAvance - $meta) / $maxVal * 100) : 0;

                                        $porcentajeReal = round(($totalAvance / $meta) * 100, 1);
                                    @endphp
                                    
                                    <tr x-show="currentFilter === 'all' || currentFilter === '{{ $event->funding_source }}'" 
                                        x-transition.opacity.duration.300ms
                                        class="hover:bg-slate-50/50 transition-colors">
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md font-mono font-black bg-slate-900 text-white shadow-inner text-xs">
                                                {{ $event->event_code }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 text-xs font-bold text-slate-700">
                                            <div class="flex flex-col gap-1">
                                                <span class="line-clamp-2">{{ $event->category->name ?? 'Sin Eje Estratégico' }}</span>
                                                @if(($event->funding_source ?? 'gobierno_regional') === 'gobierno_regional')
                                                    <span class="text-[9px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded self-start uppercase tracking-wider">GORE Regional</span>
                                                @else
                                                    <span class="text-[9px] font-black text-amber-700 bg-amber-50 border border-amber-100 px-1.5 py-0.5 rounded self-start uppercase tracking-wider">SUNAFIL / Central</span>
                                                @endif
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 text-xs text-slate-600 font-medium leading-relaxed max-w-xs">
                                            <div class="line-clamp-2" title="{{ $event->description }}">
                                                {{ $event->description }}
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col space-y-1.5 min-w-[200px]">
                                                <div class="flex justify-between items-center text-xs">
                                                    <span class="font-bold text-slate-800">
                                                        <span class="text-indigo-600 font-black">{{ number_format($totalAvance) }}</span>
                                                        <span class="text-slate-400 font-normal">/</span>
                                                        <span class="text-slate-500">{{ number_format($meta) }}</span>
                                                        <span class="text-[10px] text-slate-400 font-medium lowercase">({{ $event->unit_measure ?? 'personas' }})</span>
                                                    </span>
                                                    <span class="font-black text-slate-900 bg-slate-100 border border-slate-200 px-1.5 py-0.5 rounded text-[10px] shadow-sm">
                                                        {{ $porcentajeReal }}%
                                                    </span>
                                                </div>
                                                
                                                {{-- Barras Combinadas Antidistorsión --}}
                                                <div class="w-full bg-slate-100 rounded-full h-2.5 flex overflow-hidden border border-slate-200/50 shadow-inner">
                                                    <div class="h-full transition-all duration-500 {{ $porcentajeReal >= 100 ? 'bg-emerald-500' : 'bg-indigo-600' }}" style="width: {{ $w_base }}%"></div>
                                                    @if($w_exceso > 0)
                                                        <div class="bg-amber-400 h-full transition-all duration-500 border-l border-white/20" style="width: {{ $w_exceso }}%"></div>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex justify-between items-center text-[10px] h-3">
                                                    @if($totalAvance > $meta)
                                                        <span class="font-black text-amber-600 bg-amber-50 border border-amber-100 px-1 rounded">
                                                            <i class="fa-solid fa-arrow-trend-up animate-pulse"></i> +{{ number_format($totalAvance - $meta) }} Excedentes
                                                        </span>
                                                    @else
                                                        <span class="text-slate-400 font-medium">Faltan: {{ number_format(max(0, $meta - $totalAvance)) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-center items-center gap-2">
                                                <a href="{{ route('events.show', $event) }}" class="p-2 bg-slate-50 text-slate-600 rounded-xl hover:bg-slate-100 border border-slate-200 transition-colors" title="Ver Detalles"><i class="fa-solid fa-eye text-xs"></i></a>
                                                <a href="{{ route('events.edit', $event) }}" class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-100 border border-indigo-100/50 transition-colors" title="Editar"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                                                <button @click="selectedEventId = {{ $event->id }}; selectedEventName = '{{ addslashes($event->event_code) }}'; showDeleteModal = true" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 border border-red-100/50 transition-colors" title="Mover a Papelera"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- CONFIRMACIÓN DE ELIMINACIÓN MODAL OPTIMIZADA --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm" role="dialog" aria-modal="true">
            <div class="bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-100 max-w-md w-full p-6 sm:p-8 space-y-6" @click.away="showDeleteModal = false" x-transition>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 border border-red-100 text-red-600 flex items-center justify-center shrink-0 shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation text-lg animate-bounce"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-lg font-black text-slate-900">¿Mover actividad a la papelera?</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed" x-text="'Se archivará el registro operativo «' + selectedEventName + '». Toda la cronología vinculada dejará de ser visible en el portal público.'"></p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button @click="showDeleteModal = false" type="button" class="px-4 py-2.5 rounded-xl text-slate-500 hover:text-slate-800 font-bold text-xs uppercase tracking-wider transition-colors">
                        Cancelar
                    </button>
                    <form method="POST" :action="'{{ url('events') }}/' + selectedEventId">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl transition-all shadow-md">
                            Confirmar Archivo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

