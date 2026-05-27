<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Acceso Administrativo | DRTPE Puno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-image: url('/images/fondodash.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        /* Tarjeta Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Línea decorativa superior */
        .card-accent {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #1e293b 0%, #b91c1c 50%, #1e293b 100%);
            border-radius: 1.5rem 1.5rem 0 0;
        }

        /* Input autofill fix para que no se ponga blanco feo */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #f8fafc inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="antialiased selection:bg-red-700 selection:text-white min-h-screen flex items-center justify-center p-4 relative">

    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] z-0"></div>

    <main class="w-full max-w-md relative z-10">
        <div class="glass-card rounded-3xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.5)] border border-white/50 relative p-8 sm:p-10">
            <div class="card-accent"></div>

            <div class="text-center mb-10">
                <div class="w-20 h-20 mx-auto bg-white rounded-2xl shadow-md border border-slate-100 p-2.5 mb-5 transition-transform hover:scale-105">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Institucional" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Acceso Interno</h1>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-2">Dirección de Regional de Trabajo y Promoción del Empleo - Puno</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="username" class="block text-sm font-bold text-slate-700 ml-1">
                        Usuario
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-slate-400 group-focus-within:text-red-600 transition-colors"></i>
                        </div>
                        <input id="username" 
                               type="text" 
                               name="username" 
                               value="{{ old('username') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition-all shadow-inner" 
                               placeholder="Nombre de su Usuario">
                    </div>
                    @error('username')
                        <p class="text-red-500 text-xs font-bold ml-1 flex items-center gap-1 mt-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-bold text-slate-700 ml-1">
                        Contraseña
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400 group-focus-within:text-red-600 transition-colors"></i>
                        </div>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               class="w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent transition-all shadow-inner" 
                               placeholder="••••••••">
                               
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" id="togglePassword" class="p-2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                                <i class="fa-regular fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs font-bold ml-1 flex items-center gap-1 mt-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-red-700 text-white font-bold text-sm uppercase tracking-wider py-4 rounded-xl transition-all duration-300 shadow-[0_8px_20px_-6px_rgba(0,0,0,0.3)] hover:shadow-[0_10px_25px_-6px_rgba(220,38,38,0.5)] hover:-translate-y-0.5 flex justify-center items-center gap-3 group/btn">
                        <span>Iniciar Sesión</span>
                        <i class="fa-solid fa-arrow-right-to-bracket group-hover/btn:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white/70 hover:text-white transition-colors bg-black/20 hover:bg-black/40 px-5 py-2.5 rounded-full backdrop-blur-sm border border-white/10">
                <i class="fa-solid fa-arrow-left"></i> Volver al Portal Operativo
            </a>
        </div>
    </main>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function (e) {
            // Alternar el tipo de input
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Alternar el icono
            if(type === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>

