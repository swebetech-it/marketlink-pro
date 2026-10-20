@extends('layouts.admin')

@section('title', 'Escrow Ledger & Audit Trail')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-black text-white tracking-tight">Escrow Ledger & Immutable Audit Trail</h1>
        <p class="text-xs text-slate-400 mt-1">Real-time financial custody monitoring across M-Pesa, Tigo Pesa, Airtel Money, and CRDB Bank rails.</p>
    </div>

    <!-- Transactions Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="fa-solid fa-shield-halved text-emerald-400"></i>
            <h2 class="text-lg font-black text-white">Escrow Transactions</h2>
        </div>

        @if($transactions->isEmpty())
            <div class="text-center py-12 border border-dashed border-slate-800 rounded-xl">
                <i class="fa-solid fa-receipt text-4xl text-slate-600 mb-2"></i>
                <p class="text-sm font-semibold text-slate-400">No escrow transactions initiated yet.</p>
                <p class="text-xs text-slate-500 mt-1">When clients fund milestones via the Android app, transactions appear here with cryptographic audit codes.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="pb-3">Audit Code</th>
                            <th class="pb-3">Listing Title</th>
                            <th class="pb-3">Payer & Payee</th>
                            <th class="pb-3">Amount (TZS)</th>
                            <th class="pb-3">Payment Method</th>
                            <th class="pb-3">Escrow Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="py-3 font-mono text-emerald-400 font-bold">
                                    {{ $tx->audit_code }}
                                </td>
                                <td class="py-3 font-medium text-white">
                                    {{ $tx->listing_title }}
                                </td>
                                <td class="py-3 text-slate-300">
                                    <span class="text-slate-400">From:</span> {{ $tx->payer_name }}<br>
                                    <span class="text-slate-400">To:</span> {{ $tx->payee_name }}
                                </td>
                                <td class="py-3 font-bold text-amber-400 text-sm">
                                    TZS {{ number_format($tx->amount_tzs) }}
                                </td>
                                <td class="py-3 text-slate-400">
                                    <i class="fa-solid fa-mobile-screen text-slate-500 mr-1"></i> {{ $tx->payment_method }}
                                </td>
                                <td class="py-3">
                                    @if($tx->escrow_status === 'HELD_IN_ESCROW')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                            HELD IN ESCROW
                                        </span>
                                    @elseif($tx->escrow_status === 'RELEASED')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                            FUNDS RELEASED
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400">
                                            {{ $tx->escrow_status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Immutable Audit Log -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="fa-solid fa-list-check text-purple-400"></i>
            <h2 class="text-lg font-black text-white">System Security & Audit Trail</h2>
        </div>

        @if($auditLogs->isEmpty())
            <p class="text-xs text-slate-500">No administrative actions logged yet.</p>
        @else
            <div class="space-y-2">
                @foreach($auditLogs as $log)
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-800/40 text-xs border border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-purple-400 font-bold">{{ $log->event }}</span>
                            <span class="text-slate-400">• Actor: {{ $log->user_id }}</span>
                        </div>
                        <span class="text-slate-500 text-[11px] font-mono">{{ $log->created_at ? $log->created_at->diffForHumans() : 'Just now' }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
