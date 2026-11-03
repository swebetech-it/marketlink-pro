@extends('layouts.admin')

@section('title', 'Watumiaji na NIDA')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="display-font text-2xl font-bold text-white tracking-tight">Watumiaji wa Mfumo na Uhakiki wa NIDA</h1>
            <p class="text-xs text-slate-400 mt-1">Kagua watumiaji waliojisajili kupitia simu, thibitisha namba za vitambulisho vya NIDA, na simamia uaminifu.</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-[#121820] border border-[#1f2a36] rounded-2xl p-6">
        @if($users->isEmpty())
            <div class="text-center py-16 border border-dashed border-[#1f2b38] rounded-xl">
                <i class="fa-solid fa-users text-4xl text-slate-600 mb-3"></i>
                <p class="text-base font-semibold text-slate-300">Hakuna watumiaji kwenye kanzidata bado.</p>
                <p class="text-xs text-slate-500 mt-1">Watumiaji wakijisajili kwenye Android App, wataonekana hapa papo hapo.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 border-b border-[#1f2b38] uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="pb-3">Mtumiaji na Mawasiliano</th>
                            <th class="pb-3">Jukumu (Role)</th>
                            <th class="pb-3">Namba ya NIDA</th>
                            <th class="pb-3">Eneo</th>
                            <th class="pb-3">Alama (Score)</th>
                            <th class="pb-3">Hali ya NIDA</th>
                            <th class="pb-3 text-right">Vitendo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#18232e]">
                        @foreach($users as $user)
                            <tr class="hover:bg-[#16202a] transition">
                                <td class="py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-900/60 border border-emerald-700/60 flex items-center justify-center font-bold text-emerald-300 text-xs shrink-0">
                                            {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white text-sm">{{ $user->full_name }}</div>
                                            <span class="text-[11px] text-slate-400 font-mono">{{ $user->phone }}</span>
                                            @if($user->email)
                                                <span class="text-[10px] text-slate-500 block">{{ $user->email }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-[#0e141a] text-blue-400 border border-blue-500/20">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-3 font-mono text-slate-300">
                                    {{ $user->nida_number ?: 'Haijawekwa' }}
                                </td>
                                <td class="py-3 text-slate-400">
                                    <i class="fa-solid fa-location-dot text-slate-500 mr-1"></i> {{ $user->location }}
                                </td>
                                <td class="py-3">
                                    <span class="font-bold text-emerald-400 text-sm font-mono">{{ $user->reputation_score }}</span>
                                    <span class="text-[10px] text-slate-500">/ 1000</span>
                                </td>
                                <td class="py-3">
                                    @if($user->verification_status === 'VERIFIED')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                            <i class="fa-solid fa-circle-check"></i> IMETHIBITISHWA
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                            <i class="fa-solid fa-clock"></i> INASUBIRI
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-right">
                                    <form action="{{ route('admin.users.verify', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-lg text-xs font-bold transition {{ $user->verification_status === 'VERIFIED' ? 'bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-emerald-600 hover:bg-emerald-500 text-white' }}">
                                            {{ $user->verification_status === 'VERIFIED' ? 'Batilisha' : 'Thibitisha NIDA' }}
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
@endsection
