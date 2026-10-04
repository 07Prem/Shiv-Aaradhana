@extends('layouts.admin')

@section('title', "Inquiry #{$inquiry->reference_no}")
@section('header', "Inquiry Reference: #{$inquiry->reference_no}")

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.inquiries.index') }}" class="text-xs text-[#9C451B] font-bold hover:underline inline-flex items-center gap-1">
        &larr; Back to Inquiries List
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Left: Inquiry Full Data -->
    <div class="lg:col-span-8 space-y-6">
        
        <!-- Buyer & Commercial Information Card -->
        <div class="bg-white p-8 rounded-2xl border border-stone-200 shadow-sm space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-stone-100">
                <div>
                    <span class="text-xs uppercase font-bold tracking-wider text-stone-400 block">
                        {{ strtoupper($inquiry->inquiry_type) }} INQUIRY
                    </span>
                    <h3 class="text-2xl font-heading font-bold text-[#091433] mt-0.5">
                        {{ $inquiry->full_name }}
                    </h3>
                    <span class="text-xs text-stone-500 font-semibold">
                        {{ $inquiry->company_name ?? 'Individual Commercial Buyer' }} &bull; {{ $inquiry->country }}
                    </span>
                </div>
                <div>
                    <span class="px-3 py-1 rounded-md border text-xs font-bold {{ $inquiry->status_badge['bg'] }}">
                        {{ $inquiry->status_badge['label'] }}
                    </span>
                </div>
            </div>

            <!-- Contact Matrix -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs bg-stone-50 p-4 rounded-xl border border-stone-200">
                <div>
                    <span class="text-stone-400 block uppercase font-bold text-[10px]">Email Address</span>
                    <a href="mailto:{{ $inquiry->email }}" class="text-[#9C451B] font-bold text-sm hover:underline mt-0.5 block break-all">
                        {{ $inquiry->email }}
                    </a>
                </div>
                <div>
                    <span class="text-stone-400 block uppercase font-bold text-[10px]">Phone / WhatsApp</span>
                    <a href="tel:{{ $inquiry->phone }}" class="text-[#091433] font-bold text-sm hover:underline mt-0.5 block">
                        {{ $inquiry->phone }}
                    </a>
                </div>
                <div>
                    <span class="text-stone-400 block uppercase font-bold text-[10px]">Target Destination Country</span>
                    <span class="text-stone-900 font-bold text-sm mt-0.5 block">
                        {{ $inquiry->country }}
                    </span>
                </div>
            </div>

            <!-- Subject / Inquiry Topic -->
            @if(!empty($inquiry->subject))
            <div class="p-4 rounded-xl bg-[#FAF5ED] border border-[#EBD6B4] flex items-center justify-between gap-4">
                <div>
                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#9C451B] block">Inquiry Subject / Topic</span>
                    <h4 class="text-sm font-bold text-[#091433] mt-0.5">{{ $inquiry->subject }}</h4>
                </div>
                <span class="px-2.5 py-1 rounded bg-white text-stone-600 text-xs font-medium border border-stone-200">
                    {{ ucfirst($inquiry->inquiry_type) }} Lead
                </span>
            </div>
            @endif

            <!-- Quotation Line Items Table -->
            @if($inquiry->items->count() > 0)
            <div class="border-t border-stone-100 pt-5 space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#091433]">
                        Requested Commodities &amp; Products ({{ $inquiry->items->count() }} Line Items)
                    </h4>
                </div>

                <div class="border border-stone-200 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-stone-50 text-stone-500 uppercase tracking-wider text-[10px] border-b border-stone-200">
                                <th class="py-2.5 px-3">#</th>
                                <th class="py-2.5 px-3">Product Name</th>
                                <th class="py-2.5 px-3">HS Code</th>
                                <th class="py-2.5 px-3">Target Quantity</th>
                                <th class="py-2.5 px-3">Line Notes / Specs</th>
                                <th class="py-2.5 px-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach($inquiry->items as $idx => $item)
                            <tr class="hover:bg-stone-50 transition-colors">
                                <td class="py-3 px-3 font-mono text-stone-400">{{ $idx + 1 }}</td>
                                <td class="py-3 px-3">
                                    <span class="font-bold text-stone-900 block text-xs">{{ $item->product_name }}</span>
                                    @if($item->product?->category)
                                        <span class="text-[10px] text-stone-500">{{ $item->product->category->name }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 font-mono text-stone-600">
                                    {{ $item->hs_code ?? ($item->product?->hs_code ?? 'N/A') }}
                                </td>
                                <td class="py-3 px-3 font-semibold text-stone-900">
                                    {{ $item->quantity }}
                                </td>
                                <td class="py-3 px-3 text-stone-600">
                                    {{ $item->notes ?? 'Standard Specifications' }}
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if($item->product)
                                        <a href="{{ route('admin.products.edit', $item->product->id) }}" class="text-[11px] text-[#9C451B] font-semibold hover:underline">
                                            Edit Product &rarr;
                                        </a>
                                    @else
                                        <span class="text-stone-400 text-[11px]">Unlinked</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @elseif($inquiry->inquiry_type === 'quote' || $inquiry->product)
            <!-- Single product fallback for legacy inquiries -->
            <div class="border-t border-stone-100 pt-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#091433] mb-3">Commodity & Shipment Criteria</h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div class="p-3 bg-stone-50 rounded-lg">
                        <span class="text-stone-400 block text-[10px] uppercase font-bold">Referenced Product</span>
                        <span class="font-bold text-stone-800 text-sm mt-0.5 block">
                            {{ $inquiry->product?->name ?? 'General Inquiry' }}
                        </span>
                    </div>
                    <div class="p-3 bg-stone-50 rounded-lg">
                        <span class="text-stone-400 block text-[10px] uppercase font-bold">Requested Quantity</span>
                        <span class="font-bold text-stone-800 text-sm mt-0.5 block">
                            {{ $inquiry->target_quantity ?? 'Unspecified' }}
                        </span>
                    </div>
                    <div class="p-3 bg-stone-50 rounded-lg">
                        <span class="text-stone-400 block text-[10px] uppercase font-bold">Discharge Port</span>
                        <span class="font-bold text-stone-800 text-sm mt-0.5 block">
                            {{ $inquiry->port_of_destination ?? 'Unstated' }}
                        </span>
                    </div>
                    <div class="p-3 bg-stone-50 rounded-lg">
                        <span class="text-stone-400 block text-[10px] uppercase font-bold">Packaging Requirement</span>
                        <span class="font-bold text-stone-800 text-sm mt-0.5 block">
                            {{ $inquiry->packaging_requirements ?? 'Standard Export' }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Shipping & Packaging Summary -->
            <div class="border-t border-stone-100 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3.5 bg-stone-50 rounded-xl border border-stone-200">
                    <span class="text-stone-400 uppercase font-bold text-[10px] block mb-1">Discharge Port / Destination</span>
                    <span class="font-bold text-stone-900 text-sm block">
                        {{ $inquiry->port_of_destination ?? 'Port unstated (Inquire with buyer)' }}
                    </span>
                </div>
                <div class="p-3.5 bg-stone-50 rounded-xl border border-stone-200">
                    <span class="text-stone-400 uppercase font-bold text-[10px] block mb-1">Packaging Preference</span>
                    <span class="font-bold text-stone-900 text-sm block">
                        {{ $inquiry->packaging_requirements ?? 'Standard Export Packaging' }}
                    </span>
                </div>
            </div>

            <!-- Inquiry Body / Message -->
            <div class="border-t border-stone-100 pt-4">
                <span class="text-xs font-bold uppercase tracking-wider text-[#091433] block mb-2">Buyer Message & Technical Requirements:</span>
                <div class="p-5 rounded-xl bg-stone-50 border border-stone-200 text-stone-800 text-xs sm:text-sm leading-relaxed whitespace-pre-wrap font-sans">
{{ $inquiry->message }}
                </div>
            </div>

            <!-- Metadata info -->
            <div class="text-[11px] text-stone-400 pt-2 flex flex-wrap items-center justify-between gap-2 border-t border-stone-100">
                <span>Received: {{ $inquiry->created_at->format('d M Y, H:i:s') }} UTC</span>
                <span>IP Address: {{ $inquiry->ip_address ?? 'Recorded' }}</span>
                <span>User Agent: {{ Str::limit($inquiry->user_agent ?? 'Browser', 60) }}</span>
            </div>
        </div>

        <!-- Internal Staff Activity & Audit Notes -->
        <div class="bg-white p-8 rounded-2xl border border-stone-200 shadow-sm space-y-4">
            <h4 class="font-heading font-bold text-lg text-[#091433]">Internal Follow-up & Audit Trail</h4>

            <!-- Add Note Form -->
            <form action="{{ route('admin.inquiries.add_note', $inquiry->id) }}" method="POST" class="space-y-3">
                @csrf
                <textarea name="notes" rows="2" required placeholder="Add follow-up notes (e.g. Sent CIF Jebel Ali quotation via email, sample dispatch tracking #...)" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-xs focus:ring-2 focus:ring-[#9C451B]"></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-stone-800 hover:bg-stone-900 text-white text-xs font-bold transition-colors">
                        Add Internal Note
                    </button>
                </div>
            </form>

            <!-- Activity List -->
            @if($inquiry->activities->count() > 0)
            <div class="space-y-3 pt-4 border-t border-stone-100">
                @foreach($inquiry->activities as $act)
                <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200 text-xs">
                    <div class="flex items-center justify-between text-stone-500 mb-1">
                        <span class="font-bold text-stone-800">{{ $act->action }}</span>
                        <span>{{ $act->created_at->diffForHumans() }} ({{ $act->user?->name ?? 'System' }})</span>
                    </div>
                    <p class="text-stone-700 leading-relaxed">{{ $act->notes }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    <!-- Right: Status Update Box & Actions -->
    <div class="lg:col-span-4 space-y-6">
        
        <!-- Status Updater -->
        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
            <h4 class="font-heading font-bold text-base text-[#091433]">Lifecycle Status</h4>
            
            <form action="{{ route('admin.inquiries.update_status', $inquiry->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1">Current Status</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-xs font-semibold">
                        <option value="new" {{ $inquiry->status === 'new' ? 'selected' : '' }}>New (Awaiting Review)</option>
                        <option value="in_progress" {{ $inquiry->status === 'in_progress' ? 'selected' : '' }}>In Progress (Under Evaluation)</option>
                        <option value="responded" {{ $inquiry->status === 'responded' ? 'selected' : '' }}>Responded (Quotation Sent)</option>
                        <option value="accepted" {{ $inquiry->status === 'accepted' ? 'selected' : '' }}>Accepted (Deal Confirmed)</option>
                        <option value="rejected" {{ $inquiry->status === 'rejected' ? 'selected' : '' }}>Rejected (Declined)</option>
                        <option value="closed" {{ $inquiry->status === 'closed' ? 'selected' : '' }}>Closed (Archived)</option>
                        <option value="spam" {{ $inquiry->status === 'spam' ? 'selected' : '' }}>Spam (Disqualified)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1">Status Note (Optional)</label>
                    <input type="text" name="status_note" placeholder="e.g. Sent CIF pricing to buyer" class="w-full px-3 py-2 rounded-lg border border-stone-300 text-xs">
                </div>

                <button type="submit" class="w-full py-2.5 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-bold transition-colors">
                    Update Status
                </button>
            </form>
        </div>

        <!-- Quick Email Action -->
        <div class="bg-[#FAF5ED] p-6 rounded-2xl border border-[#EBD6B4] space-y-3 text-xs">
            <span class="font-bold text-[#091433] block uppercase tracking-wider">Direct Buyer Response</span>
            <p class="text-stone-600 leading-relaxed">
                Send official quotation or laboratory report directly to buyer's registered email address.
            </p>
            <a href="mailto:{{ $inquiry->email }}?subject=Quotation%20Ref%20%23{{ $inquiry->reference_no }}%20-%20Shiv%20Aaradhana%20Private%20Limited" class="w-full py-2.5 rounded-lg bg-[#9C451B] hover:bg-[#b85322] text-white text-xs font-bold text-center block transition-colors">
                Open Email Client &rarr;
            </a>
        </div>

    </div>

</div>

@endsection
