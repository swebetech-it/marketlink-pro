<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pakua Linkora Android APK | Linkora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 text-center shadow-2xl relative overflow-hidden">
        <div class="h-16 w-16 bg-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl border border-emerald-500/30">
            <i class="fa-brands fa-android"></i>
        </div>

        <h1 class="text-2xl font-bold text-white mb-2">Linkora Android App</h1>
        <p class="text-slate-300 text-sm mb-6">
            Faili ya ufungaji wa Android (APK) inatayarishwa kwa ajili ya usambazaji.
        </p>

        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4 text-left text-xs text-slate-300 mb-6 space-y-2">
            <div class="font-bold text-emerald-400 flex items-center gap-2">
                <i class="fa-solid fa-server"></i> Seva ya Linkora:
            </div>
            <p>Ili kufanya APK ipatikane kwa watumiaji, weka faili ya <code class="text-emerald-300 font-mono">linkora.apk</code> katika folda:</p>
            <div class="bg-slate-900 p-2.5 rounded border border-slate-800 font-mono text-emerald-400 text-[11px] break-all">
                {{ $expected_path ?? public_path('downloads/linkora.apk') }}
            </div>
            <p class="text-slate-400 text-[11px] pt-1">
                Watumiaji wakibofya "Pakua APK", mfumo utatuma faili hiyo moja kwa moja kwenye simu zao.
            </p>
        </div>

        <div class="flex flex-col gap-3">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-sm transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Rudi Ukurasa Mkuu (linkora.co.tz)</span>
            </a>
            <a href="{{ route('admin.login') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-xs border border-slate-700 transition">
                <span>Ingia Kwenye Admin Portal</span>
            </a>
        </div>
    </div>
</body>
</html>
