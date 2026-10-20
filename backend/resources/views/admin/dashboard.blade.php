@extends('layouts.admin')

@section('title', 'Admin Management & Operations Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Page Header & Action -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">System Operations & Web Management Portal</h1>
            <p class="text-xs text-slate-400 mt-1">Manage listings for mobile applicants, monitor escrow payments, and verify user profiles.</p>
        </div>
        <div>
            <button onclick="document.getElementById('createListingModal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-emerald-900/40 transition">
                <i class="fa-solid fa-plus-circle"></i> Post New Opportunity / Gig
            </button>
        </div>
    </div>

    <!-- Stat Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Registered Users</span>
            <div class="text-2xl font-black text-white mt-1">{{ number_format($stats['totalUsers']) }}</div>
            <span class="text-[10px] text-emerald-400 font-semibold"><i class="fa-solid fa-user-check"></i> {{ $stats['verifiedUsers'] }} NIDA Verified</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Active Listings</span>
            <div class="text-2xl font-black text-emerald-400 mt-1">{{ number_format($stats['activeListings']) }}</div>
            <span class="text-[10px] text-slate-400 font-semibold">Live in Mobile Feed</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Applications</span>
            <div class="text-2xl font-black text-blue-400 mt-1">{{ number_format($stats['totalApplications']) }}</div>
            <span class="text-[10px] text-slate-400 font-semibold">Job Seeker Pitches</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Held In Escrow</span>
            <div class="text-lg font-black text-amber-400 mt-1">TZS {{ number_format($stats['totalEscrowHeldTzs']) }}</div>
            <span class="text-[10px] text-amber-500/80 font-semibold">Secured Funds</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Escrow Released</span>
            <div class="text-lg font-black text-emerald-400 mt-1">TZS {{ number_format($stats['totalEscrowReleasedTzs']) }}</div>
            <span class="text-[10px] text-emerald-500/80 font-semibold">Completed Payouts</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">AI Fraud Guard</span>
            <div class="text-2xl font-black text-purple-400 mt-1">100%</div>
            <span class="text-[10px] text-purple-400 font-semibold">Gemini 2.5 Active</span>
        </div>
    </div>

    <!-- Main Grid: Recent Listings & Recent Users -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Listings (2 cols) -->
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-briefcase text-emerald-400"></i>
                    <h2 class="text-lg font-black text-white">Live Opportunity Listings</h2>
                </div>
                <a href="{{ route('admin.listings') }}" class="text-xs text-emerald-400 hover:text-emerald-300 font-bold">View All ({{ $stats['activeListings'] }}) &rarr;</a>
            </div>

            @if($recentListings->isEmpty())
                <div class="text-center py-12 border border-dashed border-slate-800 rounded-xl">
                    <i class="fa-solid fa-folder-open text-3xl text-slate-600 mb-2"></i>
                    <p class="text-sm font-semibold text-slate-400">No listings posted yet.</p>
                    <p class="text-xs text-slate-500 mt-1">Click "Post New Opportunity" to publish opportunities for mobile applicants.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="pb-3">Title & Category</th>
                                <th class="pb-3">Location</th>
                                <th class="pb-3">Compensation</th>
                                <th class="pb-3">Escrow</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($recentListings as $listing)
                                <tr class="hover:bg-slate-800/30 transition">
                                    <td class="py-3">
                                        <div class="font-bold text-white text-sm">{{ $listing->title }}</div>
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-emerald-400 border border-emerald-500/20 mt-1">
                                            {{ $listing->category }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-slate-300">
                                        <i class="fa-solid fa-location-dot text-slate-500 mr-1"></i> {{ $listing->location }}
                                    </td>
                                    <td class="py-3 font-semibold text-amber-400">
                                        {{ $listing->compensation }}
                                    </td>
                                    <td class="py-3">
                                        @if($listing->is_escrow_required)
                                            <span class="text-emerald-400 font-bold"><i class="fa-solid fa-lock"></i> Protected</span>
                                        @else
                                            <span class="text-slate-500">Direct</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right">
                                        <form action="{{ route('admin.listings.delete', $listing->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this listing?');" class="inline">
                                            @csrf
                                            <button type="submit" class="text-rose-400 hover:text-rose-300 p-1.5 rounded hover:bg-rose-950/50 transition" title="Delete Listing">
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
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-users text-blue-400"></i>
                    <h2 class="text-lg font-black text-white">Registered Users</h2>
                </div>
                <a href="{{ route('admin.users') }}" class="text-xs text-blue-400 hover:text-blue-300 font-bold">View All &rarr;</a>
            </div>

            @if($recentUsers->isEmpty())
                <div class="text-center py-12 border border-dashed border-slate-800 rounded-xl">
                    <i class="fa-solid fa-user-slash text-3xl text-slate-600 mb-2"></i>
                    <p class="text-sm font-semibold text-slate-400">No users registered yet.</p>
                    <p class="text-xs text-slate-500 mt-1">Users registered via the Android app will appear here instantly.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentUsers as $u)
                        <div class="p-3 rounded-xl bg-slate-800/40 border border-slate-800 flex items-center justify-between">
                            <div>
                                <div class="font-bold text-white text-sm">{{ $u->full_name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $u->phone }} • <span class="text-emerald-400">{{ $u->role }}</span></div>
                                <div class="text-[10px] text-slate-500 mt-0.5"><i class="fa-solid fa-location-dot"></i> {{ $u->location }}</div>
                            </div>
                            <div class="text-right">
                                <form action="{{ route('admin.users.verify', $u->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold px-2.5 py-1 rounded-full border transition {{ $u->verification_status === 'VERIFIED' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-amber-500/10 text-amber-400 border-amber-500/30' }}">
                                        @if($u->verification_status === 'VERIFIED')
                                            <i class="fa-solid fa-check-circle"></i> VERIFIED
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
<div id="createListingModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-2xl w-full p-6 shadow-2xl relative">
        <button onclick="document.getElementById('createListingModal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-white">Post Opportunity to Mobile App</h3>
                <p class="text-xs text-slate-400">Published listings appear immediately on all Android applicant feeds.</p>
            </div>
        </div>

        <form action="{{ route('admin.listings.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Opportunity Title *</label>
                <input type="text" name="title" required placeholder="e.g., Solar Installation Technician / Seasonal Harvest Crew" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Category *</label>
                    <select name="category" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                        <option value="SKILLED_SERVICE">Skilled Service</option>
                        <option value="EMPLOYMENT">Formal Employment</option>
                        <option value="CASUAL_GIG">Casual Labor / Gig</option>
                        <option value="AGRI_LABOR">Agri Seasonal Labor</option>
                        <option value="EQUIPMENT_RENTAL">Farm Equipment Rental</option>
                        <option value="BUSINESS_OPP">Business Opportunity</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Location / Region *</label>
                    <input type="text" name="location" required value="Mbeya City, Tanzania" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Compensation Display *</label>
                    <input type="text" name="compensation" required placeholder="e.g., TZS 850,000 / project or TZS 35,000 / day" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Numeric Amount (TZS for Escrow)</label>
                    <input type="number" name="compensationAmountTzs" value="850000" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Required Skills (Comma separated)</label>
                <input type="text" name="requiredSkills" placeholder="e.g., Solar Systems, Electrical Wiring, Inverters" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Detailed Description *</label>
                <textarea name="description" rows="3" required placeholder="Detailed job scope, milestones, and deliverable expectations..." class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="isEscrowRequired" name="isEscrowRequired" value="1" checked class="w-4 h-4 text-emerald-600 bg-slate-800 border-slate-700 rounded">
                <label for="isEscrowRequired" class="text-xs text-slate-300 font-medium">Require Escrow Deposit Protection (M-Pesa / Tigo Pesa / CRDB)</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('createListingModal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-slate-400 hover:text-white">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-lg transition">Publish to Mobile Feed</button>
            </div>
        </form>
    </div>
</div>
@endsection
