<!DOCTYPE html>
<html lang="sw" class="h-full bg-[#0b1116] text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') | Linkora Tanzania</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: {
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
        .display-font { font-family: 'Fraunces', Georgia, serif; }
    </style>
</head>
<body class="h-full flex flex-col font-sans antialiased bg-[#0b1116] text-slate-100 selection:bg-emerald-600 selection:text-white">

    <!-- Top Navigation Header -->
    <header class="bg-[#10171f] border-b border-[#1f2b38] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo & Corporate Identity -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-forest-700 flex items-center justify-center text-white border border-forest-600 shadow-sm">
                            <i class="fa-solid fa-link text-base"></i>
                        </div>
                        <div>
                            <span class="display-font text-lg font-bold tracking-tight text-white">Linkora <span class="text-xs text-emerald-400 font-sans font-bold uppercase ml-1">Admin</span></span>
                            <span class="block text-[10px] text-slate-400">Swebetech Company Ltd • Mbeya, Tanzania</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 text-xs sm:text-sm font-semibold">
                    <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-forest-900 text-emerald-300 border border-forest-700' : 'text-slate-300 hover:bg-[#18232e] hover:text-white' }}">
                        <i class="fa-solid fa-chart-pie mr-1.5 text-xs"></i> Dashibodi
                    </a>
                    <a href="{{ route('admin.listings') }}" class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.listings') ? 'bg-forest-900 text-emerald-300 border border-forest-700' : 'text-slate-300 hover:bg-[#18232e] hover:text-white' }}">
                        <i class="fa-solid fa-briefcase mr-1.5 text-xs"></i> Kazi na Nafasi
                    </a>
                    <a href="{{ route('admin.users') }}" class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.users') ? 'bg-forest-900 text-emerald-300 border border-forest-700' : 'text-slate-300 hover:bg-[#18232e] hover:text-white' }}">
                        <i class="fa-solid fa-users mr-1.5 text-xs"></i> Watumiaji na NIDA
                    </a>
                    <a href="{{ route('admin.transactions') }}" class="px-3.5 py-2 rounded-xl transition {{ request()->routeIs('admin.transactions') ? 'bg-forest-900 text-emerald-300 border border-forest-700' : 'text-slate-300 hover:bg-[#18232e] hover:text-white' }}">
                        <i class="fa-solid fa-shield-halved mr-1.5 text-xs"></i> Amana ya Escrow
                    </a>
                </nav>

                <!-- Public Site Link, Profile & Logout -->
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 bg-[#16212b] border border-[#233140] hover:text-white hover:bg-[#1c2a38] transition">
                        <i class="fa-solid fa-globe text-emerald-400"></i>
                        <span>Tazama Tovuti</span>
                    </a>

                    <div class="flex items-center gap-2.5 pl-2 border-l border-[#1f2b38]">
                        <div class="w-8 h-8 rounded-full bg-forest-800 border border-forest-600 flex items-center justify-center text-emerald-200 font-bold text-xs">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div class="hidden lg:block text-left text-xs">
                            <span class="block font-bold text-slate-200">{{ auth()->user()->full_name ?? session('admin_name', 'Msimamizi Mkuu') }}</span>
                            <span class="block text-[10px] text-emerald-400 font-semibold">SuperAdmin</span>
                        </div>

                        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Ondoka" class="ml-1 p-2 rounded-lg bg-rose-950/60 hover:bg-rose-900 text-rose-300 border border-rose-800/80 text-xs font-semibold transition flex items-center gap-1.5">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span class="hidden sm:inline">Ondoka</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 mb-4 rounded-xl bg-emerald-950/90 border border-emerald-600 text-emerald-200 flex items-center justify-between shadow-md">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-4 mb-4 rounded-xl bg-rose-950/90 border border-rose-600 text-rose-200 shadow-md">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400 text-base"></i>
                    <span class="text-sm font-bold">Kuna makosa yafuatayo yaliyojitokeza:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5 ml-6 text-rose-300">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Body Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#0e141a] border-t border-[#1b2530] py-6 mt-12 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Swebetech Company Limited • Linkora Ecosystem • BRELA No. 579919 • Mbeya, Tanzania</p>
    </footer>

</body>
</html>
