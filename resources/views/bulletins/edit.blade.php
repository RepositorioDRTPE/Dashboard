
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-800 tracking-tight flex items-center gap-3">
            <a href="{{ route('bulletins.index') }}" class="hover:text-amber-500 transition-colors"><i class="fa-solid fa-arrow-left text-xl"></i></a>
            Modificar Boletín publicado
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6">
        <form action="{{ route('bulletins.update', $bulletin->id) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-100 shadow-xl rounded-2xl p-6 sm:p-10 space-y-6">
            @csrf @method('put')
            
            <div class="space-y-2">
                <label for="title" class="block text-sm font-bold text-slate-700">Título del Boletín</label>
                <input type="text" id="title" name="title" value="{{ old('title', $bulletin->title) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none transition-all shadow-inner">
            </div>

            <div class="space-y-2">
                <label for="description" class="block text-sm font-bold text-slate-700">Descripción / Resumen</label>
                <textarea id="description" name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none transition-all shadow-inner">{{ old('description', $bulletin->description) }}</textarea>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Actualizar archivo PDF (Dejar vacío para conservar el actual)</label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center bg-slate-50 hover:bg-slate-100/50 transition-colors relative cursor-pointer">
                    <input type="file" name="file" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="space-y-1">
                        <i class="fa-solid fa-file-pdf text-3xl text-amber-500"></i>
                        <p class="text-sm font-bold text-slate-600">Suba un nuevo archivo si desea reemplazar el documento</p>
                        <p class="text-xs text-slate-400 font-medium">Archivo actual registrado en el sistema</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="bg-slate-900 hover:bg-amber-600 text-white font-bold py-3.5 px-8 rounded-xl transition-all shadow flex items-center gap-2">
                    <i class="fa-solid fa-rotate"></i> Actualizar Registro
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

