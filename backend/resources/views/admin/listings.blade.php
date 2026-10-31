@extends('layouts.admin')

@section('title', 'Manage Listings & Opportunities')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Opportunity & Job Listings Management</h1>
            <p class="text-xs text-slate-400 mt-1">Add, update, or remove opportunity postings accessible to mobile app job seekers.</p>
        </div>
        <button onclick="document.getElementById('createListingModal').classList.remove('hidden')" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-lg transition">
            <i class="fa-solid fa-plus-circle"></i> Create New Listing
        </button>
    </div>

    <!-- Category Filters -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.listings') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ empty($category) ? 'bg-emerald-600 text-white' : 'bg-slate-900 text-slate-400 border border-slate-800 hover:bg-slate-800' }}">
            All Categories
        </a>
        @foreach(['SKILLED_SERVICE' => 'Skilled Services', 'EMPLOYMENT' => 'Employment', 'CASUAL_GIG' => 'Casual Gigs', 'AGRI_LABOR' => 'Agri Labor', 'EQUIPMENT_RENTAL' => 'Equipment Rental', 'BUSINESS_OPP' => 'Business'] as $catKey => $catLabel)
            <a href="{{ route('admin.listings', ['category' => $catKey]) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $category === $catKey ? 'bg-emerald-600 text-white' : 'bg-slate-900 text-slate-400 border border-slate-800 hover:bg-slate-800' }}">
                {{ $catLabel }}
            </a>
        @endforeach
    </div>

    <!-- Listings Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
        @if($listings->isEmpty())
            <div class="text-center py-16 border border-dashed border-slate-800 rounded-xl">
                <i class="fa-solid fa-briefcase text-4xl text-slate-600 mb-3"></i>
                <p class="text-base font-semibold text-slate-300">No opportunities found in this category.</p>
                <p class="text-xs text-slate-500 mt-1">Create a new listing using the button above to publish immediately to Android users.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 border-b border-slate-800 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="pb-3">ID & Title</th>
                            <th class="pb-3">Organization / Poster</th>
                            <th class="pb-3">Category</th>
                            <th class="pb-3">Location</th>
                            <th class="pb-3">Compensation</th>
                            <th class="pb-3">Skills Required</th>
                            <th class="pb-3">Escrow</th>
                            <th class="pb-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($listings as $listing)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="py-3">
                                    <div class="font-bold text-white text-sm">{{ $listing->title }}</div>
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $listing->id }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="font-bold text-emerald-400 text-xs flex items-center gap-1.5">
                                        <i class="fa-solid fa-building"></i>
                                        <span>{{ $listing->poster_name ?: 'System Member' }}</span>
                                    </div>
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $listing->poster_id }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-800 text-emerald-400 border border-emerald-500/20">
                                        {{ $listing->category }}
                                    </span>
                                </td>
                                <td class="py-3 text-slate-300">
                                    <i class="fa-solid fa-location-dot text-slate-500 mr-1"></i> {{ $listing->location }}
                                </td>
                                <td class="py-3 font-semibold text-amber-400">
                                    {{ $listing->compensation }}
                                </td>
                                <td class="py-3 text-slate-400 max-w-xs truncate">
                                    {{ is_array($listing->required_skills) ? implode(', ', $listing->required_skills) : $listing->required_skills }}
                                </td>
                                <td class="py-3">
                                    @if($listing->is_escrow_required)
                                        <span class="text-emerald-400 font-bold"><i class="fa-solid fa-lock"></i> Protected</span>
                                    @else
                                        <span class="text-slate-500">Direct</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right">
                                    <form action="{{ route('admin.listings.delete', $listing->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing?');" class="inline">
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
            <div class="mt-4">
                {{ $listings->links() }}
            </div>
        @endif
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
                <input type="checkbox" id="isEscrowRequiredModal" name="isEscrowRequired" value="1" checked class="w-4 h-4 text-emerald-600 bg-slate-800 border-slate-700 rounded">
                <label for="isEscrowRequiredModal" class="text-xs text-slate-300 font-medium">Require Escrow Deposit Protection (M-Pesa / Tigo Pesa / CRDB)</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('createListingModal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-slate-400 hover:text-white">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-lg transition">Publish to Mobile Feed</button>
            </div>
        </form>
    </div>
</div>
@endsection
