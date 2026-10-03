@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header', 'Executive Operations & Catalog Overview')

@section('content')

<!-- Metric Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Inquiries -->
    <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-stone-500 block">Total Inquiries</span>
            <div class="text-3xl font-heading font-black text-[#091433] mt-1">{{ $metrics['total_inquiries'] }}</div>
            <div class="text-xs text-amber-700 font-semibold mt-1">
                {{ $metrics['new_inquiries'] }} awaiting review
            </div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl">
            📬
        </div>
    </div>

    <!-- Published Commodities -->
    <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-stone-500 block">Published Products</span>
            <div class="text-3xl font-heading font-black text-[#394F3D] mt-1">{{ $metrics['published_products'] }}</div>
            <div class="text-xs text-stone-500 mt-1">
                {{ $metrics['draft_products'] }} in draft mode
            </div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-[#394F3D]/10 text-[#394F3D] flex items-center justify-center text-xl">
            📦
        </div>
    </div>

    <!-- Categories -->
    <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-stone-500 block">Active Categories</span>
            <div class="text-3xl font-heading font-black text-[#091433] mt-1">{{ $metrics['total_categories'] }}</div>
            <div class="text-xs text-stone-500 mt-1">
                {{ $metrics['total_product_types'] }} product lines
            </div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center text-xl">
            🗂️
        </div>
    </div>

    <!-- Featured Products -->
    <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs uppercase font-bold tracking-wider text-stone-500 block">Featured on Storefront</span>
            <div class="text-3xl font-heading font-black text-[#9C451B] mt-1">{{ $metrics['featured_products'] }}</div>
            <div class="text-xs text-stone-500 mt-1">
                Homepage highlights
            </div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-[#9C451B]/10 text-[#9C451B] flex items-center justify-center text-xl">
            ⭐
        </div>
    </div>

</div>

<!-- Recent Inquiries & Activity Feed -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Recent Inquiries Table -->
    <div class="lg:col-span-8 bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
        <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-4">
            <div>
                <h3 class="font-heading font-bold text-lg text-[#091433]">Recent Buyer Inquiries</h3>
                <span class="text-xs text-stone-500">Live incoming quotation requests from overseas buyers</span>
            </div>
            <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-bold text-[#9C451B] hover:underline">
                View All Inquiries &rarr;
            </a>
        </div>

        @if($recentInquiries->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="text-stone-400 uppercase tracking-wider border-b border-stone-100">
                        <th class="py-2.5 px-3">Ref #</th>
                        <th class="py-2.5 px-3">Buyer & Country</th>
                        <th class="py-2.5 px-3">Product / Inquired Item</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($recentInquiries as $inq)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="py-3 px-3 font-mono font-bold text-[#091433]">
                            {{ $inq->reference_no }}
                        </td>
                        <td class="py-3 px-3">
                            <span class="font-bold text-stone-900 block">{{ $inq->full_name }}</span>
                            <span class="text-stone-500 text-[11px]">{{ $inq->company_name ? $inq->company_name . ' • ' : '' }}{{ $inq->country }}</span>
                        </td>
                        <td class="py-3 px-3 font-medium text-stone-700">
                            {{ $inq->product?->name ?? 'General Inquiry' }}
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded border text-[10px] font-bold {{ $inq->status_badge['bg'] }}">
                                {{ $inq->status_badge['label'] }}
                            </span>
                        </td>
                        <td class="py-3 px-3 text-right">
                            <a href="{{ route('admin.inquiries.show', $inq->id) }}" class="px-2.5 py-1 rounded bg-stone-100 hover:bg-[#091433] hover:text-white font-semibold transition-colors">
                                Review &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-12 text-center text-xs text-stone-400">
            No inquiries recorded yet.
        </div>
        @endif
    </div>

    <!-- Administrative Activity Logs -->
    <div class="lg:col-span-4 bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
        <div class="pb-4 border-b border-stone-100 mb-4 flex items-center justify-between">
            <div>
                <h3 class="font-heading font-bold text-base text-[#091433]">Audit Trail</h3>
                <span class="text-xs text-stone-500">Recent staff operations</span>
            </div>
            @if(auth()->user()?->isSuperAdmin())
            <a href="{{ route('admin.audit_logs.index') }}" class="text-xs font-bold text-[#9C451B] hover:underline">
                All Logs
            </a>
            @endif
        </div>

        @if($recentAuditLogs->count() > 0)
        <div class="space-y-4 text-xs">
            @foreach($recentAuditLogs as $log)
            <div class="flex items-start gap-3">
                <div class="w-2 h-2 rounded-full bg-[#9C451B] mt-1.5 shrink-0"></div>
                <div>
                    <span class="font-bold text-stone-900 block">{{ $log->action }}</span>
                    <p class="text-stone-600 text-[11px] leading-relaxed">{{ $log->description }}</p>
                    <span class="text-[10px] text-stone-400 block mt-0.5">
                        {{ $log->user?->name ?? 'System' }} &bull; {{ $log->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="py-8 text-center text-xs text-stone-400">
            No audit log entries recorded.
        </div>
        @endif
    </div>

</div>

@endsection
