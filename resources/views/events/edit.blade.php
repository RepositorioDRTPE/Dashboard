<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                {{ __('Editar Actividad Operativa') }}
            </h2>
            <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                {{-- Barra decorativa --}}
                <div class="h-1.5 w-full bg-gradient-to-r from-blue-400 via-indigo-500 to-blue-600"></div>

                <div class="p-8">
                    <form action="{{ route('events.update', $event) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Categoría PP --}}
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Categoría PP <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id" id="category_id" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1.5 text-sm text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Código de Actividad --}}
                            <div>
                                <label for="event_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Código de Actividad <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="event_code" id="event_code" 
                                       value="{{ old('event_code', $event->event_code) }}" required
                                       placeholder="Ej. A01, B02..."
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all">
                                @error('event_code')
                                    <p class="mt-1.5 text-sm text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Código POI --}}
                            <div>
                                <label for="poi_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Código POI
                                </label>
                                <input type="text" name="poi_code" id="poi_code" 
                                       value="{{ old('poi_code', $event->poi_code) }}"
                                       placeholder="Ej. POI-001"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all">
                            </div>

                            {{-- Unidad de Medida --}}
                            <div>
                                <label for="unit_measure" class="block text-sm font-medium text-gray-700 mb-2">
                                    Unidad de Medida <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="unit_measure" id="unit_measure" 
                                       value="{{ old('unit_measure', $event->unit_measure) }}" required
                                       placeholder="Ej. personas, asistentes, alumnos"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all">
                                @error('unit_measure')
                                    <p class="mt-1.5 text-sm text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Meta Global --}}
                            <div class="md:col-span-2">
                                <label for="goal_people" class="block text-sm font-medium text-gray-700 mb-2">
                                    Meta Global <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="goal_people" id="goal_people" min="1" 
                                           value="{{ old('goal_people', $event->goal_people) }}" required
                                           placeholder="Número total de personas a alcanzar"
                                           class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-xl shadow-sm focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('goal_people')
                                    <p class="mt-1.5 text-sm text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Descripción --}}
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descripción <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" id="description" rows="4" required
                                          placeholder="Describe detalladamente la actividad operativa..."
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm resize-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 focus:outline-none transition-all">{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1.5 text-sm text-red-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('events.index') }}" 
                               class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 shadow-sm">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Actualizar Actividad
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>