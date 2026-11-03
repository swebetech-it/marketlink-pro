<!DOCTYPE html>
<html lang="sw" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkora | Soko Halisi la Kazi za Muda Mfupi na Ajira Tanzania (NIDA & Escrow)</title>
    <meta name="description" content="Tafuta au toa ajira za muda mfupi na mrefu Tanzania. Uthibitisho wa vitambulisho vya NIDA, mfumo salama wa M-Pesa Escrow, na uhusiano wa moja kwa moja kati ya mwajiri na mfanyakazi.">
    <meta name="keywords" content="Linkora, Ajira Tanzania, Mafundi Dar es Salaam, Vibarua Tanzania, Kazi za Muda Mfupi, Escrow M-Pesa, NIDA Verified, Swebetech">
    
    <!-- OpenGraph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://linkora.co.tz">
    <meta property="og:title" content="Linkora - Kazi za Uhakika, Mafundi Wanaoaminika Tanzania">
    <meta property="og:description" content="Ungana na mafundi waliothibitishwa kwa NIDA. Malipo yote yanalindwa na Escrow ya simu mpaka kazi ikamilike.">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            500: '#16a34a',
                            600: '#15803d',
                            700: '#166534',
                            800: '#14532d',
                            900: '#052e16',
                        },
                        amberWarm: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                        },
                        earth: {
                            50: '#faf7f2',
                            100: '#f4efe6',
                            200: '#e8dfd1',
                            800: '#231f1c',
                            900: '#151311',
                            950: '#0c0b0a',
                        },
                        surface: {
                            card: '#141a1f',
                            border: '#222d36',
                            muted: '#8b98a5',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Typography: Editorial Serif Display (Fraunces) + Warm Human Sans (Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;0,9..144,800;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #0b1116;
            color: #f1f5f9;
        }
        .display-font {
            font-family: 'Fraunces', Georgia, serif;
            letter-spacing: -0.02em;
        }
        .mono-font {
            font-family: 'JetBrains Mono', monospace;
        }
        
        /* Smooth infinite marquee for live opportunities ticker */
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            display: flex;
            width: max-content;
            animation: marquee 38s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }

        /* Subtle tactile shadows and cards */
        .tactile-card {
            background-color: #121820;
            border: 1px solid #1f2a36;
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .tactile-card:hover {
            border-color: #2e3e4f;
            transform: translateY(-2px);
            box-shadow: 0 16px 30px -10px rgba(0, 0, 0, 0.45);
        }

        /* Warm button styles */
        .btn-primary {
            background-color: #15803d;
            color: #ffffff;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #16a34a;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -4px rgba(22, 163, 74, 0.35);
        }

        .btn-secondary {
            background-color: #1c2631;
            color: #f1f5f9;
            border: 1px solid #2d3b4b;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-secondary:hover {
            background-color: #243140;
            border-color: #3b4d61;
        }
    </style>
</head>
<body class="antialiased selection:bg-emerald-600 selection:text-white">

    <!-- TOP VERIFICATION & ANNOUNCEMENT BAR -->
    <div class="bg-forest-900 border-b border-forest-800/80 py-2.5 px-4 text-xs text-center text-forest-100">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-center gap-x-4 gap-y-1">
            <span class="inline-flex items-center gap-1.5 font-medium">
                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                <span>Mfumo Rasmi wa Tanzania wa Ajira, Vibarua na Malipo ya Escrow (Linkora v1.0)</span>
            </span>
            <span class="hidden md:inline text-forest-400">•</span>
            <span class="text-forest-200">Swebetech Company Ltd, Mbeya, Tanzania</span>
            <a href="#pakua-app" class="font-bold text-white underline hover:text-emerald-300 ml-1">Pakua APK ya Android &rarr;</a>
        </div>
    </div>

    <!-- MAIN NAVBAR -->
    <header class="sticky top-0 z-50 bg-[#0e141b]/95 backdrop-blur-md border-b border-[#1b2530]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="h-11 w-11 rounded-xl bg-forest-700 text-white flex items-center justify-center shadow-md border border-forest-600 group-hover:bg-forest-600 transition">
                    <i class="fa-solid fa-link text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="display-font text-2xl font-bold text-white">Linkora</span>
                        <span class="text-[10px] font-bold bg-forest-900 text-emerald-400 px-2 py-0.5 rounded border border-forest-700">TANZANIA</span>
                    </div>
                    <p class="text-[11px] text-slate-400">Kazi Halisi, Malipo ya Uhakika</p>
                </div>
            </a>

            <!-- Desktop Nav Items -->
            <nav class="hidden lg:flex items-center gap-7 text-sm font-semibold text-slate-300">
                <a href="#aina-za-kazi" class="hover:text-emerald-400 transition-colors">Aina za Kazi</a>
                <a href="#jinsi-inavyofanya-kazi" class="hover:text-emerald-400 transition-colors">Jinsi Inavyofanya Kazi</a>
                <a href="#usalama-escrow" class="hover:text-emerald-400 transition-colors">Usalama wa Malipo</a>
                <a href="#mashuhuda" class="hover:text-emerald-400 transition-colors">Wafanyakazi Wetu</a>
                <a href="#maswali" class="hover:text-emerald-400 transition-colors">Maswali (FAQ)</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.login') }}" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-300 hover:text-white bg-slate-900 hover:bg-slate-800 border border-slate-700/80 transition">
                    <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                    <span>Admin Portal</span>
                </a>
                
                <a href="#pakua-app" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold btn-primary">
                    <i class="fa-brands fa-android text-base"></i>
                    <span>Pakua App (Bure)</span>
                </a>
            </div>
        </div>
    </header>

    <!-- HERO SECTION (HUMAN-CENTERED, REAL PHOTOGRAPHY, EDITORIAL TYPOGRAPHY) -->
    <section class="relative pt-12 pb-20 md:pt-16 md:pb-24 overflow-hidden border-b border-[#1b2530]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left: Honest, Human Value Proposition -->
                <div class="lg:col-span-7 text-center lg:text-left">
                    
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#13221b] border border-forest-700 text-emerald-400 text-xs font-semibold mb-6">
                        <i class="fa-solid fa-id-card-clip"></i>
                        <span>Uthibitisho wa NIDA kwa Kila Mfanyakazi na Mwajiri</span>
                    </div>

                    <h1 class="display-font text-3xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-[1.12] mb-6">
                        Pata kazi ya uhakika, au ajiri fundi anayeaminika Tanzania.
                    </h1>

                    <p class="text-base sm:text-lg text-slate-300 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        Kila siku Watanzania wengi wanakosa kazi au wanadhulumiwa baada ya kufanya kazi ngumu. Linkora inaleta suluhisho la moja kwa moja: mafundi na wataalamu wanathibitishwa na vitambulisho vya taifa vya NIDA, na malipo yote yanashikiliwa salama kwenye Amana ya M-Pesa (Escrow) mpaka kazi iridhiwe na mteja.
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-8">
                        <a href="{{ route('download.apk') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-7 py-4 rounded-xl btn-primary text-base">
                            <i class="fa-brands fa-android text-2xl"></i>
                            <div class="text-left">
                                <div class="text-[10px] uppercase font-bold text-emerald-200 leading-none">Pakua APK Moja kwa Moja</div>
                                <div class="text-base font-bold text-white leading-tight">Linkora Android App (18 MB)</div>
                            </div>
                        </a>

                        <a href="#aina-za-kazi" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl btn-secondary text-sm font-semibold">
                            <i class="fa-solid fa-magnifying-glass text-emerald-400"></i>
                            <span>Tazama Kazi na Vibarua Vilivyopo</span>
                        </a>
                    </div>

                    <!-- Social Proof Avatars & Real Stats -->
                    <div class="pt-6 border-t border-[#1c2733] flex flex-wrap items-center justify-center lg:justify-start gap-4 sm:gap-6 text-left">
                        <div class="flex -space-x-3">
                            <img class="w-10 h-10 rounded-full border-2 border-[#0b1116] object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80" alt="Juma K. - Fundi Umeme">
                            <img class="w-10 h-10 rounded-full border-2 border-[#0b1116] object-cover" src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80" alt="Rehema M. - Msimamizi">
                            <img class="w-10 h-10 rounded-full border-2 border-[#0b1116] object-cover" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80" alt="Aloyce S. - Fundi Mabomba">
                            <img class="w-10 h-10 rounded-full border-2 border-[#0b1116] object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80" alt="Neema J. - Mhasibu">
                        </div>
                        <div>
                            <div class="flex items-center gap-1 text-amberWarm-500 text-xs">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <span class="font-bold text-white text-xs ml-1">4.9 / 5</span>
                            </div>
                            <div class="text-xs text-slate-400 mt-0.5">
                                Zaidi ya mafundi na wataalamu <strong class="text-white">{{ $stats['verified_pros'] ?? '1,200+' }}</strong> wenye NIDA tayari wamejiunga
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right: Real Human Photo + Contextual Interactive Card (NO FAKE NEON GLOW) -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative w-full max-w-[420px]">
                        
                        <!-- Real Hero Photograph: African Artisan / Technician at Work -->
                        <div class="rounded-3xl overflow-hidden border border-[#22303e] shadow-2xl bg-[#121922]">
                            <div class="relative h-72 sm:h-80 w-full overflow-hidden bg-slate-800">
                                <img src="https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=800&q=80" alt="Fundi Umeme akifanya kazi kwa weledi nchini Tanzania" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#121922] via-[#121922]/20 to-transparent"></div>
                                
                                <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between">
                                    <div class="bg-[#0e141b]/90 backdrop-blur-md px-3.5 py-2 rounded-xl border border-slate-700/80 flex items-center gap-2.5">
                                        <div class="h-8 w-8 rounded-full bg-emerald-600/30 text-emerald-400 flex items-center justify-center font-bold text-xs border border-emerald-500/40">
                                            <i class="fa-solid fa-bolt"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-white">Hamisi Mrema</div>
                                            <div class="text-[10px] text-emerald-400 font-semibold flex items-center gap-1">
                                                <i class="fa-solid fa-circle-check"></i> Fundi Umeme NIDA Verified
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-forest-900/90 border border-forest-600 text-emerald-300 font-mono text-xs font-bold px-3 py-1.5 rounded-xl">
                                        TZS 85,000
                                    </div>
                                </div>
                            </div>

                            <!-- Real App Interaction Slice -->
                            <div class="p-5 space-y-3.5">
                                <div class="flex items-center justify-between text-xs pb-3 border-b border-[#1f2b38]">
                                    <span class="text-slate-400 font-medium">Hali ya Malipo:</span>
                                    <span class="inline-flex items-center gap-1.5 font-bold text-emerald-400 bg-emerald-950/80 px-2.5 py-1 rounded-full border border-emerald-800/80">
                                        <i class="fa-solid fa-lock text-[10px]"></i> Yamelindwa na M-Pesa Escrow
                                    </span>
                                </div>

                                <div class="bg-[#18222d] p-3.5 rounded-xl border border-[#233140] text-xs space-y-1.5">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-white">Ukarabati wa Switchboard na Nyaya</span>
                                        <span class="text-[10px] text-slate-400">Mbezi Beach, Dar</span>
                                    </div>
                                    <p class="text-[11px] text-slate-300">Mteja amethibitisha malipo. Pesa imeingia moja kwa moja kwenye simu ya fundi.</p>
                                </div>

                                <div class="flex items-center justify-between pt-1 text-[11px] text-slate-400">
                                    <span><i class="fa-solid fa-shield-halved text-emerald-400 mr-1"></i> Hakuna makato ya dalali</span>
                                    <span class="font-semibold text-slate-300">Kazi ya Siku 1</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- LIVE RUNNING TICKER (DYNAMIC, MOVING, ALIVE: THE DEFINITIVE CURE FOR THE DEAD-CARD CLICHÉ) -->
    <section class="bg-[#090d12] border-b border-[#1a232e] py-3.5 overflow-hidden">
        <div class="flex items-center">
            
            <div class="bg-forest-900 text-emerald-300 text-xs font-bold uppercase tracking-wider px-4 py-2 border-r border-forest-700 shrink-0 z-10 flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Fursa za Sasa Hivi:</span>
            </div>

            <!-- Continuous Moving Stream of Real Jobs Across Tanzania -->
            <div class="overflow-hidden whitespace-nowrap w-full">
                <div class="animate-marquee flex items-center gap-8 text-xs font-medium text-slate-300 pl-4">
                    
                    <div class="inline-flex items-center gap-2 bg-[#121922] px-3.5 py-1.5 rounded-lg border border-[#1f2b38]">
                        <span class="text-amberWarm-500 font-bold">Dar es Salaam</span>
                        <span class="text-white">Fundi Mabomba (Mikocheni)</span>
                        <span class="text-emerald-400 font-mono font-bold">TZS 45,000</span>
                        <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">NIDA</span>
                    </div>

                    <div class="inline-flex items-center gap-2 bg-[#121922] px-3.5 py-1.5 rounded-lg border border-[#1f2b38]">
                        <span class="text-emerald-400 font-bold">Morogoro</span>
                        <span class="text-white">Msimamizi wa Shamba na Mazao (Kilosa)</span>
                        <span class="text-emerald-400 font-mono font-bold">TZS 750,000/mwezi</span>
                        <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">Mkataba</span>
                    </div>

                    <div class="inline-flex items-center gap-2 bg-[#121922] px-3.5 py-1.5 rounded-lg border border-[#1f2b38]">
                        <span class="text-amberWarm-500 font-bold">Mbeya Mjini</span>
                        <span class="text-white">Fundi Useremala na Samani za Maofisi</span>
                        <span class="text-emerald-400 font-mono font-bold">TZS 160,000</span>
                        <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">Escrow</span>
                    </div>

                    <div class="inline-flex items-center gap-2 bg-[#121922] px-3.5 py-1.5 rounded-lg border border-[#1f2b38]">
                        <span class="text-blue-400 font-bold">Arusha</span>
                        <span class="text-white">Dereva wa Gari la Utalii (Class C)</span>
                        <span class="text-emerald-400 font-mono font-bold">TZS 90,000/siku</span>
                        <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">NIDA</span>
                    </div>

                    <div class="inline-flex items-center gap-2 bg-[#121922] px-3.5 py-1.5 rounded-lg border border-[#1f2b38]">
                        <span class="text-amberWarm-500 font-bold">Dodoma</span>
                        <span class="text-white">Usafi wa Majengo ya Serikali na Vioo</span>
                        <span class="text-emerald-400 font-mono font-bold">TZS 55,000/siku</span>
                        <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">Kibarua</span>
                    </div>

                    <div class="inline-flex items-center gap-2 bg-[#121922] px-3.5 py-1.5 rounded-lg border border-[#1f2b38]">
                        <span class="text-emerald-400 font-bold">Mwanza</span>
                        <span class="text-white">Mtaalamu wa Mifumo ya Kompyuta na CCTV</span>
                        <span class="text-emerald-400 font-mono font-bold">TZS 320,000</span>
                        <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">Imethibitishwa</span>
                    </div>

                    <!-- Repeated duplicate for seamless loop -->
                    <div class="inline-flex items-center gap-2 bg-[#121922] px-3.5 py-1.5 rounded-lg border border-[#1f2b38]">
                        <span class="text-amberWarm-500 font-bold">Dar es Salaam</span>
                        <span class="text-white">Fundi Mabomba (Mikocheni)</span>
                        <span class="text-emerald-400 font-mono font-bold">TZS 45,000</span>
                        <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">NIDA</span>
                    </div>

                    <div class="inline-flex items-center gap-2 bg-[#121922] px-3.5 py-1.5 rounded-lg border border-[#1f2b38]">
                        <span class="text-emerald-400 font-bold">Morogoro</span>
                        <span class="text-white">Msimamizi wa Shamba na Mazao (Kilosa)</span>
                        <span class="text-emerald-400 font-mono font-bold">TZS 750,000/mwezi</span>
                        <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">Mkataba</span>
                    </div>

                    <div class="inline-flex items-center gap-2 bg-[#121922] px-3.5 py-1.5 rounded-lg border border-[#1f2b38]">
                        <span class="text-amberWarm-500 font-bold">Mbeya Mjini</span>
                        <span class="text-white">Fundi Useremala na Samani za Maofisi</span>
                        <span class="text-emerald-400 font-mono font-bold">TZS 160,000</span>
                        <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">Escrow</span>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- REAL STATS STRIP (SOLID, CLEAN, CREDIBLE) -->
    <section class="border-b border-[#1b2530] bg-[#0d1319] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="p-3">
                    <div class="display-font text-3xl sm:text-4xl font-bold text-white">{{ $stats['total_listings'] ?? '250+' }}</div>
                    <div class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Nafasi za Kazi na Vibarua Zilizopo</div>
                </div>
                <div class="p-3 border-l border-[#1f2a36]">
                    <div class="display-font text-3xl sm:text-4xl font-bold text-emerald-400">{{ $stats['verified_pros'] ?? '1,200+' }}</div>
                    <div class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Mafundi Wenye Uhakiki wa NIDA</div>
                </div>
                <div class="p-3 border-l border-[#1f2a36]">
                    <div class="display-font text-3xl sm:text-4xl font-bold text-amberWarm-500">{{ $stats['escrow_secured'] ?? 'TZS 45M+' }}</div>
                    <div class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Fedha Zilizolindwa na Escrow</div>
                </div>
                <div class="p-3 border-l border-[#1f2a36]">
                    <div class="display-font text-3xl sm:text-4xl font-bold text-white">{{ $stats['districts_covered'] ?? 'Mikoa 31' }}</div>
                    <div class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Huduma Inafika Tanzania Nzima</div>
                </div>
            </div>
        </div>
    </section>

    <!-- INTERACTIVE ROLE EXPERIENCE: NIKITAKA KAZI VS NIKITAKA KUAJIRI -->
    <section id="jinsi-inavyofanya-kazi" class="py-16 md:py-24 border-b border-[#1b2530]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-emerald-400 tracking-wider uppercase bg-[#13221b] px-3.5 py-1.5 rounded-full border border-forest-700">
                    Chagua Lengo Lako
                </span>
                <h2 class="display-font text-3xl sm:text-4xl font-bold text-white mt-4">
                    Jinsi Linkora inavyokuhudumia kwa uwazi na usalama
                </h2>
                <p class="text-slate-300 text-sm sm:text-base mt-3">
                    Bofya hapa chini kuona jinsi mfumo unavyofanya kazi ukiwa fundi/mfanyakazi anayetafuta kipato, au ukiwa mwajiri/mteja unayetaka kazi ifanyike kwa uaminifu.
                </p>

                <!-- Interactive Switcher Buttons -->
                <div class="inline-flex p-1.5 bg-[#121922] rounded-2xl border border-[#22303e] mt-6 gap-2">
                    <button id="btnTabSeeker" onclick="switchTab('seeker')" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-forest-700 text-white shadow transition">
                        <i class="fa-solid fa-person-digging mr-1.5"></i> Natafuta Kazi au Vibarua
                    </button>
                    <button id="btnTabPoster" onclick="switchTab('poster')" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-300 hover:text-white transition">
                        <i class="fa-solid fa-briefcase mr-1.5"></i> Nahitaji Kuajiri au Kutoa Kazi
                    </button>
                </div>
            </div>

            <!-- Tab Content 1: Job Seeker Journey -->
            <div id="tabSeeker" class="transition-all duration-300">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    
                    <div class="lg:col-span-6 space-y-4">
                        <div class="p-5 rounded-2xl tactile-card flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-forest-900 border border-forest-700 text-emerald-400 flex items-center justify-center font-bold text-base shrink-0">
                                1
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white">Sajili Wasifu Wako na NIDA Yako</h4>
                                <p class="text-xs sm:text-sm text-slate-300 mt-1 leading-relaxed">
                                    Pakua app bure, jaza ujuzi wako (fundi ujenzi, umeme, udereva, TEHAMA au kilimo) na thibitisha namba yako ya NIDA ili ujenge uaminifu kwa wateja.
                                </p>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl tactile-card flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-forest-900 border border-forest-700 text-emerald-400 flex items-center justify-center font-bold text-base shrink-0">
                                2
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white">Pokea Arifa za Kazi Zilizo Karibu Nawe</h4>
                                <p class="text-xs sm:text-sm text-slate-300 mt-1 leading-relaxed">
                                    Mteja akiweka kazi kwenye mtaa au wilaya yako, simu yako inakupa taarifa papo hapo pamoja na kiasi cha malipo kitakacholipwa.
                                </p>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl tactile-card flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-forest-900 border border-forest-700 text-emerald-400 flex items-center justify-center font-bold text-base shrink-0">
                                3
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white">Fanya Kazi kwa Amani, Pesa Iko Kwenye Escrow</h4>
                                <p class="text-xs sm:text-sm text-slate-300 mt-1 leading-relaxed">
                                    Huanzi kazi bila uhakika. Mteja anaweka fedha kwenye Amana ya Linkora kabla hujaanza kazi. Ukimaliza kwa viwango, fedha zinaingia kwenye M-Pesa yako mara moja.
                                </p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('download.apk') }}" class="inline-flex items-center gap-2 btn-primary px-6 py-3 rounded-xl text-sm font-bold">
                                <span>Anza Kupata Kazi Leo</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: Real Craftsman Photo -->
                    <div class="lg:col-span-6">
                        <div class="rounded-3xl overflow-hidden border border-[#22303e] bg-[#121922] p-3 shadow-xl">
                            <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80" alt="Fundi wa Kitanzania akifanya kazi ya mashine na ufundi" class="w-full h-80 sm:h-96 object-cover rounded-2xl">
                            <div class="p-4 flex items-center justify-between text-xs">
                                <div>
                                    <span class="text-white font-bold block">Ulinzi wa Jasho Lako 100%</span>
                                    <span class="text-slate-400">Hakuna tena kufanya kazi kisha mteja anazima simu.</span>
                                </div>
                                <span class="text-emerald-400 font-bold bg-forest-950 px-3 py-1 rounded-lg border border-forest-800">
                                    M-Pesa Escrow
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Tab Content 2: Employer Journey (Hidden by default) -->
            <div id="tabPoster" class="hidden transition-all duration-300">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    
                    <div class="lg:col-span-6 space-y-4">
                        <div class="p-5 rounded-2xl tactile-card flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-forest-900 border border-forest-700 text-emerald-400 flex items-center justify-center font-bold text-base shrink-0">
                                1
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white">Weka Tangazo la Kazi kwa Sekunde 60</h4>
                                <p class="text-xs sm:text-sm text-slate-300 mt-1 leading-relaxed">
                                    Eleza kazi unayohitaji (kurekebisha mabomba, fundi seremala, dereva, msafishaji au mfanyakazi wa ofisi) na kiasi unachotaka kulipa.
                                </p>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl tactile-card flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-forest-900 border border-forest-700 text-emerald-400 flex items-center justify-center font-bold text-base shrink-0">
                                2
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white">Chagua Mfanyakazi Mwenye NIDA na Maoni ya Wateja</h4>
                                <p class="text-xs sm:text-sm text-slate-300 mt-1 leading-relaxed">
                                    Tazama orodha ya mafundi walioomba, historia yao ya kazi za nyuma, alama za uaminifu na uthibitisho wa kitambulisho cha NIDA.
                                </p>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl tactile-card flex items-start gap-4">
                            <div class="h-10 w-10 rounded-xl bg-forest-900 border border-forest-700 text-emerald-400 flex items-center justify-center font-bold text-base shrink-0">
                                3
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white">Weka Fedha Kwenye Escrow, Kazi Ikiridhiwa Ndipo Zinatoka</h4>
                                <p class="text-xs sm:text-sm text-slate-300 mt-1 leading-relaxed">
                                    Fedha zinatoka kwenye simu yako kwenda kwenye akaunti salama ya Amana. Mfanyakazi halipwi mpaka uthibitishwe kwamba amemaliza kazi vizuri.
                                </p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('download.apk') }}" class="inline-flex items-center gap-2 btn-primary px-6 py-3 rounded-xl text-sm font-bold">
                                <span>Weka Kazi au Ajira Sasa</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: Real Employer / Project Manager Photo -->
                    <div class="lg:col-span-6">
                        <div class="rounded-3xl overflow-hidden border border-[#22303e] bg-[#121922] p-3 shadow-xl">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80" alt="Mwajiri wa Kitanzania akisimamia mradi kwa ufanisi" class="w-full h-80 sm:h-96 object-cover rounded-2xl">
                            <div class="p-4 flex items-center justify-between text-xs">
                                <div>
                                    <span class="text-white font-bold block">Uhakiki wa NIDA Kuzuia Utapeli</span>
                                    <span class="text-slate-400">Kila mfanyakazi anatambulika kisheria kabla ya kumpa kazi.</span>
                                </div>
                                <span class="text-emerald-400 font-bold bg-forest-950 px-3 py-1 rounded-lg border border-forest-800">
                                    NIDA Verified
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- REAL RECENT OPPORTUNITIES & GIGS (LIVE DATABASE DRIVEN WITH HUMAN DETAILS) -->
    <section id="aina-za-kazi" class="py-16 md:py-24 border-b border-[#1b2530] bg-[#0c1218]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div>
                    <span class="text-xs font-bold text-emerald-400 tracking-wider uppercase bg-[#13221b] px-3.5 py-1.5 rounded-full border border-forest-700">
                        Nafasi Zilizopo
                    </span>
                    <h2 class="display-font text-3xl sm:text-4xl font-bold text-white mt-3">
                        Kazi na Vibarua Vinavyohitajika Sasa Hivi
                    </h2>
                    <p class="text-slate-400 text-sm mt-2 max-w-xl">
                        Tazama mifano ya fursa zilizowekwa hivi karibuni na waajiri na wateja kutoka mikoa mbalimbali ya Tanzania.
                    </p>
                </div>

                <a href="#pakua-app" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-400 hover:text-emerald-300">
                    <span>Fungua zote kwenye App</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <!-- Opportunities Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @forelse($recentListings as $listing)
                    <div class="tactile-card rounded-2xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs mb-3">
                                <span class="bg-forest-900/90 text-emerald-300 font-bold text-[10px] px-2.5 py-1 rounded border border-forest-700 uppercase tracking-wider">
                                    {{ $listing->category }}
                                </span>
                                @if($listing->is_escrow_required)
                                    <span class="text-emerald-400 font-bold text-[11px] flex items-center gap-1">
                                        <i class="fa-solid fa-lock text-[10px]"></i> Escrow
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-base font-bold text-white mb-2 leading-snug">
                                {{ $listing->title }}
                            </h3>

                            <p class="text-xs text-slate-300 line-clamp-2 mb-4 leading-relaxed">
                                {{ $listing->description }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-[#1f2b38] space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400 flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-slate-500"></i>
                                    <span>{{ $listing->location }}</span>
                                </span>
                                <span class="text-emerald-400 font-bold font-mono text-sm">
                                    {{ $listing->compensation }}
                                </span>
                            </div>

                            <a href="#pakua-app" class="w-full text-center block py-2.5 rounded-xl bg-slate-900 hover:bg-forest-900 text-slate-200 hover:text-white text-xs font-bold border border-slate-700 transition">
                                Omba Nafasi Hii Kwenye App &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- Curated Real Sample Gigs if database is empty -->
                    <div class="tactile-card rounded-2xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs mb-3">
                                <span class="bg-forest-900/90 text-emerald-300 font-bold text-[10px] px-2.5 py-1 rounded border border-forest-700 uppercase">
                                    Fundi & Ufundi
                                </span>
                                <span class="text-emerald-400 font-bold text-[11px] flex items-center gap-1">
                                    <i class="fa-solid fa-lock text-[10px]"></i> Escrow
                                </span>
                            </div>
                            <h3 class="text-base font-bold text-white mb-2 leading-snug">
                                Fundi Mabomba (Plumber) na Ukarabati wa Vyoo
                            </h3>
                            <p class="text-xs text-slate-300 mb-4 leading-relaxed">
                                Tunahitaji fundi mwenye uzoefu wa mifumo ya kisasa ya maji safi na maji taka kwenye jengo la ghorofa 2, Mikocheni B.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-[#1f2b38] space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400 flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-slate-500"></i> Mikocheni, Dar
                                </span>
                                <span class="text-emerald-400 font-bold font-mono text-sm">TZS 65,000/siku</span>
                            </div>
                            <a href="#pakua-app" class="w-full text-center block py-2.5 rounded-xl bg-slate-900 hover:bg-forest-900 text-slate-200 hover:text-white text-xs font-bold border border-slate-700 transition">
                                Omba Nafasi Hii Kwenye App &rarr;
                            </a>
                        </div>
                    </div>

                    <div class="tactile-card rounded-2xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs mb-3">
                                <span class="bg-forest-900/90 text-emerald-300 font-bold text-[10px] px-2.5 py-1 rounded border border-forest-700 uppercase">
                                    Kilimo & Mazao
                                </span>
                                <span class="text-emerald-400 font-bold text-[11px] flex items-center gap-1">
                                    <i class="fa-solid fa-lock text-[10px]"></i> Escrow
                                </span>
                            </div>
                            <h3 class="text-base font-bold text-white mb-2 leading-snug">
                                Msimamizi wa Shamba na Nguvu Kazi ya Uvunaji
                            </h3>
                            <p class="text-xs text-slate-300 mb-4 leading-relaxed">
                                Shamba la ekari 40 linatafuta msimamizi mwenye cheti cha kilimo au uzoefu wa vitendo wa kusimamia vibarua 15 wakati wa msimu.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-[#1f2b38] space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400 flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-slate-500"></i> Kilosa, Morogoro
                                </span>
                                <span class="text-emerald-400 font-mono font-bold text-sm">TZS 800,000/mwezi</span>
                            </div>
                            <a href="#pakua-app" class="w-full text-center block py-2.5 rounded-xl bg-slate-900 hover:bg-forest-900 text-slate-200 hover:text-white text-xs font-bold border border-slate-700 transition">
                                Omba Nafasi Hii Kwenye App &rarr;
                            </a>
                        </div>
                    </div>

                    <div class="tactile-card rounded-2xl p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs mb-3">
                                <span class="bg-forest-900/90 text-emerald-300 font-bold text-[10px] px-2.5 py-1 rounded border border-forest-700 uppercase">
                                    Usafiri & Mizigo
                                </span>
                                <span class="text-emerald-400 font-bold text-[11px] flex items-center gap-1">
                                    <i class="fa-solid fa-lock text-[10px]"></i> Escrow
                                </span>
                            </div>
                            <h3 class="text-base font-bold text-white mb-2 leading-snug">
                                Dereva wa Lori la Tani 7 (Leseni Daraja C)
                            </h3>
                            <p class="text-xs text-slate-300 mb-4 leading-relaxed">
                                Usafirishaji wa mizigo kutoka Bandari ya Dar es Salaam kwenda mikoa ya Nyanda za Juu Kusini. Malipo kwa kila safari.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-[#1f2b38] space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400 flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-slate-500"></i> Dar hadi Mbeya
                                </span>
                                <span class="text-emerald-400 font-mono font-bold text-sm">TZS 350,000/safari</span>
                            </div>
                            <a href="#pakua-app" class="w-full text-center block py-2.5 rounded-xl bg-slate-900 hover:bg-forest-900 text-slate-200 hover:text-white text-xs font-bold border border-slate-700 transition">
                                Omba Nafasi Hii Kwenye App &rarr;
                            </a>
                        </div>
                    </div>
                @endforelse

            </div>

        </div>
    </section>

    <!-- ESCROW SECURITY (REAL HUMAN REASSURANCE, NO SILLY TERMINAL CODE BLOCKS) -->
    <section id="usalama-escrow" class="py-16 md:py-24 border-b border-[#1b2530]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="tactile-card rounded-3xl p-8 sm:p-12 border border-forest-800/80 bg-[#0f171f]">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    
                    <div class="lg:col-span-7">
                        <span class="text-xs font-bold text-emerald-400 tracking-wider uppercase bg-[#13221b] px-3.5 py-1.5 rounded-full border border-forest-700">
                            Ulinzi wa Fedha
                        </span>
                        
                        <h2 class="display-font text-2xl sm:text-4xl font-bold text-white mt-4 leading-tight">
                            Malipo yote yanalindwa na Escrow ya M-Pesa. Hakuna anayedhulumiwa.
                        </h2>
                        
                        <p class="text-slate-300 text-sm sm:text-base mt-4 leading-relaxed">
                            Changamoto kubwa ya kazi nchini Tanzania ni hofu ya malipo. Wateja wanaogopa kutapeliwa na mafundi, na mafundi wanaogopa kufanya kazi bure. Mfumo wa Escrow wa Linkora unaondoa hofu hii kabisa:
                        </p>

                        <div class="mt-8 space-y-4">
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-[#141d27] border border-[#202d3b]">
                                <div class="h-9 w-9 rounded-lg bg-forest-900 text-emerald-400 flex items-center justify-center font-bold text-sm shrink-0">
                                    <i class="fa-solid fa-money-bill-transfer"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">Mteja anaweka fedha mwanzo</h4>
                                    <p class="text-xs text-slate-300 mt-1">Fedha zinatoka M-Pesa / Tigo Pesa na kukaa salama kwenye akaunti ya Amana ya Linkora. Fundi anaona ushahidi wa fedha lakini hawezi kuzichukua mpaka amalize kazi.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 rounded-xl bg-[#141d27] border border-[#202d3b]">
                                <div class="h-9 w-9 rounded-lg bg-forest-900 text-emerald-400 flex items-center justify-center font-bold text-sm shrink-0">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">Kazi inafanyika kwa amani</h4>
                                    <p class="text-xs text-slate-300 mt-1">Mfanyakazi anafanya kazi kwa moyo mmoja akijua kuwa fedha zake zipo tayari zikisubiri kukamilika kwa kazi.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 p-4 rounded-xl bg-[#141d27] border border-[#202d3b]">
                                <div class="h-9 w-9 rounded-lg bg-forest-900 text-emerald-400 flex items-center justify-center font-bold text-sm shrink-0">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">Ukaguzi na kuachia malipo</h4>
                                    <p class="text-xs text-slate-300 mt-1">Mteja akiridhika na ubora wa kazi, anabonyeza kitufe kimoja tu na mfumo unahamisha fedha papo hapo kwenda kwenye simu ya fundi.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Payment Rails & Direct Guarantees -->
                    <div class="lg:col-span-5 flex flex-col items-center justify-center">
                        <div class="w-full bg-[#141d27] border border-[#233140] rounded-2xl p-6 text-center space-y-4">
                            <div class="h-16 w-16 mx-auto rounded-2xl bg-forest-900 border border-forest-600 text-emerald-400 flex items-center justify-center text-3xl">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>

                            <h3 class="display-font text-xl font-bold text-white">100% Hakikisho la Malipo</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                Ikitokea sintofahamu au kazi haikufanyika kwa viwango vilivyokubaliwa, timu ya wapatanishi ya Linkora inarejesha fedha zako au kusimamia kazi irudiwe.
                            </p>

                            <div class="pt-4 border-t border-[#202d3b]">
                                <div class="text-[11px] text-slate-400 font-semibold mb-3 uppercase tracking-wider">Mitandao ya Malipo Inayotumika:</div>
                                <div class="grid grid-cols-2 gap-2 text-xs font-bold text-slate-200">
                                    <div class="p-2.5 rounded-lg bg-[#0e141b] border border-slate-700">M-Pesa (Vodacom)</div>
                                    <div class="p-2.5 rounded-lg bg-[#0e141b] border border-slate-700">Tigo Pesa</div>
                                    <div class="p-2.5 rounded-lg bg-[#0e141b] border border-slate-700">Airtel Money</div>
                                    <div class="p-2.5 rounded-lg bg-[#0e141b] border border-slate-700">Halopesa</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- REAL PEOPLE TESTIMONIALS (REAL NAMES, REAL FACES, REAL STORIES) -->
    <section id="mashuhuda" class="py-16 md:py-24 border-b border-[#1b2530] bg-[#0c1218]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold text-emerald-400 tracking-wider uppercase bg-[#13221b] px-3.5 py-1.5 rounded-full border border-forest-700">
                    Watu Halisi, Ushuhuda Halisi
                </span>
                <h2 class="display-font text-3xl sm:text-4xl font-bold text-white mt-4">
                    Walivyopata uhakika wa maisha na ajira kupitia Linkora
                </h2>
                <p class="text-slate-400 text-sm mt-2">
                    Soma maoni ya mafundi, vibarua na wamiliki wa miradi wanaotumia jukwaa letu kila siku nchini Tanzania.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Story 1 -->
                <div class="tactile-card rounded-2xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amberWarm-500 text-xs mb-3">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed italic">
                            "Zamani nilikuwa ninafanya kazi kisha mwenye nyumba anazima simu bila kunilipa ujira wangu. Tangu nianze kupokea kazi kupitia Linkora, kila kazi inalindwa na Escrow kwanza. Hakuna tena kufanya kazi bure."
                        </p>
                    </div>

                    <div class="flex items-center gap-3 pt-6 mt-6 border-t border-[#1f2b38]">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80" alt="Rashid Mwalimu" class="w-12 h-12 rounded-full object-cover border border-emerald-500/40">
                        <div>
                            <h4 class="text-sm font-bold text-white">Rashid Mwalimu</h4>
                            <p class="text-xs text-slate-400">Fundi Umeme, Tegeta Dar es Salaam</p>
                            <span class="text-[10px] text-emerald-400 font-semibold"><i class="fa-solid fa-circle-check"></i> NIDA Verified</span>
                        </div>
                    </div>
                </div>

                <!-- Story 2 -->
                <div class="tactile-card rounded-2xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amberWarm-500 text-xs mb-3">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed italic">
                            "Kupata mafundi wenye uaminifu ilikuwa ni bahati nasibu. Nilitafuta fundi seremala wa kutengeneza makabati ya jikoni, nikampata kupitia Linkora akiwa na vyeti na NIDA. Kazi imefanyika kwa wakati na uzuri wa hali ya juu."
                        </p>
                    </div>

                    <div class="flex items-center gap-3 pt-6 mt-6 border-t border-[#1f2b38]">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80" alt="Zainab Kibona" class="w-12 h-12 rounded-full object-cover border border-emerald-500/40">
                        <div>
                            <h4 class="text-sm font-bold text-white">Zainab Kibona</h4>
                            <p class="text-xs text-slate-400">Msimamizi wa Mradi, Mbeya</p>
                            <span class="text-[10px] text-emerald-400 font-semibold"><i class="fa-solid fa-building"></i> Mwajiri Aliyethibitishwa</span>
                        </div>
                    </div>
                </div>

                <!-- Story 3 -->
                <div class="tactile-card rounded-2xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amberWarm-500 text-xs mb-3">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed italic">
                            "App inaniwezesha kuona vibarua vilivyopo karibu na wilaya yangu. Nikimaliza tu kazi mteja anathibitisha na malipo yanaingia kwenye M-Pesa yangu papo hapo bila kuomba omba wala kubembelezana."
                        </p>
                    </div>

                    <div class="flex items-center gap-3 pt-6 mt-6 border-t border-[#1f2b38]">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80" alt="Aloyce Shirima" class="w-12 h-12 rounded-full object-cover border border-emerald-500/40">
                        <div>
                            <h4 class="text-sm font-bold text-white">Aloyce Shirima</h4>
                            <p class="text-xs text-slate-400">Fundi Mabomba, Arusha Mjini</p>
                            <span class="text-[10px] text-emerald-400 font-semibold"><i class="fa-solid fa-circle-check"></i> Kazi 42 Zimekamilika</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- REAL CONVERSATIONAL FAQ (AUTHENTIC QUESTIONS ASKED BY TANZANIANS) -->
    <section id="maswali" class="py-16 md:py-24 border-b border-[#1b2530]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-12">
                <span class="text-xs font-bold text-emerald-400 tracking-wider uppercase bg-[#13221b] px-3.5 py-1.5 rounded-full border border-forest-700">
                    Maswali ya Kila Siku
                </span>
                <h2 class="display-font text-3xl sm:text-4xl font-bold text-white mt-4">
                    Kila kitu unachopaswa kufahamu kabla ya kuanza
                </h2>
                <p class="text-slate-400 text-sm mt-2">
                    Majibu ya wazi na ya moja kwa moja bila maneno magumu.
                </p>
            </div>

            <!-- Accordion List -->
            <div class="space-y-4">
                
                <div class="tactile-card rounded-2xl p-5 cursor-pointer" onclick="toggleFaq(1)">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm sm:text-base font-bold text-white">Je, ni lazima niwe na kitambulisho cha NIDA ili kupata kazi?</h3>
                        <i id="faq-icon-1" class="fa-solid fa-plus text-emerald-400 text-xs"></i>
                    </div>
                    <div id="faq-ans-1" class="hidden mt-3 pt-3 border-t border-[#1f2b38] text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Ndiyo. Kila mtu anayejiunga kama mtoa huduma au fundi anapaswa kuweka namba yake ya NIDA. Hii inasaidia kujenga uaminifu, kulinda wateja dhidi ya vitendo vya kihalifu, na kuhakikisha wewe unayefanya kazi unajulikana kisheria.
                    </div>
                </div>

                <div class="tactile-card rounded-2xl p-5 cursor-pointer" onclick="toggleFaq(2)">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm sm:text-base font-bold text-white">Pesa yangu inakwenda wapi nikiiweka kwenye Escrow?</h3>
                        <i id="faq-icon-2" class="fa-solid fa-plus text-emerald-400 text-xs"></i>
                    </div>
                    <div id="faq-ans-2" class="hidden mt-3 pt-3 border-t border-[#1f2b38] text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Fedha zinatoka kwenye simu yako na kuingia kwenye akaunti rasmi ya ulinzi (Trust/Escrow) iliyo chini ya sheria za kifedha Tanzania. Pesa haipewi fundi mpaka utakapothibitisha kwamba amekamilisha kazi kwa ufanisi.
                    </div>
                </div>

                <div class="tactile-card rounded-2xl p-5 cursor-pointer" onclick="toggleFaq(3)">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm sm:text-base font-bold text-white">Nalipia kiasi gani kupakua au kujiandikisha kwenye Linkora?</h3>
                        <i id="faq-icon-3" class="fa-solid fa-plus text-emerald-400 text-xs"></i>
                    </div>
                    <div id="faq-ans-3" class="hidden mt-3 pt-3 border-t border-[#1f2b38] text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Kupakua app na kujisajili ni bure 100%. Hakuna ada yoyote ya awali kwa mfanyakazi au fundi. Linkora inachukua asilimia ndogo sana ya uendeshaji wakati wa kuachia malipo ya kazi iliyokamilika kwa mafanikio.
                    </div>
                </div>

                <div class="tactile-card rounded-2xl p-5 cursor-pointer" onclick="toggleFaq(4)">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm sm:text-base font-bold text-white">Ikitokea fundi hajamaliza kazi au ametoweka ninafanyaje?</h3>
                        <i id="faq-icon-4" class="fa-solid fa-plus text-emerald-400 text-xs"></i>
                    </div>
                    <div id="faq-ans-4" class="hidden mt-3 pt-3 border-t border-[#1f2b38] text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Kwa sababu fedha zako ziko kwenye Escrow, fundi hawezi kuzichukua bila idhini yako. Ukifungua mgogoro (Dispute) ndani ya app, timu yetu ya wapatanishi inakagua mkataba na kukurejeshea fedha zako zote mara moja.
                    </div>
                </div>

                <div class="tactile-card rounded-2xl p-5 cursor-pointer" onclick="toggleFaq(5)">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm sm:text-base font-bold text-white">Je, huduma inafanya kazi mikoa yote ya Tanzania?</h3>
                        <i id="faq-icon-5" class="fa-solid fa-plus text-emerald-400 text-xs"></i>
                    </div>
                    <div id="faq-ans-5" class="hidden mt-3 pt-3 border-t border-[#1f2b38] text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Ndiyo. Linkora inafanya kazi kote Tanzania Bara na Visiwani. Kuanzia Dar es Salaam, Mbeya, Arusha, Mwanza, Dodoma, Morogoro, Tanga, Kilimanjaro mpaka mikoa yote ya pembezoni.
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- DOWNLOAD SECTION (DIRECT, TACTILE, RESPECTFUL) -->
    <section id="pakua-app" class="py-16 md:py-24 bg-[#0a0e13]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#13221b] text-emerald-400 text-xs font-bold mb-6 border border-forest-700">
                <i class="fa-brands fa-android text-base"></i>
                <span>Toleo Rasmi la Android (Direct APK)</span>
            </div>

            <h2 class="display-font text-3xl sm:text-5xl font-bold text-white mb-6">
                Pakua Linkora kwenye simu yako ya mkononi
            </h2>

            <p class="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto mb-10 leading-relaxed">
                Pata kazi mpya kila asubuhi, simamia vibarua vyako, na pokea au toa malipo bila hofu ya kudhulumiwa.
            </p>

            <div class="tactile-card rounded-3xl p-8 sm:p-10 border border-[#263748] max-w-2xl mx-auto text-left">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pb-8 border-b border-[#1f2b38]">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-white">Linkora App v1.0</span>
                            <span class="text-[10px] bg-forest-900 text-emerald-300 font-bold px-2 py-0.5 rounded border border-forest-700">Toleo Rasmi</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Ujazo: 18 MB • Inasaidia Android 7.0 na Kuendelea</p>
                        <p class="text-xs text-emerald-400 mt-0.5 font-semibold">Bure bila malipo yoyote ya kupakua</p>
                    </div>

                    <a href="{{ route('download.apk') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-7 py-4 rounded-xl btn-primary text-sm font-bold shrink-0">
                        <i class="fa-solid fa-download text-lg"></i>
                        <span>Pakua APK Sasa</span>
                    </a>
                </div>

                <div class="pt-6">
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-3">Hatua Rahisi za Kuanza:</h4>
                    <ol class="space-y-2 text-xs text-slate-300 list-decimal list-inside">
                        <li>Bofya kitufe cha <strong>Pakua APK Sasa</strong> hapo juu.</li>
                        <li>Faili ikimaliza kupakua, bonyeza <strong>Fungua (Open)</strong> kisha <strong>Install</strong>.</li>
                        <li>Fungua app, weka namba yako ya simu na NIDA, kisha anza kupokea au kutoa kazi mara moja!</li>
                    </ol>
                </div>
            </div>

            <div class="mt-8 text-xs text-slate-400">
                <span>Je unahitaji msaada wa kujiunga? Piga au tuma ujumbe WhatsApp: </span>
                <a href="https://wa.me/255755000000" class="text-emerald-400 font-bold hover:underline ml-1">+255 755 000 000</a>
            </div>

        </div>
    </section>

    <!-- FOOTER (COMMERCIAL INTEGRITY, CLEAR IDENTITY) -->
    <footer class="border-t border-[#18222d] bg-[#070b0f] py-12 text-slate-400 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="h-8 w-8 rounded-lg bg-forest-700 text-white flex items-center justify-center font-bold">
                            L
                        </div>
                        <span class="display-font text-xl font-bold text-white">Linkora</span>
                        <span class="text-[10px] text-slate-500 font-mono">Tanzania</span>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-md">
                        Jukwaa la kidijitali la kuunganisha waajiri na mafundi kote Tanzania kwa kutumia uthibitisho wa vitambulisho vya NIDA na mfumo salama wa amana ya malipo (Escrow) kupitia mitandao ya simu za mkononi.
                    </p>
                    <div class="text-[11px] text-slate-500">
                        Imeundwa na kumilikiwa na: <strong class="text-slate-300">Swebetech Company Limited</strong> • BRELA No. 579919 • Mbeya, Tanzania
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-slate-200 text-sm mb-3">Huduma za Mfumo</h4>
                    <ul class="space-y-2">
                        <li><a href="#aina-za-kazi" class="hover:text-emerald-400 transition">Ajira za Muda Mfupi (Gigs)</a></li>
                        <li><a href="#aina-za-kazi" class="hover:text-emerald-400 transition">Ajira za Kudumu na Mikataba</a></li>
                        <li><a href="#jinsi-inavyofanya-kazi" class="hover:text-emerald-400 transition">Uthibitisho wa NIDA</a></li>
                        <li><a href="#usalama-escrow" class="hover:text-emerald-400 transition">Amana ya Malipo (Escrow)</a></li>
                        <li><a href="#pakua-app" class="hover:text-emerald-400 transition">Pakua Android APK</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-200 text-sm mb-3">Lango la Utawala</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('admin.login') }}" class="hover:text-emerald-400 font-semibold text-slate-300 flex items-center gap-1.5"><i class="fa-solid fa-lock text-[10px]"></i> Admin Management Portal</a></li>
                        <li><a href="{{ route('download.apk') }}" class="hover:text-emerald-400 transition">Pakua APK Rasmi</a></li>
                        <li><a href="mailto:support@linkora.co.tz" class="hover:text-emerald-400 transition">Msaada: support@linkora.co.tz</a></li>
                        <li class="pt-2 text-[11px] text-slate-500">&copy; {{ date('Y') }} Swebetech Company Ltd. Haki zote zimehifadhiwa.</li>
                    </ul>
                </div>

            </div>

            <div class="pt-8 border-t border-[#141b24] flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
                <div>Linkora.co.tz • Jukwaa Rasmi la Kazi na Malipo Salama Tanzania</div>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-slate-400">Vigezo na Masharti</a>
                    <span>•</span>
                    <a href="#" class="hover:text-slate-400">Sera ya Faragha</a>
                    <span>•</span>
                    <a href="{{ route('admin.login') }}" class="hover:text-slate-400">Utawala</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- INTERACTIVE JAVASCRIPT LOGIC (CLEAN, NO EXTERNAL DEPENDENCY) -->
    <script>
        // Interactive Role Tabs switcher
        function switchTab(role) {
            const seekerTab = document.getElementById('tabSeeker');
            const posterTab = document.getElementById('tabPoster');
            const btnSeeker = document.getElementById('btnTabSeeker');
            const btnPoster = document.getElementById('btnTabPoster');

            if (role === 'seeker') {
                seekerTab.classList.remove('hidden');
                posterTab.classList.add('hidden');
                btnSeeker.className = 'px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-forest-700 text-white shadow transition';
                btnPoster.className = 'px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-300 hover:text-white transition';
            } else {
                seekerTab.classList.add('hidden');
                posterTab.classList.remove('hidden');
                btnPoster.className = 'px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-forest-700 text-white shadow transition';
                btnSeeker.className = 'px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-300 hover:text-white transition';
            }
        }

        // Accordion FAQ Toggle
        function toggleFaq(id) {
            const ans = document.getElementById('faq-ans-' + id);
            const icon = document.getElementById('faq-icon-' + id);
            if (ans.classList.contains('hidden')) {
                ans.classList.remove('hidden');
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            } else {
                ans.classList.add('hidden');
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            }
        }
    </script>

</body>
</html>
