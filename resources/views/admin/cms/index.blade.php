@extends('layouts.admin')

@section('title', 'Manage CMS Sections')
@section('header', 'Content Management & Storytelling')

@section('content')

<div class="mb-6">
    <p class="text-xs text-stone-500">Maintain corporate storytelling, homepage hero messaging, mission/vision statements, and verified contact sections without code modifications.</p>
</div>

<div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-sm">
    <table class="w-full text-xs text-left">
        <thead>
            <tr class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-200">
                <th class="py-3 px-4">Section Key</th>
                <th class="py-3 px-4">Title</th>
                <th class="py-3 px-4">Subtitle</th>
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @foreach($sections as $sec)
            <tr class="hover:bg-stone-50 transition-colors">
                <td class="py-3.5 px-4 font-mono font-bold text-[#091433]">
                    {{ $sec->section_key }}
                </td>
                <td class="py-3.5 px-4 font-bold text-stone-900">
                    {{ $sec->title }}
                </td>
                <td class="py-3.5 px-4 text-stone-500">
                    {{ $sec->subtitle ?? '—' }}
                </td>
                <td class="py-3.5 px-4">
                    @if($sec->is_active)
                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">Active</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-stone-100 text-stone-600 text-[10px] font-bold">Draft</span>
                    @endif
                </td>
                <td class="py-3.5 px-4 text-right">
                    <a href="{{ route('admin.cms.edit', $sec->id) }}" class="px-3 py-1.5 rounded-lg bg-stone-100 hover:bg-[#091433] hover:text-white font-semibold transition-colors">
                        Edit Content &rarr;
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
