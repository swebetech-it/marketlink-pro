@extends('layouts.admin')

@section('title', 'Amana ya Escrow')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div>
        <h1 class="display-font text-2xl font-bold text-white tracking-tight">Daftari la Malipo ya Escrow na Amana</h1>
        <p class="text-xs text-slate-400 mt-1">Ufuatiliaji wa fedha zilizowekwa na waajiri na kulipwa kwa mafundi kupitia M-Pesa, Tigo Pesa na Airtel Money.</p>
    </div>

    <!-- Transactions Table -->
    <div class="bg-[#121820] border border-[#1f2a36] rounded-2xl p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="fa-solid fa-shield-halved text-emerald-400"></i>
            <h2 class="text-base font-bold text-white">Miamala ya Escrow</h2>
        </div>

        @if($transactions->isEmpty())
            <div class="text-center py-12 border border-dashed border-[#1f2b38] rounded-xl">
                <i class="fa-solid fa-receipt text-4xl text-slate-600 mb-2"></i>
                <p class="text-sm font-semibold text-slate-400">Hakuna muamala ulioanzishwa bado.</p>
                <p class="text-xs text-slate-500 mt-1">Wateja wakiweka amana kwa ajili ya kazi kupitia simu zao, miamala itaonekana hapa ikiwa na namba ya ukaguzi.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 border-b border-[#1f2b38] uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="pb-3">Namba ya Muamala</th>
                            <th class="pb-3">Kazi Inayohusika</th>
                            <th class="pb-3">Mlipaji na Mlipwaji</th>
                            <th class="pb-3">Kiasi (TZS)</th>
                            <th class="pb-3">Njia ya Malipo</th>
                            <th class="pb-3">Hali ya Amana</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#18232e]">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-[#16202a] transition">
                                <td class="py-3 font-mono text-emerald-400 font-bold">
                                    {{ $tx->audit_code }}
                                </td>
                                <td class="py-3 font-medium text-white">
                                    {{ $tx->listing_title }}
                                </td>
                                <td class="py-3 text-slate-300">
                                    <span class="text-slate-400">Kutoka:</span> {{ $tx->payer_name }}<br>
                                    <span class="text-slate-400">Kwenda:</span> {{ $tx->payee_name }}
                                </td>
                                <td class="py-3 font-bold text-amber-400 text-sm font-mono">
                                    TZS {{ number_format($tx->amount_tzs) }}
                                </td>
                                <td class="py-3 text-slate-400">
                                    <i class="fa-solid fa-mobile-screen text-slate-500 mr-1"></i> {{ $tx->payment_method }}
                                </td>
                                <td class="py-3">
                                    @if($tx->escrow_status === 'HELD_IN_ESCROW')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                            IPO KWENYE AMANA
                                        </span>
                                    @elseif($tx->escrow_status === 'RELEASED')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                            IMEKWENDA KWA FUNDI
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400">
                                            {{ $tx->escrow_status }}
                                        </span>
                                    @endif
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
