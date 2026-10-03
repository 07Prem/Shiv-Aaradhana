<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CmsSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsController extends Controller
{
    public function index(): View
    {
        $sections = CmsSection::orderBy('id')->get();
        return view('admin.cms.index', compact('sections'));
    }

    public function edit(CmsSection $cmsSection): View
    {
        return view('admin.cms.edit', compact('cmsSection'));
    }

    public function update(Request $request, CmsSection $cmsSection): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $cmsSection->update($validated);

        AuditLog::record('cms_section_updated', "Updated CMS content for section '{$cmsSection->section_key}'", $cmsSection);

        return redirect()->route('admin.cms.index')->with('success', "CMS section '{$cmsSection->title}' updated.");
    }
}
