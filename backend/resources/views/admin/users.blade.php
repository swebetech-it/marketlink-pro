@extends('layouts.admin')

@section('title', 'Registered Users & NIDA KYC')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">System Users & KYC Verification</h1>
            <p class="text-xs text-slate-400 mt-1">Review registered mobile users, verify NIDA identification numbers, and inspect reputation scores.</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
        @if($users->isEmpty())
            <div class="text-center py-16 border border-dashed border-slate-800 rounded-xl">
                <i class="fa-solid fa-users text-4xl text-slate-600 mb-3"></i>
                <p class="text-base font-semibold text-slate-300">No registered users in the database yet.</p>
                <p class="text-xs text-slate-500 mt-1">When users sign up via the Android App, they will appear here instantly.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="pb-3">User & Contact</th>
                            <th class="pb-3">Role</th>
                            <th class="pb-3">NIDA Number</th>
                            <th class="pb-3">Location</th>
                            <th class="pb-3">Reputation</th>
                            <th class="pb-3">KYC Status</th>
                            <th class="pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="py-3">
                                    <div class="font-bold text-white text-sm">{{ $user->full_name }}</div>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $user->phone }}</span>
                                    @if($user->email)
                                        <span class="text-[10px] text-slate-500 block">{{ $user->email }}</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-blue-400 border border-blue-500/20">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-3 font-mono text-slate-300">
                                    {{ $user->nida_number ?: 'Not Provided' }}
                                </td>
                                <td class="py-3 text-slate-400">
                                    <i class="fa-solid fa-location-dot text-slate-500 mr-1"></i> {{ $user->location }}
                                </td>
                                <td class="py-3">
                                    <span class="font-black text-emerald-400 text-sm">{{ $user->reputation_score }}</span>
                                    <span class="text-[10px] text-slate-500">/ 1000</span>
                                </td>
                                <td class="py-3">
                                    @if($user->verification_status === 'VERIFIED')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                            <i class="fa-solid fa-circle-check"></i> VERIFIED
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                            <i class="fa-solid fa-clock"></i> PENDING
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-right">
                                    <form action="{{ route('admin.users.verify', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs px-2.5 py-1 rounded-lg font-semibold transition {{ $user->verification_status === 'VERIFIED' ? 'bg-slate-800 text-slate-400 hover:bg-slate-700' : 'bg-emerald-600 hover:bg-emerald-500 text-white shadow' }}">
                                            {{ $user->verification_status === 'VERIFIED' ? 'Revoke KYC' : 'Verify NIDA' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
