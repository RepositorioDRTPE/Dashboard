<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-xl text-white shadow-md shadow-indigo-200">
                <i class="fa-solid fa-circle-plus text-lg"></i>
            </div>
            <div>
                <h2 class="font-black text-2xl text-slate-800 leading-tight tracking-tight">
                    {{ __('Crear Nueva Actividad Operativa') }}
                </h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Formulación del Plan Operativo Institucional</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('events.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-100 shadow-xl overflow-hidden">
                @csrf
                
                <div class="p-6 sm:p-8 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-500">
                        <i class="fa-solid fa-file-invoice text-sm"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">Datos de Planificación (A01)</span>
                    </div>
                    <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md uppercase tracking-wider">Año Fiscal 2026</span>
                </div>

                <div class="p-6 sm:p-10 space-y-6">

                    <div class="space-y-2">
                        <label class="block text-slate-700 text-sm font-black tracking-tight">Vincular a Actividad General (PP)</label>
                        <div class="relative">
                            <select name="category_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner appearance-none cursor-pointer">
                                <option value="">-- Seleccione una Actividad General --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        [{{ $category->pp_code }}] · {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400"><i class="fa-solid fa-chevron-down text-xs"></i></div>
                        </div>
                        @error('category_id') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="block text-slate-700 text-sm font-black tracking-tight">Código Actividad <span class="text-slate-400 font-medium">(Ej. A01)</span></label>
                            <input type="text" name="event_code" value="{{ old('event_code') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-800 uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner placeholder:font-normal" placeholder="A01">
                            @error('event_code') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-slate-700 text-sm font-black tracking-tight">Código POI <span class="text-slate-400 font-medium">(Opcional)</span></label>
                            <input type="text" name="poi_code" value="{{ old('poi_code') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner" placeholder="Cód. Referencial">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-slate-700 text-sm font-black tracking-tight">Descripción de la Actividad Operativa</label>
                        <textarea name="description" required rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner placeholder:text-sm placeholder:font-medium" placeholder="Describa de manera detallada los objetivos operativos de la actividad..."></textarea>
                    </div>

                    <div class="space-y-3" x-data="{ source: '{{ old('funding_source', 'gobierno_regional') }}' }">
                        <label class="block text-slate-700 text-sm font-black tracking-tight">Fuente de Financiamiento Operativo</label>
                        <input type="hidden" name="funding_source" :value="source">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <div @click="source = 'gobierno_regional'" 
                                 :class="source === 'gobierno_regional' ? 'border-indigo-600 bg-indigo-50/40 ring-2 ring-indigo-500/10' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50'"
                                 class="border-2 rounded-2xl p-5 flex items-start gap-4 cursor-pointer transition-all duration-200 relative group shadow-sm">
                                <div :class="source === 'gobierno_regional' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-400 border border-slate-200'"
                                     class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors">
                                    <i class="fa-solid fa-building-government text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-black text-slate-900 text-sm leading-tight">Gobierno Regional</h4>
                                    <p class="text-slate-500 text-[11px] mt-1 font-medium leading-relaxed">Presupuesto ordinario asignado por la Sede Central del GORE Puno.</p>
                                </div>
                                <div class="absolute top-4 right-4 text-indigo-600 text-xs" x-show="source === 'gobierno_regional'"><i class="fa-solid fa-circle-check text-base"></i></div>
                            </div>

                            <div @click="source = 'gobierno_central'" 
                                 :class="source === 'gobierno_central' ? 'border-amber-500 bg-amber-50/20 ring-2 ring-amber-500/10' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50'"
                                 class="border-2 rounded-2xl p-5 flex items-start gap-4 cursor-pointer transition-all duration-200 relative group shadow-sm">
                                <div :class="source === 'gobierno_central' ? 'bg-amber-500 text-white shadow-md' : 'bg-white text-slate-400 border border-slate-200'"
                                     class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors">
                                    <i class="fa-solid fa-building-shield text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-black text-slate-900 text-sm leading-tight">SUNAFIL / Gobierno Central</h4>
                                    <p class="text-slate-500 text-[11px] mt-1 font-medium leading-relaxed">Transferencias sectoriales directas de fiscalización laboral y tesoro público.</p>
                                </div>
                                <div class="absolute top-4 right-4 text-amber-500 text-xs" x-show="source === 'gobierno_central'"><i class="fa-solid fa-circle-check text-base"></i></div>
                            </div>

                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="block text-slate-700 text-sm font-black tracking-tight">Unidad de Medida</label>
                            <div class="relative">
                                <select name="unit_measure" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner appearance-none cursor-pointer">
                                    <option value="PERSONAS" {{ old('unit_measure') == 'PERSONAS' ? 'selected' : '' }}>PERSONAS ALCANZADAS</option>
                                    <option value="ACTAS" {{ old('unit_measure') == 'ACTAS' ? 'selected' : '' }}>ACTAS FIRMADAS</option>
                                    <option value="EVENTOS" {{ old('unit_measure') == 'EVENTOS' ? 'selected' : '' }}>EVENTOS EJECUTADOS</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400"><i class="fa-solid fa-chevron-down text-xs"></i></div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-slate-700 text-sm font-black tracking-tight">Meta Física <span class="text-slate-400 font-medium">(Cantidad Anual)</span></label>
                            <input type="number" name="goal_people" min="1" value="{{ old('goal_people') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-black text-slate-800 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:outline-none transition-all shadow-inner" placeholder="0">
                            @error('goal_people') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                </div>

                <div class="p-6 sm:p-8 bg-slate-50/50 border-t border-slate-100 flex items-center justify-end gap-4">
                    <a href="{{ route('events.index') }}" class="px-5 py-3 rounded-xl text-slate-500 hover:text-slate-800 font-bold text-sm transition-colors uppercase tracking-wider">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-slate-900 hover:bg-indigo-600 text-white font-black text-sm uppercase tracking-wider py-3.5 px-8 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Guardar Actividad
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>


