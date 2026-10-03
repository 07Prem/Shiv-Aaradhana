@extends('layouts.admin')

@section('title', 'Manage Inquiries & RFQs')
@section('header', 'International Buyer Inquiries & Quotes')

@section('content')

<!-- Header & Export Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <p class="text-xs text-stone-500">Review, track, and respond to incoming quotation requests from overseas buyers.</p>
    <a href="{{ route('admin.inquiries.export') }}" class="px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-colors inline-flex items-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <span>Export CSV Report</span>
    </a>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white p-4 rounded-xl border border-stone-200 shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <!-- Status Filters -->
    <div class="flex flex-wrap items-center gap-2 text-xs">
        <a href="{{ route('admin.inquiries.index', array_filter(['q' => $filters['q'] ?? null, 'type' => $filters['type'] ?? null])) }}" 
           class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ empty($filters['status']) ? 'bg-[#091433] text-[#EBD6B4]' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
            All ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.inquiries.index', array_filter(['status' => 'new', 'q' => $filters['q'] ?? null, 'type' => $filters['type'] ?? null])) }}" 
           class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ ($filters['status'] ?? '') === 'new' ? 'bg-amber-600 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
            New ({{ $counts['new'] }})
        </a>
        <a href="{{ route('admin.inquiries.index', array_filter(['status' => 'in_progress', 'q' => $filters['q'] ?? null, 'type' => $filters['type'] ?? null])) }}" 
           class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ ($filters['status'] ?? '') === 'in_progress' ? 'bg-blue-600 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
            In Progress ({{ $counts['in_progress'] }})
        </a>
        <a href="{{ route('admin.inquiries.index', array_filter(['status' => 'responded', 'q' => $filters['q'] ?? null, 'type' => $filters['type'] ?? null])) }}" 
           class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ ($filters['status'] ?? '') === 'responded' ? 'bg-purple-600 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
            Responded ({{ $counts['responded'] }})
        </a>
        <a href="{{ route('admin.inquiries.index', array_filter(['status' => 'accepted', 'q' => $filters['q'] ?? null, 'type' => $filters['type'] ?? null])) }}" 
           class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ ($filters['status'] ?? '') === 'accepted' ? 'bg-emerald-600 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
            Accepted ({{ $counts['accepted'] }})
        </a>
        <a href="{{ route('admin.inquiries.index', array_filter(['status' => 'rejected', 'q' => $filters['q'] ?? null, 'type' => $filters['type'] ?? null])) }}" 
           class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ ($filters['status'] ?? '') === 'rejected' ? 'bg-rose-600 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
            Rejected ({{ $counts['rejected'] }})
        </a>
        <a href="{{ route('admin.inquiries.index', array_filter(['status' => 'closed', 'q' => $filters['q'] ?? null, 'type' => $filters['type'] ?? null])) }}" 
           class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ ($filters['status'] ?? '') === 'closed' ? 'bg-stone-800 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
            Closed ({{ $counts['closed'] }})
        </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('admin.inquiries.index') }}" method="GET" class="flex items-center gap-2">
        @if(!empty($filters['status']))
            <input type="hidden" name="status" value="{{ $filters['status'] }}">
        @endif
        <div class="relative">
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search ref, buyer, product..." class="w-48 sm:w-64 pl-8 pr-3 py-1.5 rounded-lg border border-stone-300 text-xs focus:ring-2 focus:ring-[#9C451B] focus:border-[#9C451B]">
            <svg class="w-3.5 h-3.5 text-stone-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-semibold transition-colors">
            Search
        </button>
        @if(!empty($filters['q']) || !empty($filters['status']))
            <a href="{{ route('admin.inquiries.index') }}" class="text-xs text-stone-500 hover:text-stone-800 underline">
                Clear
            </a>
        @endif
    </form>
</div>

<!-- Inquiries Table -->
<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm">
    @if($inquiries->count() > 0)
    <table class="w-full text-xs text-left">
        <thead>
            <tr class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200 text-[11px]">
                <th class="py-3 px-4">Ref #</th>
                <th class="py-3 px-4">Type</th>
                <th class="py-3 px-4">Buyer & Company</th>
                <th class="py-3 px-4">Requested Products / Commodity</th>
                <th class="py-3 px-4">Country & Port</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4">Received</th>
                <th class="py-3 px-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @foreach($inquiries as $inq)
            <tr class="hover:bg-stone-50 transition-colors">
                <td class="py-3.5 px-4 font-mono font-bold text-[#091433]">
                    {{ $inq->reference_no }}
                </td>
                <td class="py-3.5 px-4">
                    @if($inq->inquiry_type === 'quote')
                        <span class="px-2 py-0.5 rounded bg-purple-100 text-purple-800 font-bold uppercase text-[9px]">Quote</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-bold uppercase text-[9px]">General</span>
                    @endif
                </td>
                <td class="py-3.5 px-4">
                    <span class="font-bold text-stone-900 block text-sm">{{ $inq->full_name }}</span>
                    <span class="text-stone-500 text-[11px] block">{{ $inq->company_name ?? 'Individual Buyer' }}</span>
                    <a href="mailto:{{ $inq->email }}" class="text-[#9C451B] text-[11px] hover:underline">{{ $inq->email }}</a>
                    <span class="text-[10px] text-stone-400 block">{{ $inq->phone }}</span>
                </td>
                <td class="py-3.5 px-4 font-medium text-stone-800">
                    @if($inq->items->count() > 1)
                        <span class="inline-block px-2 py-0.5 rounded bg-amber-50 text-amber-900 border border-amber-200 font-bold text-[10px] mb-1">
                            {{ $inq->items->count() }} Products in RFQ
                        </span>
                        <ul class="space-y-0.5 text-[11px] text-stone-700">
                            @foreach($inq->items->take(2) as $it)
                                <li class="truncate max-w-[240px]">&bull; <strong>{{ $it->product_name }}</strong> <span class="text-stone-500">({{ $it->quantity }})</span></li>
                            @endforeach
                            @if($inq->items->count() > 2)
                                <li class="text-[10px] text-stone-400 italic">+{{ $inq->items->count() - 2 }} more products</li>
                            @endif
                        </ul>
                    @elseif($inq->items->count() === 1)
                        <span class="font-bold text-stone-900 block text-xs">{{ $inq->items->first()->product_name }}</span>
                        <span class="text-[10px] text-stone-500 block font-normal">Qty: {{ $inq->items->first()->quantity }}</span>
                    @else
                        <span class="font-bold text-stone-900 block text-xs">{{ $inq->product?->name ?? ($inq->subject ?? 'General Trade Inquiry') }}</span>
                        @if($inq->target_quantity)
                            <span class="text-[10px] text-stone-500 block font-normal">Qty: {{ $inq->target_quantity }}</span>
                        @endif
                    @endif
                </td>
                <td class="py-3.5 px-4">
                    <span class="font-semibold text-stone-800 block">{{ $inq->country }}</span>
                    <span class="text-[11px] text-stone-400 block">{{ $inq->port_of_destination ?? 'Port unstated' }}</span>
                </td>
                <td class="py-3.5 px-4">
                    <span class="px-2.5 py-1 rounded border text-[10px] font-bold {{ $inq->status_badge['bg'] }}">
                        {{ $inq->status_badge['label'] }}
                    </span>
                </td>
                <td class="py-3.5 px-4 text-stone-500 text-[11px] whitespace-nowrap">
                    {{ $inq->created_at->format('d M Y') }}<br>
                    <span class="text-[10px] text-stone-400">{{ $inq->created_at->format('H:i') }} UTC</span>
                </td>
                <td class="py-3.5 px-4 text-right">
                    <a href="{{ route('admin.inquiries.show', $inq->id) }}" class="px-3 py-1.5 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white font-semibold transition-colors">
                        View Lead &rarr;
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="py-16 text-center text-xs text-stone-400">
        No inquiries match the current filter.
    </div>
    @endif
</div>

<div class="mt-6">
    {{ $inquiries->links() }}
</div>

@endsection
