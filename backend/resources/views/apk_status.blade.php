<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pakua Linkora Android APK | Linkora Tanzania</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
        .display-font { font-family: 'Fraunces', Georgia, serif; }
    </style>
</head>
<body class="bg-[#0b1116] text-slate-100 min-h-screen flex items-center justify-center p-4 selection:bg-emerald-600 selection:text-white">

    <div class="max-w-lg w-full bg-[#121820] border border-[#222d3a] rounded-3xl p-6 sm:p-8 shadow-2xl relative">
        
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <div class="h-16 w-16 bg-[#163022] text-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl border border-emerald-600/40 shadow-md">
                <i class="fa-brands fa-android"></i>
            </div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-400 bg-[#163022] px-3 py-1 rounded-full border border-emerald-600/40">
                Linkora Android Ecosystem
            </span>
            <h1 class="display-font text-2xl sm:text-3xl font-bold text-white mt-3">
                Pakua Linkora App
            </h1>
            <p class="text-slate-300 text-xs sm:text-sm mt-1">
                Jukwaa la ajira, vibarua na malipo salama ya Escrow Tanzania
            </p>
        </div>

        <!-- Download Info & Server Status Box -->
        <div class="bg-[#17212b] border border-[#263544] rounded-2xl p-5 text-left text-xs text-slate-300 mb-6 space-y-3">
            <div class="flex items-center justify-between pb-3 border-b border-[#233140]">
                <span class="font-bold text-white flex items-center gap-1.5">
                    <i class="fa-solid fa-cube text-emerald-400"></i> Linkora v1.0.2 APK
                </span>
                <span class="text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded font-mono">18 MB</span>
            </div>

            <p class="text-slate-300 text-xs leading-relaxed">
                Faili ya ufungaji wa Android (APK) inapatikana moja kwa moja kutoka kwenye seva yetu rasmi ya Linkora.
            </p>

            <div class="bg-[#0d141b] p-3 rounded-xl border border-[#1f2c3a] font-mono text-emerald-400 text-[11px] break-all">
                {{ $expected_path ?? public_path('downloads/linkora.apk') }}
            </div>

            <div class="pt-1 flex items-center gap-2 text-[11px] text-slate-400">
                <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                <span>APK imekaguliwa, haina virusi wala programu hatarishi.</span>
            </div>
        </div>

        <!-- Installation Instructions -->
        <div class="bg-[#0e151c] p-4 rounded-xl border border-[#1d2936] text-xs text-slate-300 mb-6 space-y-2">
            <span class="font-bold text-slate-200 block mb-1">Jinsi ya kuweka kwenye simu yako:</span>
            <div class="flex items-start gap-2">
                <span class="text-emerald-400 font-bold">1.</span>
                <span>Bofya kitufe cha <strong>"Rudi Ukurasa Mkuu"</strong> au pakuwa faili moja kwa moja.</span>
            </div>
            <div class="flex items-start gap-2">
                <span class="text-emerald-400 font-bold">2.</span>
                <span>Fungua faili ya <code class="text-emerald-300">linkora.apk</code> kisha chagua <strong>Install</strong>.</span>
            </div>
            <div class="flex items-start gap-2">
                <span class="text-emerald-400 font-bold">3.</span>
                <span>Weka namba yako ya NIDA na uanze kupata kazi au kuajiri papo hapo.</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col gap-3">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-forest-700 hover:bg-forest-600 text-white font-bold text-sm transition shadow-lg shadow-forest-900/30">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Rudi Ukurasa Mkuu (Linkora.co.tz)</span>
            </a>
            <a href="{{ route('admin.login') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 font-semibold text-xs border border-slate-700 transition">
                <i class="fa-solid fa-lock text-[10px]"></i>
                <span>Lango la Utawala (Admin Portal)</span>
            </a>
        </div>

        <!-- Corporate Attribution -->
        <div class="mt-6 text-center text-[11px] text-slate-500 pt-4 border-t border-[#1e2a36]">
            Swebetech Company Limited • Mbeya, Tanzania • Simu: +255 755 000 000
        </div>

    </div>

</body>
</html>
