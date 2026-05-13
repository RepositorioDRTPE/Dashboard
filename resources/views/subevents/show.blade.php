<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del Reporte') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('subevents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
                <a href="{{ route('subevents.edit', $subevent) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Encabezado --}}
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ $subevent->event->event_code ?? 'N/A' }}
                                </span>
                                <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $subevent->report_title }}</h1>
                                <p class="text-sm text-gray-500 mt-1">{{ $subevent->event->name ?? 'Actividad no especificada' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-3xl font-bold text-green-600">+{{ $subevent->attendees_count }}</span>
                                <p class="text-sm text-gray-500">Asistentes reportados</p>
                            </div>
                        </div>
                    </div>

                    {{-- Detalles en grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Información General</h3>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Fecha del evento:</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($subevent->event_date)->format('d/m/Y') }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Registrado el:</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        {{ $subevent->created_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                @if($subevent->updated_at->ne($subevent->created_at))
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Última actualización:</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        {{ $subevent->updated_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Video de Evidencia</h3>
                            @if($subevent->youtube_url)
                                <a href="{{ $subevent->youtube_url }}" target="_blank" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                                    </svg>
                                    Ver video en YouTube
                                </a>
                            @else
                                <p class="text-sm text-gray-400">No se proporcionó enlace de video</p>
                            @endif
                        </div>
                    </div>

                    {{-- Comentario --}}
                    @if($subevent->comment)
                    <div class="mb-8">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Comentario / Observaciones</h3>
                        <div class="bg-gray-50 rounded-lg p-4 text-gray-700">
                            {{ $subevent->comment }}
                        </div>
                    </div>
                    @endif

                    {{-- Galería de fotos --}}
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Evidencia Fotográfica</h3>
                        @if($subevent->photos && count($subevent->photos) > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($subevent->photos as $photo)
                                    <div class="relative group">
                                        <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $photo) }}" alt="Foto evidencia" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-75 transition">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No hay fotos subidas para este reporte</p>
                            </div>
                        @endif
                    </div>

                    {{-- Botón de eliminar (opcional) --}}
                    <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end">
                        <form action="{{ route('subevents.destroy', $subevent) }}" method="POST" onsubmit="return confirm('¿Mover este reporte a la papelera?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Mover a papelera
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>