<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-gradient-to-br from-slate-800 to-slate-950 rounded-xl text-white shadow-md">
                <i class="fa-solid fa-calendar-plus text-lg"></i>
            </div>
            <div>
                <h2 class="font-black text-2xl text-slate-800 tracking-tight leading-none">
                    Programar Nueva Actividad
                </h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Planificación y Gestión Corporativa</p>
            </div>
            <a href="{{ route('workshops.index') }}" class="ml-auto text-slate-400 hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-arrow-left-long text-xl"></i>
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-0">
        <form action="{{ route('workshops.store') }}" method="POST" enctype="multipart/form-data"
              class="bg-white border border-slate-100 shadow-2xl rounded-2xl overflow-hidden space-y-0"
              x-data="{ 
                  selectedType: '{{ old('type', 'capacitacion') }}',
                  attachedImages: [],

                  addImages(e) {
                      const selectedFiles = Array.from(e.target.files);
                      selectedFiles.forEach(file => {
                          const isDuplicate = this.attachedImages.some(f => f.name === file.name && f.size === file.size);
                          if (!isDuplicate) {
                              this.attachedImages.push(file);
                          }
                      });
                      this.syncImagesInput();
                  },
                  removeImage(index) {
                      this.attachedImages.splice(index, 1);
                      this.syncImagesInput();
                  },
                  syncImagesInput() {
                      const dt = new DataTransfer();
                      this.attachedImages.forEach(file => dt.items.add(file));
                      this.$refs.imagesInputRaw.files = dt.files;
                      this.$refs.imagesInputRaw.value = '';
                  }
              }">
            @csrf
           
            <div class="p-6 sm:p-8 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2 text-slate-500">
                    <i class="fa-solid fa-file-signature text-sm"></i>
                    <span class="text-xs font-black uppercase tracking-wider">Ficha de Planificación Anual</span>
                </div>
                <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-0.5 rounded-md uppercase tracking-wider">POI 2026</span>
            </div>

            @if ($errors->any())
                <div class="mx-6 sm:mx-10 mt-6 p-4 bg-red-50 border border-red-200 text-red-700 text-xs font-bold rounded-xl space-y-1 shadow-sm">
                    <p class="font-black uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-triangle-exclamation animate-pulse"></i> No se pudo guardar el registro
                    </p>
                    <ul class="list-disc pl-4 space-y-0.5 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="p-6 sm:p-10 space-y-6">
                <div class="space-y-3">
                    <label class="block text-slate-700 text-xs font-black uppercase tracking-wider">Clasificación de la Tarea</label>
                    <input type="hidden" name="type" :value="selectedType">
                   
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div @click="selectedType = 'capacitacion'"
                             :class="selectedType === 'capacitacion' ? 'border-red-500 bg-red-50/20 ring-2 ring-red-500/10' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50'"
                             class="border-2 p-5 flex items-start gap-4 cursor-pointer transition-all duration-200 rounded-2xl relative shadow-sm group">
                            <div :class="selectedType === 'capacitacion' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-slate-400 border border-slate-200'"
                                 class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors">
                                <i class="fa-solid fa-user-graduate text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-black text-slate-900 text-sm leading-tight">Taller / Capacitación</h4>
                                <p class="text-slate-500 text-[11px] mt-1 font-medium leading-relaxed">Requiere control horario completo, publicación de requisitos y bases.</p>
                            </div>
                            <div class="absolute top-4 right-4 text-red-500 text-xs" x-show="selectedType === 'capacitacion'"><i class="fa-solid fa-circle-check text-base"></i></div>
                        </div>

                        <div @click="selectedType = 'coordinacion'"
                             :class="selectedType === 'coordinacion' ? 'border-indigo-600 bg-indigo-50/40 ring-2 ring-indigo-500/10' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50'"
                             class="border-2 p-5 flex items-start gap-4 cursor-pointer transition-all duration-200 rounded-2xl relative shadow-sm group">
                            <div :class="selectedType === 'coordinacion' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-400 border border-slate-200'"
                                 class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors">
                                <i class="fa-solid fa-handshake text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-black text-slate-900 text-sm leading-tight">Reunión de Coordinación</h4>
                                <p class="text-slate-500 text-[11px] mt-1 font-medium leading-relaxed">Registro ágil de mesas de trabajo grupales. Solo requiere fecha base.</p>
                            </div>
                            <div class="absolute top-4 right-4 text-indigo-600 text-base" x-show="selectedType === 'coordinacion'"><i class="fa-solid fa-circle-check"></i></div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-slate-700 tracking-tight">Título Descriptivo de la Actividad</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner placeholder:font-medium placeholder:text-slate-400 text-sm" placeholder="Ej. Taller de Capacitación en Seguridad Laboral Regional Puno">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-slate-700 tracking-tight">
                        <span x-text="selectedType === 'coordinacion' ? 'Fecha Programada para la Reunión' : 'Fecha y Hora Exacta de Ejecución'"></span>
                    </label>
                    <input :type="selectedType === 'coordinacion' ? 'date' : 'datetime-local'"
                           name="scheduled_at" value="{{ old('scheduled_at') }}" required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner text-sm">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-slate-700 tracking-tight">Descripción Detallada y Objetivos</label>
                    <textarea name="description" required rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner placeholder:text-slate-400 placeholder:text-xs text-sm" placeholder="Detalle los puntos de agenda, ponentes implicados y alcances institucionales previstos...">{{ old('description') }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-slate-700 tracking-tight">Documento Base / Afiche Informativo <span class="text-slate-400 font-medium">(Opcional)</span></label>
                    <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 p-3 rounded-xl shadow-inner relative group">
                        <div class="p-2 bg-white border border-slate-200 text-slate-400 group-hover:text-indigo-600 transition-colors shadow-sm rounded-lg"><i class="fa-solid fa-file-pdf text-sm"></i></div>
                        <input type="file" name="document" accept="application/pdf, image/*" class="w-full text-xs font-mono text-slate-600 cursor-pointer focus:outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-indigo-600 file:transition-colors file:cursor-pointer">
                    </div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider ml-1">Soporta: PDFs explicativos, Oficios de Invitación o Imágenes base (Máx. 10MB)</p>
                </div>

                <div class="space-y-2" x-show="selectedType === 'capacitacion'" x-transition:enter="transition ease-out duration-300 transform opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <label class="block text-sm font-black text-slate-700 tracking-tight">Bases, Requisitos o Términos de Referencia</label>
                    <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 p-3 rounded-xl shadow-inner relative group">
                        <div class="p-2 bg-white border border-slate-200 text-red-400 shadow-sm rounded-lg"><i class="fa-solid fa-folder-open text-sm"></i></div>
                        <input type="file" name="requirements" accept="application/pdf" class="w-full text-xs font-mono text-slate-600 cursor-pointer focus:outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-indigo-600 file:transition-colors file:cursor-pointer">
                    </div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider ml-1">Exclusivo para postulantes externos (Solo formato PDF - Máx. 10MB)</p>
                </div>

                <div class="h-px bg-slate-100 my-2"></div>

                {{-- 🎯 NUEVO: SECCIÓN DE AGREGAR EVIDENCIAS FOTOGRÁFICAS EN LA CREACIÓN --}}
                <div class="space-y-3">
                    <label class="block text-sm font-black text-slate-700 tracking-tight">Evidencias Fotográficas de la Actividad <span class="text-slate-400 font-medium">(Opcional / Concluidos)</span></label>
                   
                    <div class="border-2 border-dashed border-slate-200 hover:border-emerald-500/40 rounded-xl p-6 text-center bg-slate-50/50 hover:bg-slate-50 transition-colors relative cursor-pointer group">
                        <input type="file" name="images[]" accept="image/*" multiple
                               x-ref="imagesInputRaw"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                               @change="addImages($event)">
                        <div class="space-y-2 relative z-10">
                            <div class="w-10 h-10 bg-white border border-slate-200 rounded-lg flex items-center justify-center mx-auto text-slate-400 group-hover:text-emerald-500 transition-colors shadow-sm"><i class="fa-solid fa-camera-retro text-base"></i></div>
                            <p class="text-xs font-black text-slate-600">Presione o arrastre capturas de evidencia fotográfica</p>
                            <p class="text-[10px] text-slate-400 font-medium">Las fotos se irán acumulando abajo para enviarse juntas a la base de datos.</p>
                        </div>
                    </div>

                    <template x-if="attachedImages.length > 0">
                        <div class="bg-slate-50 border border-slate-200/70 rounded-xl p-3 space-y-1.5 shadow-inner">
                            <template x-for="(file, index) in attachedImages" :key="index">
                                <div class="flex items-center justify-between bg-white border border-slate-100 rounded-xl p-2.5 shadow-sm group">
                                    <div class="flex items-center gap-2.5 min-w-0 flex-1 pr-4">
                                        <div class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-image text-emerald-500 text-xs"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-xs font-black text-slate-800 leading-none" x-text="file.name"></p>
                                            <p class="text-[9px] font-mono font-medium text-slate-400 mt-1" x-text="(file.size / (1024*1024)).toFixed(2) + ' MB'"></p>
                                        </div>
                                    </div>
                                    <button type="button" @click="removeImage(index)"
                                            class="w-8 h-8 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-all shrink-0 border border-transparent hover:border-red-100">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

            </div>

            <div class="p-6 sm:p-8 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-4">
                <a href="{{ route('workshops.index') }}" class="px-5 py-3 rounded-xl text-slate-400 hover:text-slate-700 font-bold text-sm transition-colors uppercase tracking-wider">
                    Cancelar
                </a>
                <button type="submit" class="bg-slate-900 hover:bg-indigo-600 text-white font-black text-xs uppercase tracking-wider py-3.5 px-8 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> Registrar Actividad
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

