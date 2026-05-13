<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                {{ __('Editar Reporte de Avance') }}
            </h2>
            <a href="{{ route('subevents.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Contenedor principal con x-data --}}
            <div x-data="editForm()" x-init="init()" 
                 @photo-order-changed.window="markDirty()"
                 class="bg-white rounded-2xl shadow-xl overflow-hidden">
                
                {{-- Barra de progreso decorativa --}}
                <div class="h-1.5 w-full bg-gradient-to-r from-sky-400 via-blue-500 to-sky-600"></div>

                <div class="p-8">
                    {{-- Formulario real (oculto) --}}
                    <form id="real-form" action="{{ route('subevents.update', $subevent) }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="event_id" :value="form.event_id">
                        <input type="hidden" name="event_date" :value="form.event_date">
                        <input type="hidden" name="report_title" :value="form.report_title">
                        <input type="hidden" name="attendees_count" :value="form.attendees_count">
                        <input type="hidden" name="youtube_url" :value="form.youtube_url">
                        <input type="hidden" name="comment" :value="form.comment">
                    </form>

                    {{-- Formulario visual --}}
                    <form @submit.prevent="openConfirmationModal()" id="visual-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Actividad Operativa --}}
                            <div>
                                <label for="event_id" class="block text-sm font-medium text-gray-700 mb-1">Actividad Operativa *</label>
                                <select x-model="form.event_id" id="event_id" required
                                        @change="markDirty()"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl bg-white shadow-sm 
                                               focus:border-sky-400 focus:ring-4 focus:ring-sky-100 focus:outline-none
                                               transition-all duration-200">
                                    <option value="" disabled>Selecciona una actividad...</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->event_code }} - {{ $event->name }}</option>
                                    @endforeach
                                </select>
                                @error('event_id')
                                    <p class="mt-1 text-sm text-red-500 flex items-center">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fecha del evento --}}
                            <div>
                                <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha del Evento *</label>
                                <input type="date" x-model="form.event_date" id="event_date" required
                                       @input="markDirty()"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm 
                                              focus:border-sky-400 focus:ring-4 focus:ring-sky-100 focus:outline-none
                                              transition-all duration-200">
                                @error('event_date')
                                    <p class="mt-1 text-sm text-red-500 flex items-center">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Título del reporte --}}
                            <div class="md:col-span-2">
                                <label for="report_title" class="block text-sm font-medium text-gray-700 mb-1">Título del Reporte *</label>
                                <input type="text" x-model="form.report_title" id="report_title" required
                                       @input="markDirty()"
                                       placeholder="Ej: Visita al colegio San Juan"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm 
                                              focus:border-sky-400 focus:ring-4 focus:ring-sky-100 focus:outline-none
                                              transition-all duration-200">
                                @error('report_title')
                                    <p class="mt-1 text-sm text-red-500 flex items-center">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Personas Alcanzadas --}}
                            <div>
                                <label for="attendees_count" class="block text-sm font-medium text-gray-700 mb-1">Personas Alcanzadas *</label>
                                <div class="relative">
                                    <input type="number" x-model="form.attendees_count" id="attendees_count" required min="1"
                                           @input="markDirty()"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm 
                                                  focus:border-sky-400 focus:ring-4 focus:ring-sky-100 focus:outline-none
                                                  transition-all duration-200">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-400">👥</span>
                                    </div>
                                </div>
                                @error('attendees_count')
                                    <p class="mt-1 text-sm text-red-500 flex items-center">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Enlace de YouTube --}}
                            <div>
                                <label for="youtube_url" class="block text-sm font-medium text-gray-700 mb-1">Enlace de Video (YouTube)</label>
                                <div class="relative">
                                    <input type="url" x-model="form.youtube_url" id="youtube_url"
                                           @input="markDirty()"
                                           placeholder="https://youtube.com/watch?v=..."
                                           class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-xl shadow-sm 
                                                  focus:border-sky-400 focus:ring-4 focus:ring-sky-100 focus:outline-none
                                                  transition-all duration-200">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                                        </svg>
                                    </div>
                                </div>
                                @error('youtube_url')
                                    <p class="mt-1 text-sm text-red-500 flex items-center">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Comentario --}}
                            <div class="md:col-span-2">
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Comentario / Observaciones</label>
                                <textarea x-model="form.comment" id="comment" rows="3"
                                          @input="markDirty()"
                                          placeholder="Detalles adicionales sobre el evento..."
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm resize-none
                                                 focus:border-sky-400 focus:ring-4 focus:ring-sky-100 focus:outline-none
                                                 transition-all duration-200"></textarea>
                                @error('comment')
                                    <p class="mt-1 text-sm text-red-500 flex items-center">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fotos actuales con gestión de prioridad --}}
                            <div class="md:col-span-2" x-data="photoManager()" x-init="initSortable()">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-medium text-gray-700">Fotos actuales (arrastra para cambiar prioridad)</p>
                                    <button type="button" 
                                            x-show="photoOrderChanged" 
                                            x-transition.duration.200
                                            @click="resetOrder()" 
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition-all duration-200 shadow-sm">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Restablecer orden original
                                    </button>
                                </div>
                                
                                <div id="photo-sortable" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    @foreach($subevent->photos as $index => $photo)
                                        @php 
                                            $priority = isset($subevent->photo_priority[$index]) ? $subevent->photo_priority[$index] : $index + 1;
                                        @endphp
                                        <div class="relative group rounded-lg overflow-hidden border-2 border-gray-200 cursor-move" 
                                             data-photo="{{ $photo }}" 
                                             data-priority="{{ $priority }}">
                                            <img src="{{ asset('storage/' . $photo) }}" class="w-full h-24 object-cover">
                                            <div class="absolute top-1 left-1 bg-sky-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-md">
                                                {{ $priority }}
                                            </div>
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 flex items-center justify-center">
                                                <span class="text-white opacity-0 group-hover:opacity-100 text-xs font-medium bg-black bg-opacity-50 px-2 py-1 rounded">Prioridad {{ $priority }}</span>
                                            </div>
                                            <div class="absolute bottom-1 right-1 bg-white bg-opacity-80 rounded-full p-1 shadow">
                                                <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Las fotos con menor número de prioridad aparecerán primero en el dashboard.</p>
                                
                                {{-- Inputs ocultos para enviar el orden --}}
                                <template x-for="(photo, idx) in orderedPhotos" :key="photo">
                                    <input type="hidden" name="photo_order[]" :value="photo">
                                </template>
                                <template x-for="(priority, idx) in orderedPriorities" :key="idx">
                                    <input type="hidden" name="photo_priority[]" :value="priority">
                                </template>
                            </div>

                            {{-- Subida de nuevas fotos --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Agregar más fotos</label>
                                <div x-data="{ files: [], dragOver: false }" 
                                     class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 transition-all duration-200 hover:border-sky-400"
                                     :class="{ 'border-sky-500 bg-sky-50': dragOver }"
                                     @dragover.prevent="dragOver = true"
                                     @dragleave.prevent="dragOver = false"
                                     @drop.prevent="dragOver = false; handleDrop($event)">
                                    
                                    <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                           @change="files = Array.from($event.target.files); $dispatch('photo-order-changed')">
                                    
                                    <div class="text-center pointer-events-none">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">
                                            <span class="font-medium text-sky-600">Haz clic para seleccionar</span> o arrastra y suelta
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF hasta 5MB</p>
                                    </div>

                                    <div x-show="files.length > 0" x-transition.duration.300ms class="mt-4 pointer-events-none">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Nuevas fotos:</p>
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="(file, index) in files" :key="index">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span x-text="file.name"></span>
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Las fotos nuevas se añadirán al final con la prioridad más baja.</p>
                                @error('photos.*')
                                    <p class="mt-1 text-sm text-red-500 flex items-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('subevents.index') }}" 
                               class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 shadow-sm">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-8 py-2.5 font-medium rounded-xl shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-sky-200"
                                    :class="{
                                        'bg-gradient-to-r from-sky-600 to-blue-600 text-white hover:shadow-xl': isDirty,
                                        'bg-gray-300 text-gray-500 cursor-not-allowed': !isDirty
                                    }"
                                    :disabled="!isDirty ? true : false">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Actualizar Reporte
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Modal de Confirmación --}}
                <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-sky-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            ¿Seguro que deseas actualizar los datos del reporte?
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                Se actualizará el reporte de avance para la actividad <span class="font-semibold text-sky-700" x-text="selectedEventName()"></span> 
                                                con un total de <span class="font-semibold text-sky-700" x-text="form.attendees_count"></span> personas alcanzadas.
                                            </p>
                                            <div class="mt-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                <dl class="space-y-2 text-sm">
                                                    <div class="flex justify-between">
                                                        <dt class="text-gray-500">Fecha:</dt>
                                                        <dd class="font-medium text-gray-900" x-text="formattedDate()"></dd>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <dt class="text-gray-500">Título:</dt>
                                                        <dd class="font-medium text-gray-900" x-text="form.report_title || '—'"></dd>
                                                    </div>
                                                    <div class="flex justify-between" x-show="form.youtube_url">
                                                        <dt class="text-gray-500">Video:</dt>
                                                        <dd class="font-medium text-sky-600 truncate max-w-[200px]" x-text="form.youtube_url"></dd>
                                                    </div>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" @click="submitForm()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-sky-600 to-blue-600 text-base font-medium text-white hover:from-sky-700 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-sky-200 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                                    Continuar
                                </button>
                                <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-sky-100 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        function editForm() {
            return {
                showModal: false,
                isDirty: false,
                originalForm: null,
                form: {
                    event_id: '{{ old('event_id', $subevent->event_id) }}',
                    event_date: '{{ old('event_date', $subevent->event_date->format('Y-m-d')) }}',
                    report_title: '{{ old('report_title', $subevent->report_title) }}',
                    attendees_count: '{{ old('attendees_count', $subevent->attendees_count) }}',
                    youtube_url: '{{ old('youtube_url', $subevent->youtube_url) }}',
                    comment: `{{ old('comment', addslashes($subevent->comment)) }}`,
                },
                events: @json($events->keyBy('id')),
                
                init() {
                    this.originalForm = { ...this.form };
                    window.photoOrderChanged = false;
                },
                
                markDirty() {
                    const current = this.form;
                    const original = this.originalForm;
                    
                    const fieldsChanged = 
                        current.event_id != original.event_id ||
                        current.event_date != original.event_date ||
                        current.report_title != original.report_title ||
                        current.attendees_count != original.attendees_count ||
                        current.youtube_url != original.youtube_url ||
                        current.comment != original.comment;
                    
                    const fileInput = document.getElementById('photos');
                    const hasNewFiles = fileInput && fileInput.files.length > 0;
                    
                    this.isDirty = fieldsChanged || hasNewFiles || window.photoOrderChanged;
                },
                
                openConfirmationModal() {
                    if (!this.form.event_id || !this.form.event_date || !this.form.report_title || !this.form.attendees_count) {
                        alert('Por favor completa todos los campos obligatorios.');
                        return;
                    }
                    
                    if (!this.isDirty) {
                        alert('No hay cambios para guardar.');
                        return;
                    }
                    
                    this.showModal = true;
                },
                
                selectedEventName() {
                    if (this.form.event_id && this.events[this.form.event_id]) {
                        return this.events[this.form.event_id].event_code + ' - ' + this.events[this.form.event_id].name;
                    }
                    return 'Selecciona una actividad';
                },
                
                formattedDate() {
                    if (!this.form.event_date) return '';
                    const date = new Date(this.form.event_date + 'T00:00:00');
                    return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
                },
                
                submitForm() {
                    const realForm = document.getElementById('real-form');
                    
                    // Actualizar campos básicos
                    realForm.querySelector('input[name="event_id"]').value = this.form.event_id;
                    realForm.querySelector('input[name="event_date"]').value = this.form.event_date;
                    realForm.querySelector('input[name="report_title"]').value = this.form.report_title;
                    realForm.querySelector('input[name="attendees_count"]').value = this.form.attendees_count;
                    realForm.querySelector('input[name="youtube_url"]').value = this.form.youtube_url;
                    realForm.querySelector('input[name="comment"]').value = this.form.comment;
                    
                    // Asegurar método PUT
                    let methodInput = realForm.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PUT';
                        realForm.appendChild(methodInput);
                    }
                    
                    // ⭐ CORRECCIÓN: Copiar los inputs de photo_order y photo_priority al formulario real
                    const visualForm = document.getElementById('visual-form');
                    const orderInputs = visualForm.querySelectorAll('input[name="photo_order[]"]');
                    const priorityInputs = visualForm.querySelectorAll('input[name="photo_priority[]"]');
                    
                    // Eliminar inputs previos en el formulario real para evitar duplicados
                    realForm.querySelectorAll('input[name="photo_order[]"]').forEach(el => el.remove());
                    realForm.querySelectorAll('input[name="photo_priority[]"]').forEach(el => el.remove());
                    
                    // Clonar y añadir los nuevos
                    orderInputs.forEach(input => {
                        const clone = input.cloneNode(true);
                        realForm.appendChild(clone);
                    });
                    priorityInputs.forEach(input => {
                        const clone = input.cloneNode(true);
                        realForm.appendChild(clone);
                    });
                    
                    // Copiar archivos nuevos
                    const fileInput = document.getElementById('photos');
                    if (fileInput.files.length > 0) {
                        const clone = fileInput.cloneNode(true);
                        clone.style.display = 'block';
                        clone.classList.remove('absolute', 'inset-0', 'opacity-0', 'z-10');
                        clone.removeAttribute('x-data');
                        clone.removeAttribute('@change');
                        realForm.appendChild(clone);
                    }
                    
                    realForm.submit();
                }
            }
        }

        function photoManager() {
            return {
                orderedPhotos: @json($subevent->photos ?? []),
                orderedPriorities: @json(
                    isset($subevent->photo_priority) 
                        ? $subevent->photo_priority 
                        : array_map(function($i) { return $i + 1; }, array_keys($subevent->photos ?? []))
                ),
                originalPhotos: @json($subevent->photos ?? []),
                originalPriorities: @json(
                    isset($subevent->photo_priority) 
                        ? $subevent->photo_priority 
                        : array_map(function($i) { return $i + 1; }, array_keys($subevent->photos ?? []))
                ),
                photoOrderChanged: false,
                sortableInstance: null,
                
                initSortable() {
                    const container = document.getElementById('photo-sortable');
                    if (!container) return;
                    
                    this.sortableInstance = new Sortable(container, {
                        animation: 150,
                        ghostClass: 'bg-sky-100',
                        dragClass: 'opacity-50',
                        onEnd: () => {
                            this.updateOrder();
                            this.checkOrderChange();
                            window.photoOrderChanged = this.photoOrderChanged;
                            window.dispatchEvent(new CustomEvent('photo-order-changed'));
                        }
                    });
                },
                
                updateOrder() {
                    const items = document.querySelectorAll('#photo-sortable > div');
                    const newPhotos = [];
                    const newPriorities = [];
                    
                    items.forEach((item, index) => {
                        const photo = item.dataset.photo;
                        newPhotos.push(photo);
                        newPriorities.push(index + 1);
                        
                        const badge = item.querySelector('.absolute.top-1.left-1');
                        if (badge) badge.textContent = index + 1;
                    });
                    
                    this.orderedPhotos = newPhotos;
                    this.orderedPriorities = newPriorities;
                },
                
                checkOrderChange() {
                    const photosChanged = JSON.stringify(this.orderedPhotos) !== JSON.stringify(this.originalPhotos);
                    const prioritiesChanged = JSON.stringify(this.orderedPriorities) !== JSON.stringify(this.originalPriorities);
                    this.photoOrderChanged = photosChanged || prioritiesChanged;
                },
                
                resetOrder() {
                    const container = document.getElementById('photo-sortable');
                    const items = Array.from(container.children);
                    
                    items.sort((a, b) => {
                        const aPriority = parseInt(a.dataset.priority) || 999;
                        const bPriority = parseInt(b.dataset.priority) || 999;
                        return aPriority - bPriority;
                    });
                    
                    items.forEach(item => container.appendChild(item));
                    this.updateOrder();
                    
                    this.orderedPhotos = [...this.originalPhotos];
                    this.orderedPriorities = [...this.originalPriorities];
                    this.photoOrderChanged = false;
                    window.photoOrderChanged = false;
                    window.dispatchEvent(new CustomEvent('photo-order-changed'));
                }
            }
        }
        
        window.photoOrderChanged = false;
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .sortable-ghost {
            opacity: 0.4;
            background: #e0f2fe;
            border: 2px dashed #0284c7;
        }
    </style>
</x-app-layout>