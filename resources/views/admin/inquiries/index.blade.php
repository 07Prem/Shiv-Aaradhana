@extends('layouts.admin')

@section('title', 'Manage Inquiries')
@section('header', 'International Buyer Inquiries & Quotes')

@section('content')

<!-- Header & Export Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <p class="text-xs text-stone-500">Review, track, and respond to incoming quotation requests from overseas buyers.</p>
    <a href="{{ route('admin.inquiries.export') }}" class="px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <span>Export CSV Report</span>
    </a>
</div>

<!-- Status Filters -->
<div class="flex flex-wrap items-center gap-2 mb-6 text-xs">
    <a href="{{ route('admin.inquiries.index') }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-colors {{ empty($filters['status']) ? 'bg-[#091433] text-[#EBD6B4]' : 'bg-white text-stone-700 hover:bg-stone-50' }}">
        All ({{ $counts['all'] }})
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'new']) }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-colors {{ ($filters['status'] ?? '') === 'new' ? 'bg-amber-600 text-white' : 'bg-white text-stone-700 hover:bg-stone-50' }}">
        New ({{ $counts['new'] }})
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'in_progress']) }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-colors {{ ($filters['status'] ?? '') === 'in_progress' ? 'bg-blue-600 text-white' : 'bg-white text-stone-700 hover:bg-stone-50' }}">
        In Progress ({{ $counts['in_progress'] }})
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'responded']) }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-colors {{ ($filters['status'] ?? '') === 'responded' ? 'bg-emerald-600 text-white' : 'bg-white text-stone-700 hover:bg-stone-50' }}">
        Responded ({{ $counts['responded'] }})
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'closed']) }}" class="px-3.5 py-1.5 rounded-lg font-bold transition-colors {{ ($filters['status'] ?? '') === 'closed' ? 'bg-stone-800 text-white' : 'bg-white text-stone-700 hover:bg-stone-50' }}">
        Closed ({{ $counts['closed'] }})
    </a>
</div>

<!-- Inquiries Table -->
<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm">
    @if($inquiries->count() > 0)
    <table class="w-full text-xs text-left">
        <thead>
            <tr class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                <th class="py-3 px-4">Ref #</th>
                <th class="py-3 px-4">Type</th>
                <th class="py-3 px-4">Buyer & Company</th>
                <th class="py-3 px-4">Target Commodity</th>
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
                </td>
                <td class="py-3.5 px-4 font-medium text-stone-800">
                    {{ $inq->product?->name ?? ($inq->subject ?? 'General Trade') }}
                    @if($inq->target_quantity)
                        <span class="text-[10px] text-stone-500 block font-normal">Vol: {{ $inq->target_quantity }}</span>
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
