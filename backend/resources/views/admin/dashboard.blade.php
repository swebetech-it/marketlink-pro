@extends('layouts.admin')

@section('title', 'Dashibodi ya Utawala')

@section('content')
<div class="space-y-8">

    <!-- Page Header & Action -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="display-font text-2xl font-bold text-white tracking-tight">Dashibodi ya Utawala na Uendeshaji</h1>
            <p class="text-xs text-slate-400 mt-1">Simamia fursa za kazi, kagua malipo ya Escrow, na thibitisha vitambulisho vya NIDA vya watumiaji.</p>
        </div>
        <div>
            <button onclick="document.getElementById('createListingModal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-600 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-md transition">
                <i class="fa-solid fa-plus-circle"></i> Weka Nafasi / Tangazo Jipya
            </button>
        </div>
    </div>

    <!-- Stat Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-[#121820] border border-[#1f2a36] p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Watumiaji Wote</span>
            <div class="display-font text-2xl font-bold text-white mt-1">{{ number_format($stats['totalUsers']) }}</div>
            <span class="text-[10px] text-emerald-400 font-semibold"><i class="fa-solid fa-user-check"></i> {{ $stats['verifiedUsers'] }} NIDA Verified</span>
        </div>

        <div class="bg-[#121820] border border-[#1f2a36] p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kazi Zilizopo</span>
            <div class="display-font text-2xl font-bold text-emerald-400 mt-1">{{ number_format($stats['activeListings']) }}</div>
            <span class="text-[10px] text-slate-400 font-semibold">Zinaonekana kwenye App</span>
        </div>

        <div class="bg-[#121820] border border-[#1f2a36] p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Maombi ya Kazi</span>
            <div class="display-font text-2xl font-bold text-blue-400 mt-1">{{ number_format($stats['totalApplications']) }}</div>
            <span class="text-[10px] text-slate-400 font-semibold">Kutoka kwa Mafundi</span>
        </div>

        <div class="bg-[#121820] border border-[#1f2a36] p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Zilizopo Escrow</span>
            <div class="text-base font-bold text-amber-400 mt-1 font-mono">TZS {{ number_format($stats['totalEscrowHeldTzs']) }}</div>
            <span class="text-[10px] text-amber-500/80 font-semibold">Fedha Zilizolindwa</span>
        </div>

        <div class="bg-[#121820] border border-[#1f2a36] p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Zilizolipwa</span>
            <div class="text-base font-bold text-emerald-400 mt-1 font-mono">TZS {{ number_format($stats['totalEscrowReleasedTzs']) }}</div>
            <span class="text-[10px] text-emerald-400 font-semibold">Kazi Zilizokamilika</span>
        </div>

        <div class="bg-[#121820] border border-[#1f2a36] p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ulinzi wa Mfumo</span>
            <div class="display-font text-2xl font-bold text-emerald-300 mt-1">100%</div>
            <span class="text-[10px] text-emerald-400 font-semibold">NIDA & Escrow Guard</span>
        </div>
    </div>

    <!-- Main Grid: Recent Listings & Recent Users -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Listings (2 cols) -->
        <div class="lg:col-span-2 bg-[#121820] border border-[#1f2a36] rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-briefcase text-emerald-400"></i>
                    <h2 class="text-base font-bold text-white">Kazi na Nafasi za Hivi Karibuni</h2>
                </div>
                <a href="{{ route('admin.listings') }}" class="text-xs text-emerald-400 hover:text-emerald-300 font-bold">Tazama Zote ({{ $stats['activeListings'] }}) &rarr;</a>
            </div>

            @if($recentListings->isEmpty())
                <div class="text-center py-12 border border-dashed border-[#1f2b38] rounded-xl">
                    <i class="fa-solid fa-folder-open text-3xl text-slate-600 mb-2"></i>
                    <p class="text-sm font-semibold text-slate-400">Hakuna kazi iliyowekwa bado.</p>
                    <p class="text-xs text-slate-500 mt-1">Bofya "Weka Nafasi / Tangazo Jipya" ili kuweka kazi kwa watumiaji wa app.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-slate-400 border-b border-[#1f2b38] uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="pb-3">Kichwa na Aina</th>
                                <th class="pb-3">Eneo</th>
                                <th class="pb-3">Malipo</th>
                                <th class="pb-3">Escrow</th>
                                <th class="pb-3 text-right">Vitendo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#18232e]">
                            @foreach($recentListings as $listing)
                                <tr class="hover:bg-[#16202a] transition">
                                    <td class="py-3">
                                        <div class="font-bold text-white text-sm">{{ $listing->title }}</div>
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-[#0e141a] text-emerald-400 border border-emerald-500/20 mt-1">
                                            {{ $listing->category }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-slate-300">
                                        <i class="fa-solid fa-location-dot text-slate-500 mr-1"></i> {{ $listing->location }}
                                    </td>
                                    <td class="py-3 font-semibold text-amber-400 font-mono">
                                        {{ $listing->compensation }}
                                    </td>
                                    <td class="py-3">
                                        @if($listing->is_escrow_required)
                                            <span class="text-emerald-400 font-bold"><i class="fa-solid fa-lock"></i> Escrow</span>
                                        @else
                                            <span class="text-slate-500">Moja kwa moja</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right">
                                        <form action="{{ route('admin.listings.delete', $listing->id) }}" method="POST" onsubmit="return confirm('Una uhakika unataka kufuta tangazo hili?');" class="inline">
                                            @csrf
                                            <button type="submit" class="text-rose-400 hover:text-rose-300 p-1.5 rounded hover:bg-rose-950/50 transition" title="Futa Tangazo">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Recent Registered Users (1 col) -->
        <div class="bg-[#121820] border border-[#1f2a36] rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-users text-emerald-400"></i>
                    <h2 class="text-base font-bold text-white">Watumiaji Wapya</h2>
                </div>
                <a href="{{ route('admin.users') }}" class="text-xs text-emerald-400 hover:text-emerald-300 font-bold">Wote &rarr;</a>
            </div>

            @if($recentUsers->isEmpty())
                <div class="text-center py-12 border border-dashed border-[#1f2b38] rounded-xl">
                    <i class="fa-solid fa-user-slash text-3xl text-slate-600 mb-2"></i>
                    <p class="text-sm font-semibold text-slate-400">Hakuna mtumiaji aliyejisajili bado.</p>
                    <p class="text-xs text-slate-500 mt-1">Watumiaji wakijisajili kwenye app wataonekana hapa papo hapo.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentUsers as $u)
                        <div class="p-3 rounded-xl bg-[#16212b] border border-[#22313f] flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-emerald-900/60 border border-emerald-700/60 flex items-center justify-center font-bold text-emerald-300 text-xs shrink-0">
                                    {{ strtoupper(substr($u->full_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-white text-xs sm:text-sm">{{ $u->full_name }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $u->phone }} • <span class="text-emerald-400 font-sans font-semibold">{{ $u->role }}</span></div>
                                    <div class="text-[10px] text-slate-500 mt-0.5"><i class="fa-solid fa-location-dot"></i> {{ $u->location }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <form action="{{ route('admin.users.verify', $u->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold px-2.5 py-1 rounded-full border transition {{ $u->verification_status === 'VERIFIED' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-amber-500/10 text-amber-400 border-amber-500/30' }}">
                                        @if($u->verification_status === 'VERIFIED')
                                            <i class="fa-solid fa-check-circle"></i> NIDA OK
                                        @else
                                            <i class="fa-solid fa-clock"></i> PENDING
                                        @endif
                                    </button>
                                </form>
                                <span class="block text-[10px] text-slate-400 font-semibold mt-1">{{ $u->reputation_score }} pts</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>

<!-- Create Listing Modal -->
<div id="createListingModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-[#121820] border border-[#222d3a] rounded-2xl max-w-2xl w-full p-6 shadow-2xl relative">
        <button onclick="document.getElementById('createListingModal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="flex items-center gap-2.5 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-900/60 text-emerald-400 flex items-center justify-center border border-emerald-700">
                <i class="fa-solid fa-bullhorn text-sm"></i>
            </div>
            <div>
                <h3 class="display-font text-lg font-bold text-white">Weka Nafasi / Tangazo Jipya</h3>
                <p class="text-xs text-slate-400">Tangazo litatokea papo hapo kwenye orodha ya waombaji wa Android App.</p>
            </div>
        </div>

        <form action="{{ route('admin.listings.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Kichwa cha Kazi / Fursa *</label>
                <input type="text" name="title" required placeholder="mfano: Fundi Umeme na Ufungaji wa Sola / Vibarua wa Uvunaji" class="w-full bg-[#0b1116] border border-[#2b3a4a] rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Aina ya Kazi (Category) *</label>
                    <select name="category" required class="w-full bg-[#0b1116] border border-[#2b3a4a] rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                        <option value="SKILLED_SERVICE">Ufundi & Huduma (Skilled Service)</option>
                        <option value="EMPLOYMENT">Ajira ya Kudumu (Employment)</option>
                        <option value="CASUAL_GIG">Vibarua vya Siku (Casual Gig)</option>
                        <option value="AGRI_LABOR">Kilimo & Mifugo (Agri Labor)</option>
                        <option value="EQUIPMENT_RENTAL">Kukodisha Vifaa (Equipment Rental)</option>
                        <option value="BUSINESS_OPP">Biashara & Zabuni (Business)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Eneo / Mkoa *</label>
                    <input type="text" name="location" required value="Dar es Salaam, Tanzania" class="w-full bg-[#0b1116] border border-[#2b3a4a] rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Malipo Yanayoonekana *</label>
                    <input type="text" name="compensation" required placeholder="mfano: TZS 750,000 / mradi au TZS 45,000 / siku" class="w-full bg-[#0b1116] border border-[#2b3a4a] rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Kiasi cha Hesabu ya Escrow (TZS)</label>
                    <input type="number" name="compensationAmountTzs" value="750000" class="w-full bg-[#0b1116] border border-[#2b3a4a] rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Ujuzi Unaohitajika (Tenganisha kwa koma)</label>
                <input type="text" name="requiredSkills" placeholder="mfano: Wiring ya Nyumba, Switchboards, Vyeti vya VETA" class="w-full bg-[#0b1116] border border-[#2b3a4a] rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Maelezo ya Kazi *</label>
                <textarea name="description" rows="3" required placeholder="Eleza majukumu ya kazi, muda wa kukamilisha na vigezo vingine..." class="w-full bg-[#0b1116] border border-[#2b3a4a] rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="isEscrowRequired" name="isEscrowRequired" value="1" checked class="w-4 h-4 text-emerald-600 bg-[#0b1116] border-[#2b3a4a] rounded">
                <label for="isEscrowRequired" class="text-xs text-slate-300 font-medium">Ulinzi wa Escrow unahitajika (M-Pesa / Tigo Pesa)</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#1f2a36]">
                <button type="button" onclick="document.getElementById('createListingModal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-slate-400 hover:text-white">Ghairi</button>
                <button type="submit" class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl shadow transition">Chapisha kwenye App</button>
            </div>
        </form>
    </div>
</div>
@endsection
