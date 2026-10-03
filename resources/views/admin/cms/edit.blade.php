@extends('layouts.admin')

@section('title', "Edit CMS Section: {$cmsSection->section_key}")
@section('header', "Edit Section: {$cmsSection->title}")

@section('content')

<div class="max-w-3xl bg-white p-8 rounded-2xl border border-stone-200 shadow-sm">
    <div class="mb-4 pb-3 border-b border-stone-100 flex items-center justify-between">
        <span class="text-xs font-mono font-bold text-stone-400">Key: {{ $cmsSection->section_key }}</span>
        <a href="{{ route('admin.cms.index') }}" class="text-xs text-[#9C451B] font-bold hover:underline">
            &larr; Back to Sections
        </a>
    </div>

    <form action="{{ route('admin.cms.update', $cmsSection->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Section Title *</label>
            <input type="text" name="title" value="{{ old('title', $cmsSection->title) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Subtitle / Category Tag</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $cmsSection->subtitle) }}" class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-1">Content / Story Narrative</label>
            <textarea name="content" rows="6" class="w-full px-3.5 py-2.5 rounded-lg border border-stone-300 text-sm focus:ring-2 focus:ring-[#9C451B]">{{ old('content', $cmsSection->content) }}</textarea>
        </div>

        <div>
            <label class="inline-flex items-center gap-2 cursor-pointer pt-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $cmsSection->is_active) ? 'checked' : '' }} class="rounded border-stone-300 text-[#9C451B]">
                <span class="text-xs font-bold uppercase text-stone-700">Active on Storefront</span>
            </label>
        </div>

        <div class="pt-4 border-t border-stone-200 flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#091433] hover:bg-[#394F3D] text-white text-xs font-bold transition-colors">
                Update Section Content
            </button>
            <a href="{{ route('admin.cms.index') }}" class="px-5 py-2.5 rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-50 text-xs font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
