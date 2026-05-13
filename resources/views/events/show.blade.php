<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                {{ __('Detalle de Actividad') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition shadow-sm hover:shadow">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            {{-- Barra decorativa --}}
            <div class="h-1.5 w-full bg-gradient-to-r from-blue-400 via-indigo-500 to-blue-600"></div>

            <div class="p-8">
                {{-- Información principal --}}
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $event->event_code }}
                        </span>
                        <span class="text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-full border border-gray-200">
                            {{ $event->category->name ?? 'Sin categoría' }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $event->name ?? $event->description }}</h1>
                    @if($event->poi_code)
                        <p class="text-sm text-gray-500 mt-2 flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Código POI: {{ $event->poi_code }}
                        </p>
                    @endif
                </div>

                {{-- Meta y progreso --}}
                @php
                    $totalAvance = $event->subEvents->sum('attendees_count');
                    $meta = $event->goal_people;
                    $porcentaje = $meta > 0 ? min(100, round(($totalAvance / $meta) * 100, 1)) : 0;
                @endphp
                <div class="mb-8 bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Progreso de la Meta
                    </h3>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm text-gray-700">
                            <span class="text-2xl font-bold text-blue-700">{{ $totalAvance }}</span>
                            <span class="text-gray-500 text-lg"> / {{ $meta }} {{ $event->unit_measure ?? 'personas' }}</span>
                        </span>
                        <span class="text-lg font-bold {{ $porcentaje >= 100 ? 'text-green-600' : 'text-gray-600' }}">
                            {{ $porcentaje }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden shadow-inner">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-3 rounded-full transition-all duration-700"
                             style="width: {{ $porcentaje }}%"></div>
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        Descripción
                    </h3>
                    <div class="text-gray-600 leading-relaxed bg-gray-50 rounded-xl p-4 border border-gray-100">
                        {{ $event->description }}
                    </div>
                </div>

                {{-- Reportes asociados --}}
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                        Reportes de Avance
                    </h3>
                    @if($event->subEvents->isEmpty())
                        <div class="text-center py-8 bg-gray-50 rounded-xl border border-gray-100">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500">No hay reportes para esta actividad.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto border border-gray-100 rounded-xl">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asistentes</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acumulado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $acum = 0; @endphp
                                    @foreach($event->subEvents->sortBy('event_date') as $reporte)
                                        @php $acum += $reporte->attendees_count; @endphp
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-5 py-3 text-sm text-gray-700 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $reporte->event_date->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td class="px-5 py-3 text-sm text-gray-900">{{ $reporte->report_title }}</td>
                                            <td class="px-5 py-3 text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    +{{ $reporte->attendees_count }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-3 text-sm font-medium text-gray-700">{{ $acum }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Botón de volver --}}
                <div class="mt-8 pt-4 border-t border-gray-200 text-right">
                    <a href="{{ route('events.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a la lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>