<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Page;
use App\Models\ActivityLog;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('sort_order')->orderBy('title')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int)($validated['sort_order'] ?? 0);

        $page = Page::create($validated);
        ActivityLog::log('page.create', "Created page {$page->title}", $page);

        return redirect()->route('admin.pages.index')->with('success', "Page '{$page->title}' created successfully.");
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => "required|string|max:255|unique:pages,slug,{$page->id}",
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int)($validated['sort_order'] ?? 0);

        $page->update($validated);
        ActivityLog::log('page.update', "Updated page {$page->title}", $page);

        return redirect()->route('admin.pages.index')->with('success', "Page '{$page->title}' updated successfully.");
    }

    public function destroy(Page $page)
    {
        $title = $page->title;
        $page->delete();
        ActivityLog::log('page.delete', "Deleted page {$title}");

        return redirect()->route('admin.pages.index')->with('success', "Page '{$title}' removed.");
    }

    public function toggleStatus(Page $page)
    {
        $page->update(['is_active' => !$page->is_active]);
        $status = $page->is_active ? 'published' : 'draft';
        ActivityLog::log('page.status', "Changed page {$page->title} status to {$status}", $page);

        return back()->with('success', "Page '{$page->title}' is now {$status}.");
    }
}
