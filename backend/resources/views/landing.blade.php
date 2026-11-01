<!DOCTYPE html>
<html lang="sw" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkora | Jukwaa Mahiri la Ajira za Muda Mfupi na Muda Mrefu Tanzania (AI & Escrow)</title>
    <meta name="description" content="Linkora inakuunganisha papo hapo na ajira za muda mfupi (vibarua/gigs) na ajira za muda mrefu kote Tanzania. Inaendeshwa na Linkora AI, uthibitisho wa NIDA, na malipo salama ya Escrow kupitia M-Pesa.">
    <meta name="keywords" content="Linkora, Ajira Tanzania, Vibarua Dar es Salaam, Kazi za Muda Mfupi, Ajira za Muda Mrefu, Mafundi NIDA, Escrow M-Pesa, AI Job Matching Tanzania">
    
    <!-- OpenGraph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://linkora.co.tz">
    <meta property="og:title" content="Linkora - Fursa, Ajira na Vibarua kwa Uhakika na Usalama">
    <meta property="og:description" content="Tafuta au toa ajira za muda mfupi na mrefu Tanzania. Ulinzi wa amana (Escrow), NIDA verification, na Linkora AI matching.">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        accent: {
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        darkBg: '#090d16',
                        darkCard: '#111827',
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .heading-font {
            font-family: 'Space Grotesk', sans-serif;
        }
        .mesh-gradient {
            background-color: #090d16;
            background-image: 
                radial-gradient(at 10% 20%, rgba(16, 185, 129, 0.15) 0px, transparent 50%),
                radial-gradient(at 90% 10%, rgba(59, 130, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 50% 80%, rgba(16, 185, 129, 0.08) 0px, transparent 50%);
        }
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-panel-hover:hover {
            border-color: rgba(16, 185, 129, 0.4);
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -10px rgba(16, 185, 129, 0.15);
        }
        .glow-emerald {
            box-shadow: 0 0 40px -10px rgba(16, 185, 129, 0.4);
        }
    </style>
</head>
<body class="bg-darkBg text-slate-100 antialiased selection:bg-brand-500 selection:text-white">

    <!-- TOP ANNOUNCEMENT BANNER -->
    <div class="bg-gradient-to-r from-emerald-900/90 via-teal-900/80 to-blue-900/90 border-b border-emerald-500/20 py-2.5 px-4 text-xs md:text-sm text-center text-emerald-200">
        <span class="inline-flex items-center gap-2 font-medium">
            <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span>Karibu kwenye mfumo mpya wa <strong>Linkora v1.0</strong> — Jukwaa la kidijitali la ajira, vibarua na fursa za kiuchumi Tanzania!</span>
            <a href="#pakua-app" class="underline font-bold text-white hover:text-emerald-300 ml-1">Pakua APK ya Android &rarr;</a>
        </span>
    </div>

    <!-- MAIN NAVBAR -->
    <header class="sticky top-0 z-50 glass-panel border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="h-11 w-11 rounded-xl bg-gradient-to-tr from-brand-600 via-emerald-500 to-teal-400 p-0.5 shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <div class="h-full w-full bg-slate-950 rounded-[10px] flex items-center justify-center">
                        <i class="fa-solid fa-link text-brand-500 text-lg group-hover:rotate-45 transition-transform duration-300"></i>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="heading-font text-2xl font-bold tracking-tight text-white">Linkora</span>
                        <span class="text-[10px] font-bold bg-brand-500/20 text-brand-400 px-1.5 py-0.5 rounded border border-brand-500/30">.CO.TZ</span>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium tracking-wide">Fursa • Ajira • Uaminifu</p>
                </div>
            </a>

            <!-- Desktop Nav Items -->
            <nav class="hidden lg:flex items-center gap-8 text-sm font-medium text-slate-300">
                <a href="#kuhusu" class="hover:text-brand-400 transition-colors">Kuhusu Linkora</a>
                <a href="#aina-za-ajira" class="hover:text-brand-400 transition-colors">Ajira & Vibarua</a>
                <a href="#ai-teknolojia" class="hover:text-brand-400 transition-colors">Linkora AI</a>
                <a href="#escrow-usalama" class="hover:text-brand-400 transition-colors">Escrow (Amana)</a>
                <a href="#pakua-app" class="hover:text-brand-400 transition-colors">Pakua App</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.login') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-slate-300 hover:text-white bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 transition">
                    <i class="fa-solid fa-shield-halved text-brand-400"></i>
                    <span>Admin Portal</span>
                </a>
                
                <a href="#pakua-app" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-950 bg-gradient-to-r from-brand-400 via-emerald-400 to-teal-300 hover:opacity-95 shadow-lg shadow-emerald-500/20 transition hover:scale-105 duration-200">
                    <i class="fa-brands fa-android text-base"></i>
                    <span>Pakua APK</span>
                </a>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="relative mesh-gradient pt-16 pb-24 md:pt-24 md:pb-32 overflow-hidden">
        <!-- Floating Light Highlights -->
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-brand-500/10 blur-[130px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left Column: Value Proposition -->
                <div class="lg:col-span-7 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold mb-6">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>AI-Powered Opportunity Ecosystem Tanzania</span>
                    </div>

                    <h1 class="heading-font text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.15] mb-6">
                        Pata Ajira za <span class="bg-gradient-to-r from-brand-400 via-emerald-300 to-teal-200 bg-clip-text text-transparent">Muda Mfupi</span> na <span class="bg-gradient-to-r from-blue-400 via-indigo-300 to-teal-300 bg-clip-text text-transparent">Muda Mrefu</span> kwa Uhakika.
                    </h1>

                    <p class="text-base sm:text-lg text-slate-300 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                        Linkora inawaunganisha waajiri, mafundi, wataalamu na vibarua kote Tanzania. Inaendeshwa na <strong>Linkora AI</strong> kwa ulinganifu wa ujuzi, <strong>NIDA</strong> kwa uthibitisho wa utambulisho, na mfumo salama wa <strong>Escrow (M-Pesa)</strong> unaolinda fedha zako mpaka kazi ikamilike.
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-10">
                        <a href="{{ route('download.apk') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-7 py-4 rounded-2xl font-bold text-slate-950 bg-gradient-to-r from-brand-400 via-emerald-400 to-teal-300 hover:from-brand-300 hover:to-teal-200 shadow-xl shadow-brand-500/25 transition duration-200 transform hover:-translate-y-0.5">
                            <i class="fa-brands fa-android text-2xl"></i>
                            <div class="text-left">
                                <div class="text-[10px] uppercase font-bold text-slate-800 leading-none">Pakua Sasa (Bure)</div>
                                <div class="text-base font-extrabold text-slate-950 leading-tight">Linkora APK (v1.0)</div>
                            </div>
                        </a>

                        <a href="#aina-za-ajira" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-4 rounded-2xl font-semibold text-slate-200 bg-slate-800/90 hover:bg-slate-700/90 border border-slate-700 transition">
                            <i class="fa-solid fa-briefcase text-emerald-400"></i>
                            <span>Tazama Fursa Zilizopo</span>
                        </a>
                    </div>

                    <!-- Trust Pillars Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-6 border-t border-slate-800/80">
                        <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800 text-left">
                            <div class="flex items-center gap-2 text-emerald-400 text-xs font-bold mb-1">
                                <i class="fa-solid fa-id-card"></i> NIDA Verified
                            </div>
                            <div class="text-[11px] text-slate-400">Uthibitisho wa vitambulisho vya taifa kuzuia utapeli</div>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800 text-left">
                            <div class="flex items-center gap-2 text-blue-400 text-xs font-bold mb-1">
                                <i class="fa-solid fa-shield-halved"></i> Escrow Amana
                            </div>
                            <div class="text-[11px] text-slate-400">M-Pesa inashikilia malipo mpaka kazi iridhiwe</div>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800 text-left">
                            <div class="flex items-center gap-2 text-teal-400 text-xs font-bold mb-1">
                                <i class="fa-solid fa-brain"></i> Linkora AI
                            </div>
                            <div class="text-[11px] text-slate-400">Ulinganishaji mahiri wa ujuzi na nafasi ya kazi</div>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-900/60 border border-slate-800 text-left">
                            <div class="flex items-center gap-2 text-amber-400 text-xs font-bold mb-1">
                                <i class="fa-solid fa-bolt"></i> Malipo ya Haraka
                            </div>
                            <div class="text-[11px] text-slate-400">Fedha zinaingia moja kwa moja kazi ikikamilika</div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive Phone Mockup Preview -->
                <div class="lg:col-span-5 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-[340px]">
                        <!-- Decorative glow -->
                        <div class="absolute -inset-1.5 bg-gradient-to-r from-brand-500 to-blue-600 rounded-[44px] blur-xl opacity-30"></div>
                        
                        <!-- Phone frame -->
                        <div class="relative bg-slate-950 border-[6px] border-slate-800 rounded-[42px] p-4 shadow-2xl overflow-hidden">
                            <!-- Speaker / Notch -->
                            <div class="w-24 h-4 bg-slate-800 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-slate-700"></div>
                            </div>

                            <!-- Mock Screen Content -->
                            <div class="space-y-3.5">
                                <!-- App Header -->
                                <div class="flex items-center justify-between bg-slate-900/90 p-3 rounded-2xl border border-slate-800">
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs">
                                            L
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-white">Linkora App</div>
                                            <div class="text-[9px] text-emerald-400 flex items-center gap-1">
                                                <i class="fa-solid fa-circle-check text-[8px]"></i> NIDA Verified
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-[10px] bg-emerald-500/20 text-emerald-300 font-semibold px-2 py-0.5 rounded-full border border-emerald-500/30">
                                        M-Pesa Escrow
                                    </span>
                                </div>

                                <!-- AI Match Banner -->
                                <div class="bg-gradient-to-br from-indigo-950/80 to-slate-900 p-3 rounded-2xl border border-indigo-500/30">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[10px] font-bold text-indigo-300 flex items-center gap-1">
                                            <i class="fa-solid fa-robot text-brand-400"></i> Linkora AI Match: 96%
                                        </span>
                                        <span class="text-[9px] bg-brand-500/20 text-brand-300 px-1.5 py-0.2 rounded font-bold">Inafaa</span>
                                    </div>
                                    <p class="text-xs font-bold text-white">Ujenzi & Umeme wa Ndani (Dar es Salaam)</p>
                                    <p class="text-[10px] text-slate-300 mt-1">Nafasi ya siku 4 • Malipo yamehifadhiwa kwenye Amana</p>
                                    <div class="mt-2.5 flex items-center justify-between text-[11px] font-bold">
                                        <span class="text-emerald-400">TZS 180,000</span>
                                        <span class="text-[9px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded">Mkataba wa Escrow</span>
                                    </div>
                                </div>

                                <!-- Gigs Feed Item 1 -->
                                <div class="bg-slate-900/70 p-3 rounded-2xl border border-slate-800/80">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="text-[9px] text-amber-400 font-bold uppercase tracking-wider">Muda Mfupi (Gig)</span>
                                            <h4 class="text-xs font-bold text-slate-100 mt-0.5">Fundi Mabomba (Plumber)</h4>
                                            <p class="text-[10px] text-slate-400">Mikocheni, Dar es Salaam</p>
                                        </div>
                                        <span class="text-xs font-bold text-emerald-400">TZS 45,000</span>
                                    </div>
                                    <div class="mt-2 flex items-center gap-1.5 text-[9px] text-slate-400">
                                        <span class="bg-slate-800 px-1.5 py-0.5 rounded text-slate-300">NIDA Imethibitishwa</span>
                                        <span class="bg-emerald-950/60 text-emerald-400 px-1.5 py-0.5 rounded">Masaa 6 yaliyopita</span>
                                    </div>
                                </div>

                                <!-- Gigs Feed Item 2 -->
                                <div class="bg-slate-900/70 p-3 rounded-2xl border border-slate-800/80">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="text-[9px] text-blue-400 font-bold uppercase tracking-wider">Muda Mrefu (Career)</span>
                                            <h4 class="text-xs font-bold text-slate-100 mt-0.5">Msimamizi wa Shamba & Mazao</h4>
                                            <p class="text-[10px] text-slate-400">Morogoro Mjini</p>
                                        </div>
                                        <span class="text-xs font-bold text-emerald-400">TZS 750,000/mwezi</span>
                                    </div>
                                </div>

                                <!-- Download Button Inside Mockup -->
                                <a href="{{ route('download.apk') }}" class="block text-center bg-brand-500 hover:bg-brand-600 text-slate-950 font-bold text-xs py-2.5 rounded-xl transition shadow">
                                    <i class="fa-solid fa-download mr-1"></i> Pakua App Kamili (APK)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- LIVE STATS BANNER -->
    <section class="border-y border-slate-800 bg-slate-950/80 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="heading-font text-2xl sm:text-4xl font-extrabold text-brand-400">{{ $stats['total_listings'] ?? '250+' }}</div>
                    <div class="text-xs sm:text-sm text-slate-400 mt-1">Fursa za Ajira na Vibarua</div>
                </div>
                <div>
                    <div class="heading-font text-2xl sm:text-4xl font-extrabold text-blue-400">{{ $stats['verified_pros'] ?? '1,200+' }}</div>
                    <div class="text-xs sm:text-sm text-slate-400 mt-1">Mafundi & Wataalamu wenye NIDA</div>
                </div>
                <div>
                    <div class="heading-font text-2xl sm:text-4xl font-extrabold text-teal-400">{{ $stats['escrow_secured'] ?? 'TZS 45M+' }}</div>
                    <div class="text-xs sm:text-sm text-slate-400 mt-1">Malipo Yaliyolindwa na Escrow</div>
                </div>
                <div>
                    <div class="heading-font text-2xl sm:text-4xl font-extrabold text-amber-400">{{ $stats['districts_covered'] ?? 'Tanzania Nzima' }}</div>
                    <div class="text-xs sm:text-sm text-slate-400 mt-1">Mikoa na Wilaya zote</div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN PURPOSE: AJIRA ZA MUDA MFUPI VS MUDA MREFU -->
    <section id="aina-za-ajira" class="py-20 md:py-28 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-bold text-brand-400 tracking-wider uppercase bg-brand-500/10 px-3 py-1 rounded-full border border-brand-500/20">
                    Aina za Ajira
                </span>
                <h2 class="heading-font text-3xl sm:text-4xl font-extrabold text-white mt-4">
                    Kazi Yoyote, Muda Wowote — Chagua Unachohitaji
                </h2>
                <p class="text-slate-400 text-sm sm:text-base mt-3">
                    Linkora imejengwa kutatua changamoto halisi ya ukosefu wa ajira na uaminifu Tanzania kwa kugawa fursa katika makundi makuu mawili:
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Card 1: Ajira za Muda Mfupi (Gigs / Vibarua) -->
                <div class="glass-panel glass-panel-hover p-8 rounded-3xl relative overflow-hidden transition-all duration-300">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl"></div>

                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-amber-500/20 text-amber-400 text-xs font-bold mb-4 border border-amber-500/30">
                        <i class="fa-solid fa-bolt"></i> Ajira za Muda Mfupi & Vibarua (Gigs)
                    </div>

                    <h3 class="heading-font text-2xl font-bold text-white mb-3">
                        Kazi za Siku, Wiki na Vibarua vya Papo Hapo
                    </h3>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        Uhitaji wa dharura wa mafundi, vibarua au wasaidizi? Linkora inakupa wataalamu walio karibu nawe waliothibitishwa kwa NIDA ndani ya dakika chache.
                    </p>

                    <ul class="space-y-3 text-sm text-slate-300 mb-8">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-brand-400 mt-1"></i>
                            <span><strong>Mafundi Ujenzi & Ukarabati:</strong> Mafundi umeme, plumbing, useremala, rangi na vibarua wa site.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-brand-400 mt-1"></i>
                            <span><strong>Kilimo & Mifugo:</strong> Vibarua wa kuvuna, kupalilia, matrekta na wasafirishaji wa mazao.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-brand-400 mt-1"></i>
                            <span><strong>Usafiri & Usambazaji:</strong> Madereva wa dharura, bodaboda za mizigo, na usafishaji wa maofisi.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-brand-400 mt-1"></i>
                            <span><strong>Ulinzi wa Malipo:</strong> Malipo yanaingizwa kwenye Escrow kwanza — mfanyakazi halipwi hadhi isipokuwa kazi ikamilike kwa ubora.</span>
                        </li>
                    </ul>

                    <a href="#pakua-app" class="inline-flex items-center gap-2 text-sm font-bold text-amber-400 hover:text-amber-300">
                        <span>Pata au Toa Kazi za Muda Mfupi</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Card 2: Ajira za Muda Mrefu & Nafasi za Wataalamu -->
                <div class="glass-panel glass-panel-hover p-8 rounded-3xl relative overflow-hidden transition-all duration-300">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl"></div>

                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-blue-500/20 text-blue-400 text-xs font-bold mb-4 border border-blue-500/30">
                        <i class="fa-solid fa-briefcase"></i> Ajira za Muda Mrefu & Wataalamu (Careers)
                    </div>

                    <h3 class="heading-font text-2xl font-bold text-white mb-3">
                        Nafasi za Kudumu, Mikataba & Zabuni
                    </h3>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        Kwa makampuni, taasisi, wafanyabiashara na waajiri wanaotafuta nguvu kazi ya kudumu yenye vyeti, uzoefu na uaminifu unaoweza kufuatiliwa.
                    </p>

                    <ul class="space-y-3 text-sm text-slate-300 mb-8">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-blue-400 mt-1"></i>
                            <span><strong>Taaluma & TEHAMA:</strong> Programmers, wahasibu, wataalamu wa masoko, graphic designers na HR.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-blue-400 mt-1"></i>
                            <span><strong>Wahandisi & Wasimamizi:</strong> Civil, Electrical, Mechanical, na Wasimamizi wa miradi mikubwa.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-blue-400 mt-1"></i>
                            <span><strong>Uchambuzi wa Linkora AI:</strong> AI inasoma wasifu (CV) na kuandika barua za maombi (Pitches) kulingana na vigezo vya mwajiri.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-blue-400 mt-1"></i>
                            <span><strong>Uthibitisho wa Sifa (Vouching & Reviews):</strong> Historia halisi ya utendaji kutoka kwa waajiri waliopita.</span>
                        </li>
                    </ul>

                    <a href="#pakua-app" class="inline-flex items-center gap-2 text-sm font-bold text-blue-400 hover:text-blue-300">
                        <span>Tafuta Wafanyakazi au Ajira ya Kudumu</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- AI INNOVATION & SMART FEATURES -->
    <section id="ai-teknolojia" class="py-20 bg-slate-950/60 border-t border-slate-800/80 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-5">
                    <span class="text-xs font-bold text-emerald-400 tracking-wider uppercase bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                        Teknolojia ya Kisasa
                    </span>
                    <h2 class="heading-font text-3xl sm:text-4xl font-extrabold text-white mt-4 leading-tight">
                        Inaendeshwa na Linkora AI Kulinda na Kukuunganisha
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base mt-4 leading-relaxed">
                        Tofauti na mifumo ya kawaida ya matangazo, Linkora inatumia akili mnemba (Artificial Intelligence) kuhakikisha kila tangazo ni salama na kila mwombaji anapata kazi inayomfaa zaidi.
                    </p>

                    <div class="mt-8 space-y-4">
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-900/80 border border-slate-800">
                            <div class="h-10 w-10 rounded-xl bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-bullseye text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white">AI Skill Matching</h4>
                                <p class="text-xs text-slate-400 mt-1">Inachambua ujuzi wako na kupima alama za uwiano (Match Score 0-100%) na mahitaji ya kazi.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-900/80 border border-slate-800">
                            <div class="h-10 w-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-feather-pointed text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white">AI Pitch & Barua ya Maombi</h4>
                                <p class="text-xs text-slate-400 mt-1">Huna uwezo wa kuandika barua kali ya maombi? AI inakutengenezea pitch ya ushawishi kwa Kiswahili au Kiingereza.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-900/80 border border-slate-800">
                            <div class="h-10 w-10 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-shield-virus text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white">Fraud & Scam Detection</h4>
                                <p class="text-xs text-slate-400 mt-1">AI inakagua maelezo ya kazi, malipo na maelekezo ili kuzuia matangazo ya kitapeli kabla hayajawafikia watumiaji.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right visual -->
                <div class="lg:col-span-7">
                    <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-slate-800 space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-3 w-3 rounded-full bg-emerald-400 animate-pulse"></div>
                                <span class="text-xs font-mono text-slate-300">Linkora AI Engine v1.0 • Neural Matcher</span>
                            </div>
                            <span class="text-xs font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full border border-emerald-500/20">Active</span>
                        </div>

                        <div class="bg-slate-950/80 rounded-2xl p-4 border border-slate-800 font-mono text-xs text-slate-300 space-y-2">
                            <div class="text-slate-500">// Sampuli ya Uchunguzi wa AI (AI Match Result)</div>
                            <div class="text-emerald-400">POST /api/ai/match</div>
                            <div class="text-slate-300">{</div>
                            <div class="pl-4 text-slate-300">"candidate": "Hamisi Juma (Fundi Umeme NIDA Verified)",</div>
                            <div class="pl-4 text-slate-300">"match_score": 94,</div>
                            <div class="pl-4 text-emerald-300">"explanation": "Ujuzi wa kuweka nyaya, switchboards na vyeti vya VETA vinalingana 100% na tangazo la ujenzi Tegeta.",</div>
                            <div class="pl-4 text-slate-300">"fraud_risk": "SAFE (0.01)"</div>
                            <div class="text-slate-300">}</div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
                                <div class="text-xs text-slate-400">Kasi ya AI Response</div>
                                <div class="text-xl font-bold text-white mt-1">&lt; 1.2s</div>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
                                <div class="text-xs text-slate-400">Usahihi wa Matching</div>
                                <div class="text-xl font-bold text-brand-400 mt-1">98.4%</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ESCROW & SECURITY SECTION -->
    <section id="escrow-usalama" class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-panel p-8 sm:p-12 rounded-3xl border border-emerald-500/20 bg-gradient-to-b from-slate-900/90 to-slate-950">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    
                    <div class="lg:col-span-8">
                        <span class="text-xs font-bold text-emerald-400 tracking-wider uppercase bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                            Mfumo wa Malipo
                        </span>
                        <h2 class="heading-font text-2xl sm:text-4xl font-extrabold text-white mt-3">
                            Malipo Yanalindwa na Escrow (Amana ya Simu)
                        </h2>
                        <p class="text-slate-300 text-sm sm:text-base mt-4 leading-relaxed">
                            Mwajiri hawezi kudhulumu, na mfanyakazi hawezi kutelekeza kazi. Fedha za makubaliano zinawekwa kwenye mfumo salama wa <strong>Escrow</strong> kabla kazi haijaanza.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                                <div class="h-8 w-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm mb-2">1</div>
                                <h4 class="text-sm font-bold text-white">Mwajiri Anaweka Pesa</h4>
                                <p class="text-xs text-slate-400 mt-1">Pesa inatoka kwa M-Pesa / Tigo Pesa na kukaa kwenye Escrow.</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                                <div class="h-8 w-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-sm mb-2">2</div>
                                <h4 class="text-sm font-bold text-white">Kazi Inafanyika</h4>
                                <p class="text-xs text-slate-400 mt-1">Mfanyakazi anafanya kazi kwa amani akijua fedha zipo tayari.</p>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800">
                                <div class="h-8 w-8 rounded-lg bg-teal-500/20 text-teal-400 flex items-center justify-center font-bold text-sm mb-2">3</div>
                                <h4 class="text-sm font-bold text-white">Malipo Yanatolewa</h4>
                                <p class="text-xs text-slate-400 mt-1">Mwajiri akiridhika, mfumo unahamisha fedha papo hapo kwenda kwa mfanyakazi.</p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4 text-center lg:text-right">
                        <div class="inline-block p-6 rounded-3xl bg-slate-950 border border-slate-800 text-center">
                            <i class="fa-solid fa-lock text-5xl text-emerald-400 mb-4"></i>
                            <div class="text-base font-bold text-white">100% Hakikisho la Malipo</div>
                            <div class="text-xs text-slate-400 mt-1 mb-4">M-Pesa • Airtel Money • Tigo Pesa • Halopesa</div>
                            <span class="inline-flex items-center gap-1.5 text-xs text-emerald-300 font-bold bg-emerald-500/20 px-3 py-1 rounded-full border border-emerald-500/30">
                                <i class="fa-solid fa-check"></i> Hakuna Dhuluma
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <!-- DOWNLOAD APK SECTION (CORE APPLICATION DOWNLOAD) -->
    <section id="pakua-app" class="py-20 md:py-28 relative overflow-hidden bg-gradient-to-b from-darkBg via-slate-950 to-emerald-950/20 border-t border-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-brand-500/10 text-brand-400 text-xs font-bold mb-6 border border-brand-500/30">
                <i class="fa-brands fa-android text-base"></i>
                <span>Toleo Rasmi la Android (Direct APK)</span>
            </div>

            <h2 class="heading-font text-3xl sm:text-5xl font-extrabold text-white mb-6 tracking-tight">
                Pakua Linkora Android App Kwenye Simu Yako
            </h2>

            <p class="text-base sm:text-lg text-slate-300 max-w-2xl mx-auto mb-10 leading-relaxed">
                Pata uzoefu kamili wa kutafuta kazi, kupokea arifa za dharura za vibarua vya karibu, kuthibitisha NIDA na kusimamia malipo ya Escrow moja kwa moja kwenye simu yako ya mkononi.
            </p>

            <!-- Download Action Card -->
            <div class="glass-panel max-w-2xl mx-auto p-8 rounded-3xl border border-emerald-500/30 glow-emerald mb-10">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    
                    <div class="text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start gap-2">
                            <span class="text-xl font-bold text-white">Linkora v1.0.2</span>
                            <span class="text-[10px] bg-emerald-500/20 text-emerald-300 font-bold px-2 py-0.5 rounded border border-emerald-500/30">APK Rasmi (Latest)</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Ujazo: ~18 MB • Inasaidia Android 7.0 na Kuendelea</p>
                        <p class="text-xs text-slate-400 mt-0.5">Imeunganishwa na Seva: <strong class="text-emerald-400 font-mono">api.linkora.co.tz</strong></p>
                    </div>

                    <a href="{{ route('download.apk') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl font-extrabold text-slate-950 bg-gradient-to-r from-brand-400 via-emerald-400 to-teal-300 hover:from-brand-300 hover:to-teal-200 shadow-xl shadow-brand-500/30 transition duration-200 transform hover:scale-105">
                        <i class="fa-solid fa-download text-xl"></i>
                        <span class="text-base">Pakua APK Moja kwa Moja</span>
                    </a>

                </div>

                <!-- APK Installation Instructions -->
                <div class="mt-8 pt-6 border-t border-slate-800 text-left text-xs text-slate-400 space-y-2">
                    <div class="font-bold text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-brand-400"></i>
                        <span>Jinsi ya Kuweka (Install) APK kwenye Simu Yako:</span>
                    </div>
                    <ol class="list-decimal list-inside space-y-1 text-slate-300 pl-2">
                        <li>Bofya kitufe cha <strong>"Pakua APK"</strong> hapo juu.</li>
                        <li>Baada ya kupakua, fungua faili ya <strong>linkora.apk</strong>.</li>
                        <li>Ikiwa simu itaomba ruhusa, chagua <em>"Settings"</em> kisha wezesha <em>"Allow from this source / Ruhusu kutoka chanzo hiki"</em>.</li>
                        <li>Bonyeza <strong>"Install"</strong> kisha anza kuitumia papo hapo!</li>
                    </ol>
                </div>
            </div>

            <!-- API / Developer Notice -->
            <div class="inline-flex items-center gap-2 text-xs text-slate-400">
                <i class="fa-solid fa-network-wired text-brand-400"></i>
                <span>API Gateway rasmi inapatikana kwenye: <code class="bg-slate-900 px-2 py-0.5 rounded text-emerald-400 font-mono">https://api.linkora.co.tz</code></span>
            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="border-t border-slate-800 bg-slate-950 py-12 text-slate-400 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="h-8 w-8 rounded-lg bg-emerald-500 text-slate-950 flex items-center justify-center font-black">
                            L
                        </div>
                        <span class="heading-font text-xl font-bold text-white">Linkora</span>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-md">
                        Jukwaa la kidijitali linalotumia teknolojia ya kisasa ya Linkora AI, uthibitisho wa NIDA, na huduma salama ya Escrow kuunganisha Watanzania na ajira za muda mfupi (vibarua) na muda mrefu.
                    </p>
                    <div class="mt-4 flex items-center gap-4 text-slate-400 text-base">
                        <a href="#" class="hover:text-brand-400"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="#" class="hover:text-brand-400"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="hover:text-brand-400"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="hover:text-brand-400"><i class="fa-brands fa-x-twitter"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-slate-200 text-sm mb-3">Huduma za Mfumo</h4>
                    <ul class="space-y-2">
                        <li><a href="#aina-za-ajira" class="hover:text-brand-400">Ajira za Muda Mfupi (Gigs)</a></li>
                        <li><a href="#aina-za-ajira" class="hover:text-brand-400">Ajira za Muda Mrefu</a></li>
                        <li><a href="#ai-teknolojia" class="hover:text-brand-400">Linkora AI Matching</a></li>
                        <li><a href="#escrow-usalama" class="hover:text-brand-400">Mfumo wa Escrow (Amana)</a></li>
                        <li><a href="#pakua-app" class="hover:text-brand-400">Pakua Android APK</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-200 text-sm mb-3">Lango la Utawala</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('admin.login') }}" class="hover:text-brand-400 font-semibold text-slate-300">Admin Management Portal</a></li>
                        <li><a href="{{ route('download.apk') }}" class="hover:text-brand-400">Pakua Android APK</a></li>
                        <li><a href="https://api.linkora.co.tz" class="hover:text-brand-400 font-mono text-[11px]">API: api.linkora.co.tz</a></li>
                        <li class="pt-2 text-[11px] text-slate-500">Imeundwa na: <strong>Swebetech IT Solutions</strong></li>
                    </ul>
                </div>

            </div>

            <div class="pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
                <div>&copy; {{ date('Y') }} Linkora (Linkora.co.tz). Haki zote zimehifadhiwa.</div>
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

</body>
</html>
