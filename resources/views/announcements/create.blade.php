<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-slate-800 tracking-tight flex items-center gap-3">
            <a href="{{ route('announcements.index') }}" class="hover:text-amber-500 transition-colors"><i class="fa-solid fa-arrow-left text-xl"></i></a>
            Publicar Nuevo Comunicado
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6">
        <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-100 shadow-xl rounded-2xl p-6 sm:p-10 space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="title" class="block text-sm font-bold text-slate-700">Título del Comunicado</label>
                <input type="text" id="title" name="title" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:ring-2 focus:ring-amber-500 focus:outline-none transition-all shadow-inner" placeholder="Ej. Comunicado N° 024-2026-DTPE">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="published_at" class="block text-sm font-bold text-slate-700">Fecha Lanzamiento / Publicación</label>
                    <input type="date" id="published_at" name="published_at" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:ring-2 focus:ring-amber-500 focus:outline-none transition-all shadow-inner">
                </div>
                <div class="space-y-2">
                    <label for="expired_at" class="block text-sm font-bold text-slate-700">Fecha de Retiro / Vencimiento</label>
                    <input type="date" id="expired_at" name="expired_at" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold focus:ring-2 focus:ring-amber-500 focus:outline-none transition-all shadow-inner">
                </div>
            </div>

            <div class="space-y-2">
                <label for="description" class="block text-sm font-bold text-slate-700">Notas / Descripción breve (Opcional)</label>
                <textarea id="description" name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-amber-500 focus:outline-none transition-all shadow-inner" placeholder="Añada una pequeña referencia informativa..."></textarea>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Archivo Adjunto (PDF o Imagen)</label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center bg-slate-50 hover:bg-slate-100/50 transition-colors relative cursor-pointer">
                    <input type="file" name="file" accept="application/pdf, image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="space-y-1">
                        <i class="fa-solid fa-photo-film text-3xl text-slate-400"></i>
                        <p class="text-sm font-bold text-slate-600">Suelte el documento aquí o explore</p>
                        <p class="text-xs text-slate-400 font-medium">Extensiones válidas: PDF, JPG, PNG, WEBP (Máx. 10MB)</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="bg-slate-900 hover:bg-amber-600 text-white font-bold py-3.5 px-8 rounded-xl transition-all shadow flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Lanzar Comunicado
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

