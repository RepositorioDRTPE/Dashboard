<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-gradient-to-br from-slate-800 to-slate-950 rounded-xl text-white shadow-md">
                    <i class="fa-solid fa-chalkboard-user text-lg"></i>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-slate-800 tracking-tight leading-none">
                        Control de Talleres y Capacitaciones
                    </h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Planificación y Banco de Evidencias Fotográficas</p>
                </div>
            </div>
            <a href="{{ route('workshops.create') }}" class="bg-slate-900 hover:bg-indigo-600 text-white font-black text-xs uppercase tracking-wider py-3.5 px-6 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2 w-full sm:w-auto justify-center">
                <i class="fa-solid fa-circle-plus text-sm"></i>
                Programar Actividad
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- ALERTAS DE SISTEMA --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)" x-transition:leave="transition ease-in duration-500 opacity-0" class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            {{-- TABLA MATRIZ DE COMPORTAMIENTO OPERATIVO --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-md overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-black text-slate-800">Panel General de Eventos</h3>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium">Cronograma regulado automáticamente por el horario del servidor.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left divide-y divide-slate-100">
                        <thead class="bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-4 w-40">Clasificación</th>
                                <th scope="col" class="px-6 py-4">Descripción del Evento</th>
                                <th scope="col" class="px-6 py-4 w-48">Fecha de Ejecución</th>
                                <th scope="col" class="px-6 py-4 w-40 text-center">Estado Temporal</th>
                                <th scope="col" class="px-6 py-4 text-center w-36">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($workshops as $workshop)
                                @php
                                    $isPast = $workshop->scheduled_at->isPast();
                                    $hasPhotos = $workshop->photos && count($workshop->photos) > 0;
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    
                                    {{-- Clasificación --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($workshop->type === 'capacitacion')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-black bg-red-50 text-red-700 border border-red-100 uppercase tracking-wider">
                                                <i class="fa-solid fa-user-graduate"></i> Taller / Cap.
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-wider">
                                                <i class="fa-solid fa-handshake"></i> Coordinación
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Título y descripción --}}
                                    <td class="px-6 py-4">
                                        <div class="text-slate-900 font-bold text-sm leading-snug max-w-xs md:max-w-md lg:max-w-lg">{{ $workshop->title }}</div>
                                        <p class="text-[11px] text-slate-400 font-medium mt-1 line-clamp-1" title="{{ $workshop->description }}">{{ $workshop->description }}</p>
                                    </td>

                                    {{-- Fecha --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-600">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-regular fa-clock text-slate-400"></i>
                                            {{ $workshop->scheduled_at->format('d/m/Y h:i A') }}
                                        </div>
                                    </td>

                                    {{-- Estado Temporal Automático --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($isPast)
                                            <div class="flex flex-col items-center gap-1">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-black uppercase tracking-wider">
                                                    Ejecutado
                                                </span>
                                                <span class="text-[9px] font-bold {{ $hasPhotos ? 'text-blue-500' : 'text-amber-500 animate-pulse' }}">
                                                    <i class="fa-solid {{ $hasPhotos ? 'fa-images' : 'fa-camera' }} mr-0.5"></i>
                                                    {{ $hasPhotos ? count($workshop->photos).' Evidencias' : 'Faltan Fotos' }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-blue-50 border border-blue-200 text-blue-700 text-[10px] font-black uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-ping"></span>
                                                En Vigencia
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('workshops.edit', $workshop->id) }}" class="p-2 {{ $isPast && !$hasPhotos ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-indigo-50 text-indigo-600 border border-indigo-100/50' }} rounded-xl hover:opacity-80 transition-all" title="Gestionar Actividad y Fotos">
                                                <i class="fa-solid {{ $isPast ? 'fa-camera-retro' : 'fa-pen-to-square' }} text-xs"></i>
                                            </a>
                                            <form action="{{ route('workshops.destroy', $workshop->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar de forma permanente esta actividad?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 border border-red-100/50 transition-colors">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-slate-400 font-medium">
                                        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-2 text-slate-300 border border-slate-100 shadow-inner"><i class="fa-solid fa-box-open text-base"></i></div>
                                        No se registran talleres o reuniones de trabajo en el periodo actual.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($workshops, 'links'))
                    <div class="p-6 border-t border-slate-100 bg-slate-50/30">
                        {{ $workshops->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

