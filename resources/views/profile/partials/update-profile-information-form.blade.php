<section class="bg-white/90 backdrop-blur-md shadow-lg border border-slate-100 rounded-3xl p-6 sm:p-10 relative overflow-hidden">
    
    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-40 h-40 bg-red-50 rounded-full blur-3xl z-0 pointer-events-none"></div>

    <header class="relative z-10 mb-8 border-b border-slate-100 pb-6">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-md">
                <i class="fa-solid fa-id-card-clip text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                    Información del Perfil
                </h2>
                <p class="mt-1 text-sm text-slate-500 font-medium">
                    Actualice los datos personales y el usuario de acceso de su cuenta.
                </p>
            </div>
        </div>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="relative z-10 space-y-6 max-w-2xl">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-bold text-slate-700 mb-2 ml-1">
                Nombre Completo
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-user text-slate-400 group-focus-within:text-red-600 transition-colors"></i>
                </div>
                <input id="name" 
                       name="name" 
                       type="text" 
                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition-all shadow-inner placeholder-slate-400" 
                       value="{{ old('name', $user->name) }}" 
                       required 
                       autofocus 
                       autocomplete="name" 
                       placeholder="Ej. Juan Pérez" />
            </div>
            @error('name')
                <p class="text-red-500 text-xs font-bold mt-2 ml-1 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2 ml-1">
                Usuario de Acceso
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-circle-user text-slate-400 group-focus-within:text-red-600 transition-colors"></i>
                </div>
                <input id="email" 
                       name="email" 
                       type="text" 
                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition-all shadow-inner placeholder-slate-400" 
                       value="{{ old('email', $user->email) }}" 
                       required 
                       autocomplete="username" 
                       placeholder="Ingrese su usuario" />
            </div>
            @error('email')
                <p class="text-red-500 text-xs font-bold mt-2 ml-1 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex items-center gap-5 pt-6 border-t border-slate-100">
            <button type="submit" class="bg-slate-900 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-wider py-3.5 px-8 rounded-xl transition-all duration-300 shadow-[0_4px_15px_-3px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_20px_-3px_rgba(220,38,38,0.5)] hover:-translate-y-0.5 flex items-center gap-2 group/btn">
                <i class="fa-solid fa-floppy-disk group-hover/btn:scale-110 transition-transform"></i>
                Guardar Cambios
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="opacity-0 translate-y-2"
                   x-transition:enter-end="opacity-100 translate-y-0"
                   x-transition:leave="transition ease-in duration-300"
                   x-transition:leave-start="opacity-100"
                   x-transition:leave-end="opacity-0"
                   x-init="setTimeout(() => show = false, 3000)"
                   class="text-sm font-bold text-emerald-700 bg-emerald-50 px-4 py-2.5 rounded-lg border border-emerald-200 flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i> 
                    Perfil actualizado correctamente.
                </p>
            @endif
        </div>
    </form>
</section>
