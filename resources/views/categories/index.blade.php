<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-gradient-to-br from-slate-800 to-slate-950 rounded-xl text-white shadow-md">
                    <i class="fa-solid fa-layer-group text-lg"></i>
                </div>
                <div>
                    <h2 class="font-black text-2xl text-slate-800 leading-tight tracking-tight">
                        {{ __('Actividades Generales (PP)') }}
                    </h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Ejes del Programa Presupuestal</p>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('categories.trashed') }}" class="group bg-white hover:bg-red-50 text-slate-600 hover:text-red-600 border border-slate-200 hover:border-red-200 font-bold py-2.5 px-4 rounded-xl flex items-center gap-2 transition-all text-xs shadow-sm">
                    <i class="fa-solid fa-trash-can text-slate-400 group-hover:text-red-500 transition-colors"></i>
                    Papelera
                </a>
                <a href="{{ route('categories.create') }}" class="bg-slate-900 hover:bg-indigo-600 text-white font-black text-xs uppercase tracking-wider py-3 px-5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-circle-plus"></i>
                    Nueva Actividad General
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ showDeleteModal: false, selectedCategoryId: null, selectedCategoryName: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
           
            {{-- MENSAJE DE ÉXITO CON FADE OUT --}}
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

            @if($categories->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-100 p-16 text-center shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 border border-slate-100 shadow-inner rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <i class="fa-regular fa-folder-open text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-800">No hay actividades generales</h3>
                    <p class="text-sm text-slate-400 font-medium max-w-sm mx-auto mt-1">Comienza organizando la estructura de tus Programas Presupuestales asignados.</p>
                    <div class="mt-6">
                        <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-wider hover:bg-indigo-600 transition-colors shadow">
                            <i class="fa-solid fa-plus"></i> Crear Estructura PP
                        </a>
                    </div>
                </div>
            @else
                {{-- TABLA ADMINISTRATIVA MATRIZ --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider">
                                <tr>
                                    <th scope="col" class="px-6 py-4 w-36">Código PP</th>
                                    <th scope="col" class="px-6 py-4">Denominación Institucional</th>
                                    <th scope="col" class="px-6 py-4 w-52 text-center">Tareas Operativas Asignadas</th>
                                    <th scope="col" class="px-6 py-4 text-center w-36">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($categories as $category)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        
                                        {{-- Código PP --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md font-mono font-black bg-slate-900 text-white shadow-inner text-xs">
                                                {{ $category->pp_code }}
                                            </span>
                                        </td>
                                        
                                        {{-- Denominación --}}
                                        <td class="px-6 py-4">
                                            <div class="text-slate-900 font-bold text-sm leading-snug break-words max-w-xs md:max-w-md xl:max-w-lg">
                                                {{ $category->name }}
                                            </div>
                                            @if($category->description)
                                                <p class="text-[11px] text-slate-400 font-medium mt-1 break-words max-w-xs md:max-w-md xl:max-w-lg">
                                                    {{ Str::limit($category->description, 100) }}
                                                </p>
                                            @endif
                                        </td>
                                        
                                        {{-- Conteo de actividades operativas --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center justify-center px-3 py-1 bg-indigo-50 border border-indigo-100 text-indigo-700 font-black text-xs rounded-full shadow-inner">
                                                <i class="fa-solid fa-layer-group text-[10px] mr-1.5 opacity-60"></i>
                                                {{ $category->events_count ?? $category->events->count() }} Tareas
                                            </span>
                                        </td>
                                        
                                        {{-- Botonera de Acciones --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center items-center gap-2">
                                                <a href="{{ route('categories.show', $category) }}" class="p-2 bg-slate-50 text-slate-600 rounded-xl hover:bg-slate-100 border border-slate-200 transition-colors" title="Ver Detalles"><i class="fa-solid fa-eye text-xs"></i></a>
                                                <a href="{{ route('categories.edit', $category) }}" class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-100 border border-indigo-100/50 transition-colors" title="Editar"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                                                <button @click="selectedCategoryId = {{ $category->id }}; selectedCategoryName = '{{ addslashes($category->name) }}'; showDeleteModal = true" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 border border-red-100/50 transition-colors" title="Mover a Papelera"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(method_exists($categories, 'links'))
                <div class="p-2">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

        {{-- CONFIRMACIÓN DE ELIMINACIÓN MODAL --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm" role="dialog" aria-modal="true">
            <div class="bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-100 max-w-md w-full p-6 sm:p-8 space-y-6" @click.away="showDeleteModal = false" x-transition>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 border border-red-100 text-red-600 flex items-center justify-center shrink-0 shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation text-lg animate-bounce"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-lg font-black text-slate-900">¿Mover actividad a la papelera?</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed" x-text="'Se archivará la Actividad General «' + selectedCategoryName + '». Toda la configuración y las metas físicas derivadas dejarán de estar operativas temporalmente.'"></p>
                    </div>
                </div>

<div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button @click="showDeleteModal = false" type="button" class="px-4 py-2.5 rounded-xl text-slate-500 hover:text-slate-800 font-bold text-xs uppercase tracking-wider transition-colors">
                        Cancelar
                    </button>
                    <form method="POST" :action="'{{ url('categories') }}/' + selectedCategoryId">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl transition-all shadow-md">
                            Confirmar Archivo
                        </button>
                    </form>
                </div>
            </div>
        </div>
        {{-- FIN DEL MODAL --}}

    </div>
    {{-- FIN DEL CONTENEDOR X-DATA --}}
</x-app-layout>


