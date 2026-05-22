<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-slate-800 tracking-tight flex items-center gap-3">
                <div class="p-2 bg-amber-100 rounded-xl text-amber-600"><i class="fa-solid fa-file-pdf"></i></div>
                Boletines Informativos publicados
            </h2>
            <a href="{{ route('bulletins.create') }}" class="bg-slate-900 hover:bg-amber-600 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition-all shadow flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Nuevo Boletín
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto space-y-6">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-md overflow-hidden">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4">Publicado</th>
                        <th class="px-6 py-4">Título</th>
                        <th class="px-6 py-4">Descripción</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bulletins as $bulletin)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-500">{{ $bulletin->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $bulletin->title }}</td>
                            <td class="px-6 py-4 text-slate-600 max-w-xs truncate">{{ $bulletin->description ?? 'Sin descripción' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ asset('storage/' . $bulletin->file_path) }}" target="_blank" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition border border-blue-200" title="Ver documento PDF">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                    <a href="{{ route('bulletins.edit', $bulletin->id) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition border border-amber-200" title="Editar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('bulletins.destroy', $bulletin->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar de forma permanente este boletín?')">
                                        @csrf @method('delete')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition border border-red-200" title="Eliminar">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-slate-400 font-medium">No se han registrado boletines informativos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $bulletins->links() }}</div>
    </div>
</x-app-layout>

