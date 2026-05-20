<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-800 to-slate-950 text-white flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-images text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                        Galería de Evidencias e Impacto
                    </h2>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-0.5">
                        Archivo de actividades institucionales
                    </p>
                </div>
            </div>
            <a href="{{ route('photo-reports.create') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white font-bold text-sm uppercase tracking-wider py-3 px-6 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2 group">
                <i class="fa-solid fa-circle-plus group-hover:rotate-90 transition-transform"></i>
                Nuevo Reporte
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-500" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-md flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 text-xl animate-bounce"></i>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($reports as $report)
                <article class="bg-white/95 backdrop-blur-md rounded-2xl shadow-md border border-slate-100 overflow-hidden relative flex flex-col">
                    
                    <div class="absolute top-0 left-0 right-0 h-1.5 {{ $report->type === 'evento' ? 'bg-red-600' : 'bg-blue-600' }} z-20"></div>

                    <div class="p-6 pb-4">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider shadow-sm
                                {{ $report->type === 'evento' ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
                                <i class="{{ $report->type === 'evento' ? 'fa-solid fa-calendar-star' : 'fa-solid fa-radio' }}"></i>
                                {{ $report->type === 'evento' ? 'Evento' : 'Difusión' }}
                            </span>
                            <span class="text-xs font-bold text-slate-400 flex items-center gap-1">
                                <i class="fa-regular fa-clock"></i>
                                {{ $report->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        <h3 class="text-xl font-black text-slate-900 leading-tight mb-2">
                            {{ $report->title }}
                        </h3>
                        
                        <p class="text-slate-600 text-sm leading-relaxed font-medium line-clamp-3 mb-4">
                            {{ $report->description }}
                        </p>
                    </div>

                    @if(is_array($report->photos) && count($report->photos) > 0)
                        <div x-data="{ current: 0, photos: {{ json_encode($report->photos) }} }" class="relative h-64 sm:h-72 w-full bg-slate-900 group/slider mt-auto">
                            
                            <img :src="'{{ asset('storage') }}/' + photos[current]" class="w-full h-full object-cover transition-opacity duration-300">

                            <template x-if="photos.length > 1">
                                <div>
                                    <button @click="current = current === 0 ? photos.length - 1 : current - 1" 
                                            class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-red-600 text-white backdrop-blur-sm flex items-center justify-center opacity-0 group-hover/slider:opacity-100 transition-all duration-300 shadow-md">
                                        <i class="fa-solid fa-chevron-left text-sm pr-0.5"></i>
                                    </button>
                                    
                                    <button @click="current = current === photos.length - 1 ? 0 : current + 1" 
                                            class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 hover:bg-red-600 text-white backdrop-blur-sm flex items-center justify-center opacity-0 group-hover/slider:opacity-100 transition-all duration-300 shadow-md">
                                        <i class="fa-solid fa-chevron-right text-sm pl-0.5"></i>
                                    </button>

                                    <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-sm border border-white/10">
                                        <i class="fa-solid fa-camera mr-1 text-slate-300"></i>
                                        <span x-text="current + 1"></span> / <span x-text="photos.length"></span>
                                    </div>

                                    <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-1.5 z-10">
                                        <template x-for="(p, index) in photos" :key="index">
                                            <button @click="current = index" 
                                                    class="h-2 rounded-full transition-all duration-300 shadow-sm" 
                                                    :class="current === index ? 'bg-red-500 w-4' : 'bg-white/60 hover:bg-white w-2'"></button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    @endif
                </article>
            @empty
                <div class="col-span-1 md:col-span-2 text-center py-20 bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-slate-100">
                    <div class="w-20 h-20 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100 shadow-inner text-slate-400">
                        <i class="fa-regular fa-folder-open text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800">No hay registros fotográficos</h3>
                    <p class="text-slate-500 mt-1 text-sm font-medium max-w-md mx-auto">
                        Aún no se han publicado evidencias en esta sección.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $reports->links() }}
        </div>
    </div>
</x-app-layout>
