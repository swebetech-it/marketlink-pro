@extends('layouts.admin')

@section('title', 'Kazi na Nafasi')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="display-font text-2xl font-bold text-white tracking-tight">Usimamizi wa Kazi na Fursa</h1>
            <p class="text-xs text-slate-400 mt-1">Weka, sasisha au ondoa nafasi za kazi zinazoonekana kwa watumiaji wa app ya simu.</p>
        </div>
        <button onclick="document.getElementById('createListingModal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-600 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow transition">
            <i class="fa-solid fa-plus-circle"></i> Weka Tangazo Jipya
        </button>
    </div>

    <!-- Category Filters -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.listings') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ empty($category) ? 'bg-forest-700 text-white' : 'bg-[#121820] text-slate-400 border border-[#1f2a36] hover:bg-[#18232e]' }}">
            Kazi Zote
        </a>
        @foreach(['SKILLED_SERVICE' => 'Ufundi & Huduma', 'EMPLOYMENT' => 'Ajira ya Kudumu', 'CASUAL_GIG' => 'Vibarua vya Siku', 'AGRI_LABOR' => 'Kilimo & Mifugo', 'EQUIPMENT_RENTAL' => 'Kukodisha Vifaa', 'BUSINESS_OPP' => 'Biashara & Zabuni'] as $catKey => $catLabel)
            <a href="{{ route('admin.listings', ['category' => $catKey]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $category === $catKey ? 'bg-forest-700 text-white' : 'bg-[#121820] text-slate-400 border border-[#1f2a36] hover:bg-[#18232e]' }}">
                {{ $catLabel }}
            </a>
        @endforeach
    </div>

    <!-- Listings Table -->
    <div class="bg-[#121820] border border-[#1f2a36] rounded-2xl p-6">
        @if($listings->isEmpty())
            <div class="text-center py-16 border border-dashed border-[#1f2b38] rounded-xl">
                <i class="fa-solid fa-briefcase text-4xl text-slate-600 mb-3"></i>
                <p class="text-base font-semibold text-slate-300">Hakuna fursa zilizopatikana kwenye kundi hili.</p>
                <p class="text-xs text-slate-500 mt-1">Bofya kitufe cha hapo juu kuongeza nafasi mpya.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 border-b border-[#1f2b38] uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="pb-3">Kazi & Kichwa</th>
                            <th class="pb-3">Mwajiri / Mchapishaji</th>
                            <th class="pb-3">Aina</th>
                            <th class="pb-3">Eneo</th>
                            <th class="pb-3">Malipo</th>
                            <th class="pb-3">Ujuzi</th>
                            <th class="pb-3">Escrow</th>
                            <th class="pb-3 text-right">Vitendo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#18232e]">
                        @foreach($listings as $listing)
                            <tr class="hover:bg-[#16202a] transition">
                                <td class="py-3">
                                    <div class="font-bold text-white text-sm">{{ $listing->title }}</div>
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $listing->id }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="font-bold text-emerald-400 text-xs flex items-center gap-1.5">
                                        <i class="fa-solid fa-building"></i>
                                        <span>{{ $listing->poster_name ?: 'Mwanachama' }}</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $listing->poster_id }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-[#0e141a] text-emerald-400 border border-emerald-500/20">
                                        {{ $listing->category }}
                                    </span>
                                </td>
                                <td class="py-3 text-slate-300">
                                    <i class="fa-solid fa-location-dot text-slate-500 mr-1"></i> {{ $listing->location }}
                                </td>
                                <td class="py-3 font-semibold text-amber-400 font-mono">
                                    {{ $listing->compensation }}
                                </td>
                                <td class="py-3 text-slate-400 max-w-xs truncate">
                                    {{ is_array($listing->required_skills) ? implode(', ', $listing->required_skills) : $listing->required_skills }}
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
                                        <button type="submit" class="text-rose-400 hover:text-rose-300 p-1.5 rounded hover:bg-rose-950/50 transition" title="Futa">
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
