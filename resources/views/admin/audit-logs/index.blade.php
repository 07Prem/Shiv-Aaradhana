@extends('layouts.admin')

@section('title', 'Audit Trail')
@section('header', 'Administrative Audit Trail & Security Logs')

@section('content')

<div class="mb-6">
    <p class="text-xs text-stone-500">Immutable ledger of administrative actions, catalog modifications, and authentication events.</p>
</div>

<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm">
    <table class="w-full text-xs text-left">
        <thead>
            <tr class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                <th class="py-3 px-4">Timestamp</th>
                <th class="py-3 px-4">Staff Member</th>
                <th class="py-3 px-4">Action</th>
                <th class="py-3 px-4">Event Description</th>
                <th class="py-3 px-4">IP Address</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100 font-sans">
            @forelse($logs as $log)
            <tr class="hover:bg-stone-50 transition-colors">
                <td class="py-3 px-4 font-mono text-stone-500 whitespace-nowrap">
                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                </td>
                <td class="py-3 px-4">
                    <span class="font-bold text-stone-900 block">{{ $log->user?->name ?? 'System / Anonymous' }}</span>
                    <span class="text-[10px] text-stone-400 font-mono">{{ $log->user?->email ?? '' }}</span>
                </td>
                <td class="py-3 px-4">
                    <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-800 font-mono text-[10px] font-bold">
                        {{ $log->action }}
                    </span>
                </td>
                <td class="py-3 px-4 text-stone-700">
                    {{ $log->description }}
                </td>
                <td class="py-3 px-4 font-mono text-stone-400">
                    {{ $log->ip_address ?? '127.0.0.1' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-12 text-center text-stone-400">
                    No audit records found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $logs->links() }}
</div>

@endsection
