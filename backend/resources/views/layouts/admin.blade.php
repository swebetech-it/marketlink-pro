<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') - MarketLink Pro | Swebetech</title>
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
<body class="h-full flex flex-col font-sans antialiased bg-slate-950 text-slate-100">

    <!-- Top Navigation Header -->
    <header class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-blue-600 flex items-center justify-center shadow-lg shadow-emerald-900/30">
                        <i class="fa-solid fa-link text-white text-lg"></i>
                    </div>
                    <div>
                        <span class="text-lg font-black tracking-tight text-white">MarketLink <span class="text-emerald-400">Pro</span></span>
                        <span class="block text-[10px] uppercase font-semibold tracking-wider text-slate-400">Swebetech Company Ltd • Mbeya, Tanzania</span>
                    </div>
                </div>

                <!-- Nav Links -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-chart-pie mr-1.5"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.listings') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.listings') ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-briefcase mr-1.5"></i> Listings & Posts
                    </a>
                    <a href="{{ route('admin.users') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.users') ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-users mr-1.5"></i> Users & KYC
                    </a>
                    <a href="{{ route('admin.transactions') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.transactions') ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-shield-halved mr-1.5"></i> Escrow Ledger
                    </a>
                </nav>

                <!-- Status, SuperAdmin Profile & Logout -->
                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> MySQL Connected
                    </span>

                    <div class="flex items-center gap-2 pl-2 border-l border-slate-800">
                        <div class="w-8 h-8 rounded-full bg-emerald-600/30 border border-emerald-500/50 flex items-center justify-center text-emerald-300 font-bold text-xs">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div class="hidden lg:block text-left text-xs">
                            <span class="block font-bold text-slate-200">{{ auth()->user()->full_name ?? session('admin_name', 'SuperAdmin') }}</span>
                            <span class="block text-[10px] text-emerald-400 font-semibold">SuperAdmin</span>
                        </div>

                        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Ondoka / Sign Out" class="ml-1 p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-medium transition flex items-center gap-1.5">
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
            <div class="p-4 mb-4 rounded-xl bg-emerald-950/80 border border-emerald-600/50 text-emerald-300 flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 rounded-xl bg-rose-950/80 border border-rose-600/50 text-rose-300 shadow-lg">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400 text-lg"></i>
                    <span class="text-sm font-bold">Please correct the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5 ml-6">
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
    <footer class="bg-slate-900 border-t border-slate-800 py-6 mt-12 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Swebetech Company Limited • MarketLink Pro Ecosystem (SRS V1.0) • BRELA No. 579919 • Mbeya, Tanzania</p>
    </footer>

</body>
</html>
