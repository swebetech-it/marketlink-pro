<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin Login - Linkora | Swebetech</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#1E3A8A',
                            emerald: '#059669',
                            gold: '#D97706',
                            dark: '#0F172A',
                            card: '#1E293B'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 font-sans antialiased">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center px-4">
        <!-- Brand Logo Icon -->
        <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-600 via-teal-600 to-blue-600 items-center justify-center shadow-xl shadow-emerald-900/40 mb-4 border border-emerald-400/30 ring-4 ring-emerald-500/10">
            <i class="fa-solid fa-shield-halved text-white text-2xl"></i>
        </div>
        
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
            Link<span class="text-emerald-400">ora</span>
        </h2>
        <p class="mt-1 text-xs font-semibold uppercase tracking-widest text-emerald-400/90">
            SuperAdmin Management Console
        </p>
        <p class="mt-1 text-xs text-slate-400">
            Swebetech Company Limited • Mbeya, Tanzania
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="bg-slate-900/90 backdrop-blur-xl py-8 px-6 sm:px-10 shadow-2xl rounded-2xl border border-slate-800 ring-1 ring-slate-700/50">
            
            <!-- Security Notice Pill -->
            <div class="mb-6 flex items-center justify-between p-2.5 rounded-xl bg-slate-800/80 border border-slate-700/60 text-xs">
                <div class="flex items-center gap-2 text-emerald-400 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>NIDA & KYC Guard</span>
                </div>
                <span class="text-slate-400 font-mono text-[11px]">Port 8000 • MySQL</span>
            </div>

            <!-- Flash Success Message -->
            @if(session('success'))
                <div class="mb-6 p-3 rounded-xl bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="mb-6 p-3 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-300 text-xs flex items-start gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400 text-sm mt-0.5"></i>
                    <div>
                        <span class="font-bold block mb-1">Hitilafu ya Kuingia / Login Error:</span>
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
                        Barua Pepe au Namba ya Simu (Email / Phone)
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-user-shield text-sm"></i>
                        </div>
                        <input type="text" name="login" id="login" required autofocus
                            value="{{ old('login') }}"
                            placeholder="admin@linkora.co.tz au namba ya simu"
                            class="block w-full pl-10 pr-3 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-300">
                            Nenosiri (SuperAdmin Password)
                        </label>
                    </div>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            value=""
                            placeholder="••••••••"
                            autocomplete="current-password"
                            class="block w-full pl-10 pr-10 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 focus:outline-none">
                            <i id="eyeIcon" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-400 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-slate-900">
                        <span class="ml-2">Kumbuka Kifaa Hiki (Remember)</span>
                    </label>
                    <span class="text-slate-500 text-[11px]">Role: ADMIN</span>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl shadow-lg shadow-emerald-900/30 text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 focus:ring-offset-slate-900 transition active:scale-[0.99]">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Ingia Kama Msimamizi Mkuu</span>
                    </button>
                </div>
            </form>

        </div>

        <div class="mt-8 text-center text-xs text-slate-500 space-y-1">
            <p>&copy; {{ date('Y') }} Swebetech Company Limited • Linkora Ecosystem (SRS V1.0)</p>
            <p>BRELA No. 579919 • Regional Operations: Mbeya, Tanzania</p>
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
