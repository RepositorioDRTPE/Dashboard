<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-gradient-to-br from-slate-800 to-slate-950 rounded-xl text-white shadow-md">
                <i class="fa-solid fa-sliders text-lg"></i>
            </div>
            <div>
                <h2 class="font-black text-2xl text-slate-800 tracking-tight leading-none">
                    Modificar Actividad Registrada
                </h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Actualización Documental e Historial de Evidencias</p>
            </div>
            <a href="{{ route('workshops.index') }}" class="ml-auto text-slate-400 hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-arrow-left-long text-xl"></i>
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-0">
        <form action="{{ route('workshops.update', $workshop->id) }}" method="POST" enctype="multipart/form-data" 
              class="bg-white border border-slate-100 shadow-2xl rounded-2xl overflow-hidden space-y-0">
            @csrf @method('PUT')
            
            <input type="hidden" name="type" value="{{ $workshop->type }}">

            <div class="p-6 sm:p-8 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2 text-slate-500">
                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                    <span class="text-xs font-black uppercase tracking-wider">Auditoría del Registro Operativo</span>
                </div>
                <span class="text-[10px] font-black {{ $workshop->type == 'capacitacion' ? 'text-red-600 bg-red-50 border-red-100' : 'text-indigo-600 bg-indigo-50 border-indigo-100' }} border px-2.5 py-0.5 rounded-md uppercase tracking-wider">
                    {{ $workshop->type == 'capacitacion' ? 'Taller / Cap.' : 'Coordinación' }}
                </span>
            </div>

            <div class="p-6 sm:p-10 space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-black text-slate-700 tracking-tight">Título Institucional de la Actividad</label>
                    <input type="text" name="title" value="{{ $workshop->title }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner text-sm">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-slate-700 tracking-tight">Fecha / Horario de Ejecución</label>
                    <input type="{{ $workshop->type === 'coordinacion' ? 'date' : 'datetime-local' }}" 
                           name="scheduled_at" 
                           value="{{ $workshop->type === 'coordinacion' ? $workshop->scheduled_at->format('Y-m-d') : $workshop->scheduled_at->format('Y-m-d\TH:i') }}" 
                           required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner text-sm">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-slate-700 tracking-tight">Descripción General de Actividades</label>
                    <textarea name="description" required rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner text-sm">{{ $workshop->description }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-slate-700 tracking-tight">Actualizar Documentación Explicativa</label>
                    <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 p-3 rounded-xl shadow-inner relative group">
                        <div class="p-2 bg-white border border-slate-200 text-slate-400 shadow-sm rounded-lg"><i class="fa-solid fa-file-pdf text-sm"></i></div>
                        <input type="file" name="document" accept="application/pdf, image/*" class="w-full text-xs font-mono text-slate-600 cursor-pointer focus:outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-indigo-600 file:transition-colors file:cursor-pointer">
                    </div>
                    @if($workshop->document_path)
                        <div class="mt-1.5 ml-1">
                            <a href="{{ asset('storage/' . $workshop->document_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-black text-indigo-600 hover:text-indigo-800 transition-colors">
                                <i class="fa-solid fa-circle-arrow-down text-sm"></i> Descargar / Examinar Documento Guardado
                            </a>
                        </div>
                    @endif
                </div>

                @if($workshop->type === 'capacitacion')
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-slate-700 tracking-tight">Actualizar Requisitos y Bases de Convocatoria</label>
                        <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 p-3 rounded-xl shadow-inner relative group">
                            <div class="p-2 bg-white border border-slate-200 text-red-400 shadow-sm rounded-lg"><i class="fa-solid fa-folder-open text-sm"></i></div>
                            <input type="file" name="requirements" accept="application/pdf" class="w-full text-xs font-mono text-slate-600 cursor-pointer focus:outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-indigo-600 file:transition-colors file:cursor-pointer">
                        </div>
                        @if($workshop->requirements_path)
                            <div class="mt-1.5 ml-1">
                                <a href="{{ asset('storage/' . $workshop->requirements_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-black text-red-600 hover:text-red-800 transition-colors">
                                    <i class="fa-solid fa-file-pdf text-sm"></i> Descargar / Examinar Bases Guardadas
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- BAÚL DE EVIDENCIAS FOTOGRÁFICAS (Automático por el Servidor) --}}
                @if($workshop->scheduled_at->isPast())
                    <div class="p-6 bg-slate-50 border border-slate-200 rounded-2xl space-y-4 shadow-sm">
                        <div class="flex items-center gap-2 text-emerald-700 font-black text-xs uppercase tracking-wider">
                            <i class="fa-solid fa-camera-retro text-base"></i>
                            <span>Galería de Evidencias de Cierre (Taller Ejecutado)</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">Cargue múltiples capturas fotográficas para evidenciar el cumplimiento del taller en la pantalla de cara al ciudadano.</p>
                        
                        <div class="flex items-center gap-3 bg-white border border-slate-200 p-3 rounded-xl shadow-sm relative group">
                            <div class="p-2 bg-slate-50 border border-slate-100 text-slate-400 shadow-sm rounded-lg"><i class="fa-solid fa-images text-sm"></i></div>
                            <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs font-mono text-slate-600 cursor-pointer focus:outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-indigo-600 file:transition-colors file:cursor-pointer">
                        </div>
                        
                        {{-- Grilla de fotos existentes en formato mosaico elegante --}}
                        @if($workshop->photos)
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                                @foreach($workshop->photos as $photo)
                                    <div class="relative w-full aspect-video border border-slate-200 bg-slate-200 rounded-xl overflow-hidden shadow-sm group">
                                        <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-slate-950/20 opacity-15"></div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="p-6 sm:p-8 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-4">
                <a href="{{ route('workshops.index') }}" class="px-5 py-3 rounded-xl text-slate-400 hover:text-slate-700 font-bold text-sm transition-colors uppercase tracking-wider">
                    Cancelar
                </a>
                <button type="submit" class="bg-slate-900 hover:bg-emerald-600 text-white font-black text-xs uppercase tracking-wider py-3.5 px-8 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk text-sm"></i> Guardar Cambios Consolidados
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
