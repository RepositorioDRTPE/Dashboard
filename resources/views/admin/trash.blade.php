<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-trash-can text-red-500 text-xl"></i>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Papelera de Reciclaje (Registros Eliminados)') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800">Reportes de Avance (Sub-Eventos) Eliminados</h3>
                </div>
                <div class="p-6">
                    @if($deletedSubEvents->isEmpty())
                        <p class="text-slate-500 text-sm italic">No hay reportes en la papelera.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase pb-3">Título / Fecha</th>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase pb-3">Asistentes</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase pb-3">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($deletedSubEvents as $sub)
                                    <tr>
                                        <td class="py-3 text-sm font-medium text-slate-900">{{ $sub->report_title }}</td>
                                        <td class="py-3 text-sm text-slate-500">{{ $sub->attendees_count }}</td>
                                        <td class="py-3 text-right">
                                            <form action="{{ route('trash.restore', ['tipo' => 'subevent', 'id' => $sub->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-emerald-600 hover:text-emerald-800 font-bold text-sm bg-emerald-50 px-3 py-1 rounded">Restaurar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="font-bold text-slate-800">Actividades Operativas Eliminadas</h3>
                </div>
                <div class="p-6">
                    @if($deletedEvents->isEmpty())
                        <p class="text-slate-500 text-sm italic">No hay actividades operativas en la papelera.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="divide-y divide-gray-100">
                                @foreach($deletedEvents as $evento)
                                    <tr>
                                        <td class="py-3 text-sm font-medium text-slate-900">{{ $evento->event_code }} - {{ Str::limit($evento->description, 50) }}</td>
                                        <td class="py-3 text-right">
                                            <form action="{{ route('trash.restore', ['tipo' => 'event', 'id' => $evento->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-emerald-600 hover:text-emerald-800 font-bold text-sm bg-emerald-50 px-3 py-1 rounded">Restaurar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
