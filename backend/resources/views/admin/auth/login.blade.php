<!DOCTYPE html>
<html lang="sw" class="h-full bg-[#0b1116] text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin Login | Linkora Tanzania</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
        .display-font { font-family: 'Fraunces', Georgia, serif; }
    </style>
</head>
<body class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-[#0b1116] antialiased selection:bg-emerald-600 selection:text-white">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center px-4">
        <!-- Brand Logo Icon (Clean, Solid, No Purple/Blue AI Gradients) -->
        <div class="inline-flex w-14 h-14 rounded-2xl bg-emerald-800 text-white items-center justify-center shadow-lg mb-4 border border-emerald-600">
            <i class="fa-solid fa-shield-halved text-2xl"></i>
        </div>
        
        <h2 class="display-font text-3xl font-bold tracking-tight text-white">
            Linkora
        </h2>
        <p class="mt-1 text-xs font-bold uppercase tracking-widest text-emerald-400">
            Lango Kuu la Utawala (SuperAdmin Console)
        </p>
        <p class="mt-1 text-xs text-slate-400">
            Swebetech Company Limited • Mbeya, Tanzania
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="bg-[#121820] py-8 px-6 sm:px-10 shadow-xl rounded-2xl border border-[#222d3a]">
            
            <!-- Security Guard Pill -->
            <div class="mb-6 flex items-center justify-between p-2.5 rounded-xl bg-[#17212b] border border-[#263544] text-xs">
                <div class="flex items-center gap-2 text-emerald-400 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Ulinzi wa NIDA na Escrow</span>
                </div>
                <span class="text-slate-400 font-mono text-[11px]">Mfumo Salama</span>
            </div>

            <!-- Flash Success Message -->
            @if(session('success'))
                <div class="mb-6 p-3 rounded-xl bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Validation Errors -->
            @if(isset($errors) && $errors->any())
                <div class="mb-6 p-3 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-300 text-xs flex items-start gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400 text-sm mt-0.5"></i>
                    <div>
                        <span class="font-bold block mb-1">Hitilafu ya Kuingia:</span>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Phone / Email Field -->
                <div>
                    <label for="login" class="block text-xs font-semibold text-slate-300 mb-1.5">
                        Barua Pepe au Namba ya Simu
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-user-shield text-sm"></i>
                        </div>
                        <input type="text" name="login" id="login" required autofocus
                            value="{{ old('login') }}"
                            placeholder="admin@linkora.co.tz au namba ya simu"
                            class="block w-full pl-10 pr-3 py-2.5 bg-[#0b1116] border border-[#2b3a4a] rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-300">
                            Nenosiri la Msimamizi
                        </label>
                    </div>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            placeholder="••••••••"
                            autocomplete="current-password"
                            class="block w-full pl-10 pr-10 py-2.5 bg-[#0b1116] border border-[#2b3a4a] rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 focus:outline-none">
                            <i id="eyeIcon" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-400 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-[#0b1116] border-[#2b3a4a] text-emerald-600 focus:ring-emerald-500">
                        <span class="ml-2">Kumbuka Kifaa Hiki</span>
                    </label>
                    <span class="text-slate-500 text-[11px] font-mono">Role: ADMIN</span>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl text-sm font-bold text-white bg-emerald-700 hover:bg-emerald-600 transition shadow-md">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Ingia Kwenye Dashibodi</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-[#1e2a36] text-center">
                <a href="{{ url('/') }}" class="text-xs text-slate-400 hover:text-emerald-400 transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Rudi Ukurasa wa Kwanza (Linkora.co.tz)</span>
                </a>
            </div>

        </div>

        <div class="mt-8 text-center text-xs text-slate-500 space-y-1">
            <p>&copy; {{ date('Y') }} Swebetech Company Limited • Linkora Ecosystem</p>
            <p>BRELA No. 579919 • Makao Makuu: Mbeya, Tanzania</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
