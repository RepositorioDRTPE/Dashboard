<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('Registrar Nuevo Reporte') }}
            </h2>
            <a href="{{ route('subevents.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="reportForm()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Contenedor principal con sombra suave --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-sky-100">
                {{-- Barra superior azul cielo --}}
                <div class="h-2 w-full bg-gradient-to-r from-sky-400 to-blue-500"></div>

                <div class="p-8">
                    <form @submit.prevent="openConfirmationModal()">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Actividad --}}
                            <div>
                                <label for="event_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Actividad Operativa <span class="text-red-500">*</span>
                                </label>
                                <select name="event_id" id="event_id" x-model="form.event_id" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white shadow-sm 
                                               focus:border-sky-500 focus:ring-2 focus:ring-sky-200 focus:outline-none
                                               transition-all duration-200">
                                    <option value="" disabled>Selecciona una actividad...</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" data-code="{{ $event->event_code }}" data-name="{{ $event->name }}"
                                            {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                            {{ $event->event_code }} - {{ $event->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('event_id')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Fecha --}}
                            <div>
                                <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Fecha del Evento <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="event_date" id="event_date" x-model="form.event_date" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                                              focus:border-sky-500 focus:ring-2 focus:ring-sky-200 focus:outline-none
                                              transition-all duration-200">
                                @error('event_date')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Título (ocupa 2 cols) --}}
                            <div class="md:col-span-2">
                                <label for="report_title" class="block text-sm font-medium text-gray-700 mb-1">
                                    Título del Reporte <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="report_title" id="report_title" x-model="form.report_title" required
                                       placeholder="Ej: Visita al colegio San Juan"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                                              focus:border-sky-500 focus:ring-2 focus:ring-sky-200 focus:outline-none
                                              transition-all duration-200">
                                @error('report_title')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Asistentes --}}
                            <div>
                                <label for="attendees_count" class="block text-sm font-medium text-gray-700 mb-1">
                                    Personas Alcanzadas <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="attendees_count" id="attendees_count" x-model="form.attendees_count" required min="1"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                                              focus:border-sky-500 focus:ring-2 focus:ring-sky-200 focus:outline-none
                                              transition-all duration-200">
                                @error('attendees_count')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- YouTube URL --}}
                            <div>
                                <label for="youtube_url" class="block text-sm font-medium text-gray-700 mb-1">
                                    Enlace de Video (YouTube)
                                </label>
                                <input type="url" name="youtube_url" id="youtube_url" x-model="form.youtube_url"
                                       placeholder="https://youtube.com/watch?v=..."
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                                              focus:border-sky-500 focus:ring-2 focus:ring-sky-200 focus:outline-none
                                              transition-all duration-200">
                                @error('youtube_url')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Comentario --}}
                            <div class="md:col-span-2">
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">
                                    Comentario / Observaciones
                                </label>
                                <textarea name="comment" id="comment" x-model="form.comment" rows="3"
                                          placeholder="Detalles adicionales sobre el evento..."
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm 
                                                 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 focus:outline-none
                                                 transition-all duration-200 resize-none"></textarea>
                                @error('comment')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Fotos --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fotos de Evidencia</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50 hover:border-sky-400 transition-colors duration-200">
                                    <input type="file" name="photos[]" multiple accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                                    <p class="mt-2 text-xs text-gray-500">PNG, JPG, GIF hasta 5MB cada una</p>
                                </div>
                                @error('photos.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('subevents.index') }}" 
                               class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-200 shadow-sm">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-8 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-sky-300">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Guardar Reporte
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL DE CONFIRMACIÓN --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Overlay de fondo --}}
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     @click="showModal = false"></div>

                {{-- Contenido del modal --}}
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    {{-- Cabecera azul cielo --}}
                    <div class="bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-white rounded-full p-2">
                                <svg class="h-6 w-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="ml-3 text-xl font-semibold text-white" id="modal-title">
                                Confirmar Reporte
                            </h3>
                        </div>
                    </div>

                    {{-- Cuerpo del modal --}}
                    <div class="px-6 py-5">
                        <p class="text-gray-700 text-base leading-relaxed">
                            Se agregará un reporte de avance para la actividad 
                            <span class="font-semibold text-sky-700" x-text="selectedEventName"></span> 
                            con un total de 
                            <span class="font-bold text-sky-600 text-lg" x-text="form.attendees_count || '0'"></span> 
                            personas alcanzadas.
                        </p>
                        
                        {{-- Detalles adicionales si existen --}}
                        <div class="mt-4 bg-sky-50 rounded-lg p-4 border border-sky-200">
                            <dl class="grid grid-cols-2 gap-2 text-sm">
                                <dt class="text-gray-600">Fecha:</dt>
                                <dd class="text-gray-900 font-medium" x-text="formatDate(form.event_date)"></dd>
                                <dt class="text-gray-600">Título:</dt>
                                <dd class="text-gray-900 font-medium truncate" x-text="form.report_title || '—'"></dd>
                                <dt class="text-gray-600">Video:</dt>
                                <dd class="text-gray-900 font-medium truncate" x-text="form.youtube_url ? 'Sí' : 'No'"></dd>
                            </dl>
                        </div>

                        <p class="mt-4 text-sm text-gray-500">
                            ¿Estás seguro de continuar con el registro?
                        </p>
                    </div>

                    {{-- Pie del modal --}}
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t">
                        <button type="button" @click="showModal = false"
                                class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-white transition-all duration-200 shadow-sm">
                            Cancelar
                        </button>
                        <button type="button" @click="submitForm()"
                                class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-sky-300">
                            Confirmar y Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Alpine.js --}}
    <script>
        function reportForm() {
            return {
                showModal: false,
                form: {
                    event_id: '{{ old('event_id') }}',
                    event_date: '{{ old('event_date') }}',
                    report_title: '{{ old('report_title') }}',
                    attendees_count: '{{ old('attendees_count') }}',
                    youtube_url: '{{ old('youtube_url') }}',
                    comment: '{{ old('comment') }}',
                },
                selectedEventName: '',
                init() {
                    // Inicializar nombre del evento seleccionado
                    this.updateSelectedEventName();
                    this.$watch('form.event_id', () => this.updateSelectedEventName());
                },
                updateSelectedEventName() {
                    const select = document.getElementById('event_id');
                    if (select && select.selectedIndex > 0) {
                        const option = select.options[select.selectedIndex];
                        const code = option.dataset.code || '';
                        const name = option.dataset.name || '';
                        this.selectedEventName = code + ' - ' + name;
                    } else {
                        this.selectedEventName = 'la actividad seleccionada';
                    }
                },
                formatDate(dateString) {
                    if (!dateString) return '—';
                    const date = new Date(dateString + 'T00:00:00');
                    return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
                },
                openConfirmationModal() {
                    // Validar campos requeridos
                    if (!this.form.event_id || !this.form.event_date || !this.form.report_title || !this.form.attendees_count) {
                        alert('Por favor completa todos los campos obligatorios.');
                        return;
                    }
                    this.updateSelectedEventName();
                    this.showModal = true;
                },
                submitForm() {
                    // Crear un formulario real y enviarlo
                    const realForm = document.createElement('form');
                    realForm.method = 'POST';
                    realForm.action = '{{ route('subevents.store') }}';
                    realForm.enctype = 'multipart/form-data';

                    // CSRF token
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    realForm.appendChild(csrf);

                    // Campos del formulario
                    for (let key in this.form) {
                        if (this.form.hasOwnProperty(key) && this.form[key] !== null) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            input.value = this.form[key];
                            realForm.appendChild(input);
                        }
                    }

                    // Manejar archivos (fotos)
                    const fileInput = document.getElementById('photos');
                    if (fileInput && fileInput.files.length > 0) {
                        const newFileInput = document.createElement('input');
                        newFileInput.type = 'file';
                        newFileInput.name = 'photos[]';
                        newFileInput.multiple = true;
                        newFileInput.style.display = 'none';
                        // No podemos clonar archivos fácilmente, así que transferimos el FileList
                        // Mejor: clonar el input original y agregarlo al nuevo form
                        const clonedFileInput = fileInput.cloneNode(true);
                        realForm.appendChild(clonedFileInput);
                    }

                    document.body.appendChild(realForm);
                    realForm.submit();
                }
            }
        }
    </script>
</x-app-layout>