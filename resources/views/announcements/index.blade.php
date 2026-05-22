<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-slate-800 tracking-tight flex items-center gap-3">
                <div class="p-2 bg-amber-100 rounded-xl text-amber-600"><i class="fa-solid fa-bullhorn"></i></div>
                Gestión de Comunicados Oficiales
            </h2>
            <a href="{{ route('announcements.create') }}" class="bg-slate-900 hover:bg-amber-600 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition-all shadow flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Nuevo Comunicado
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto space-y-6">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl flex items-center gap-2 font-bold text-sm">
                <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-md overflow-hidden">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Vigencia (Inicio / Fin)</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4">Título</th>
                        <th class="px-6 py-4">Tipo</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($announcements as $item)
                        @php
                            $today = now()->startOfDay();
                            $start = $item->published_at->startOfDay();
                            $end = $item->expired_at->startOfDay();
                            $isActive = $today->between($start, $end);
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-slate-600">
                                {{ $item->published_at->format('d/m/Y') }} - {{ $item->expired_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider 
                                    {{ $isActive ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                    {{ $isActive ? 'Visible' : 'Expirado/Programado' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $item->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold uppercase text-xs text-slate-500">
                                <i class="{{ $item->file_type === 'pdf' ? 'fa-solid fa-file-pdf text-red-500' : 'fa-solid fa-image text-blue-500' }} mr-1"></i>
                                {{ $item->file_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="p-2 bg-slate-50 text-slate-600 rounded-lg hover:bg-slate-100 border border-slate-200"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('announcements.edit', $item->id) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 border border-amber-200"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="{{ route('announcements.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Eliminar comunicado?')">
                                        @csrf @method('delete')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 border border-red-200"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-12 text-slate-400 font-medium">No hay comunicados registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $announcements->links() }}</div>
    </div>
</x-app-layout>

