

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-600 to-red-800 text-white flex items-center justify-center shadow-lg shadow-red-500/30">
                <i class="fa-solid fa-camera-retro text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    Registro de Impacto y Galería
                </h2>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-0.5">
                    Evidencias institucionales
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <form action="{{ route('photo-reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 relative z-10" x-data="photoUpload('{{ $defaultType ?? 'evento' }}')">
            @csrf

            <div class="bg-white/95 backdrop-blur-md shadow-xl border border-slate-100 rounded-3xl p-6 sm:p-10 relative overflow-hidden">
                
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-red-600 to-red-400"></div>
                
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-slate-50 rounded-full blur-3xl z-0 pointer-events-none"></div>

                <div class="relative z-10">
                    <p class="text-sm text-slate-500 font-medium mb-8">
                        Publique evidencias fotográficas de actividades que no requieren métricas cuantitativas. Este material se utilizará para el portal de transparencia y comunicaciones de la Dirección.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        
                        <div class="space-y-3 col-span-1 md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1 uppercase tracking-wider">Clasificación del Reporte</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                
                                <label class="relative flex items-center gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all bg-white shadow-sm hover:shadow-md"
                                       :class="reportType === 'evento' ? 'border-red-600 ring-4 ring-red-600/10' : 'border-slate-200 hover:border-slate-300'">
                                    <input type="radio" name="tipo_reporte" value="evento" x-model="reportType" class="sr-only">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl transition-all duration-300"
                                         :class="reportType === 'evento' ? 'bg-red-600 text-white shadow-lg shadow-red-500/30 scale-110' : 'bg-slate-100 text-slate-400'">
                                        <i class="fa-solid fa-calendar-star"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-black text-base" :class="reportType === 'evento' ? 'text-red-900' : 'text-slate-700'">Evento Institucional</span>
                                        <span class="text-xs text-slate-500 font-medium mt-0.5">Festividades, aniversarios, días conmemorativos.</span>
                                    </div>
                                    <div class="absolute top-4 right-4 text-red-600 transition-opacity" :class="reportType === 'evento' ? 'opacity-100' : 'opacity-0'">
                                        <i class="fa-solid fa-circle-check text-xl"></i>
                                    </div>
                                </label>

                                <label class="relative flex items-center gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all bg-white shadow-sm hover:shadow-md"
                                       :class="reportType === 'difusion' ? 'border-blue-600 ring-4 ring-blue-600/10' : 'border-slate-200 hover:border-slate-300'">
                                    <input type="radio" name="tipo_reporte" value="difusion" x-model="reportType" class="sr-only">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl transition-all duration-300"
                                         :class="reportType === 'difusion' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30 scale-110' : 'bg-slate-100 text-slate-400'">
                                        <i class="fa-solid fa-radio"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-black text-base" :class="reportType === 'difusion' ? 'text-blue-900' : 'text-slate-700'">Actividad de Difusión</span>
                                        <span class="text-xs text-slate-500 font-medium mt-0.5">Entrevistas radiales, notas de prensa, tv.</span>
                                    </div>
                                    <div class="absolute top-4 right-4 text-blue-600 transition-opacity" :class="reportType === 'difusion' ? 'opacity-100' : 'opacity-0'">
                                        <i class="fa-solid fa-circle-check text-xl"></i>
                                    </div>
                                </label>

                            </div>
                        </div>

                        <div class="space-y-2 col-span-1 md:col-span-2 mt-4">
                            <label for="titulo" class="block text-sm font-bold text-slate-700 ml-1">Titular Informativo</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-pen-nib text-slate-400 group-focus-within:text-red-600 transition-colors"></i>
                                </div>
                                <input type="text" id="titulo" name="titulo" required
                                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition-all shadow-inner placeholder-slate-400"
                                       placeholder="Ej. Participación institucional por el Día de la Madre">
                            </div>
                        </div>

                        <div class="space-y-2 col-span-1 md:col-span-2">
                            <label for="descripcion" class="block text-sm font-bold text-slate-700 ml-1">Reseña o Descripción Detallada</label>
                            <textarea id="descripcion" name="descripcion" rows="4" required
                                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition-all shadow-inner placeholder-slate-400"
                                      placeholder="Describa el contexto, quiénes participaron y los puntos más resaltantes del evento..."></textarea>
                        </div>

                        <div class="space-y-2 col-span-1 md:col-span-2 mt-2">
                            <label class="block text-sm font-bold text-slate-700 ml-1">Evidencias Fotográficas</label>
                            
                            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-10 text-center bg-slate-50 hover:bg-slate-100 hover:border-slate-400 transition-all relative cursor-pointer group">
                                <input type="file" name="imagenes[]" id="file-input" multiple accept="image/jpeg, image/png, image/webp" 
                                       @change="previewImages" 
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                
                                <div class="space-y-3 pointer-events-none">
                                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-slate-200 flex items-center justify-center text-slate-400 mx-auto group-hover:text-red-500 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fa-solid fa-cloud-arrow-up text-3xl"></i>
                                    </div>
                                    <p class="text-base font-black text-slate-700">Haga clic o arrastre sus imágenes aquí</p>
                                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Formatos soportados: JPG, PNG, WEBP</p>
                                </div>
                            </div>

                            <template x-if="previews.length > 0">
                                <div class="mt-4">
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-1">Imágenes Seleccionadas (<span x-text="previews.length"></span>)</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-slate-100/50 rounded-2xl border border-slate-200/60">
                                        <template x-for="(src, index) in previews" :key="index">
                                            <div class="relative aspect-square rounded-xl overflow-hidden shadow-sm bg-white border border-slate-200 group/img">
                                                <img :src="src" class="w-full h-full object-cover transition-transform group-hover/img:scale-110">
                                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                                    <button type="button" @click.stop="removeImage(index)" class="bg-red-600 text-white w-10 h-10 rounded-full shadow-lg hover:bg-red-700 hover:scale-110 transition-all flex items-center justify-center">
                                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-end pt-8 border-t border-slate-100">
                        <button type="submit" class="bg-slate-900 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-wider py-4 px-8 rounded-xl transition-all duration-300 shadow-[0_4px_15px_-3px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_20px_-3px_rgba(220,38,38,0.5)] hover:-translate-y-0.5 flex items-center gap-3 group/btn">
                            <i class="fa-solid fa-paper-plane group-hover/btn:-translate-y-1 group-hover/btn:translate-x-1 transition-transform"></i>
                            Publicar Reporte
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('photoUpload', (initialType) => ({
                reportType: initialType,
                previews: [],
                files: [], // Array real para manipular si es necesario
                
                previewImages(event) {
                    const inputFiles = event.target.files;
                    if (inputFiles.length === 0) return;

                    // Limpiamos previsualizaciones anteriores para que no se dupliquen visualmente al volver a seleccionar
                    this.previews = []; 
                    
                    Array.from(inputFiles).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            // En JS nativo es e.target.result (corrige el error anterior de getResult)
                            this.previews.push(e.target.result); 
                        };
                        reader.readAsDataURL(file);
                    });
                },
                
                removeImage(index) {
                    this.previews.splice(index, 1);
                    
                    // IMPORTANTE: Limpiar el input real es complicado en JS por seguridad del navegador. 
                    // Si el usuario borra todas las fotos de la vista previa, reseteamos el input principal
                    if(this.previews.length === 0) {
                        document.getElementById('file-input').value = "";
                    }
                }
            }));
        });
    </script>
</x-app-layout>

