<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-800 tracking-tight flex items-center gap-3">
            <a href="{{ route('bulletins.index') }}" class="hover:text-amber-500 transition-colors"><i class="fa-solid fa-arrow-left text-xl"></i></a>
            Publicar Nuevo Boletín
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6">
        <form action="{{ route('bulletins.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-100 shadow-xl rounded-2xl p-6 sm:p-10 space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="title" class="block text-sm font-bold text-slate-700">Título del Boletín</label>
                <input type="text" id="title" name="title" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none transition-all shadow-inner" placeholder="Ej. Boletín Informativo - Mayo 2026">
                @error('title') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="description" class="block text-sm font-bold text-slate-700">Descripción / Resumen (Opcional)</label>
                <textarea id="description" name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none transition-all shadow-inner" placeholder="Escriba un resumen corto sobre los comunicados contenidos..."></textarea>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Documento adjunto (PDF)</label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center bg-slate-50 hover:bg-slate-100/50 transition-colors relative cursor-pointer">
                    <input type="file" name="file" accept="application/pdf" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="space-y-1">
                        <i class="fa-solid fa-file-arrow-up text-3xl text-slate-400"></i>
                        <p class="text-sm font-bold text-slate-600">Seleccione o arrastre el archivo PDF</p>
                        <p class="text-xs text-slate-400 font-medium">Tamaño máximo admitido: 15 Megabytes</p>
                    </div>
                </div>
                @error('file') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="bg-slate-900 hover:bg-amber-600 text-white font-bold py-3.5 px-8 rounded-xl transition-all shadow flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Publicar Documento
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

