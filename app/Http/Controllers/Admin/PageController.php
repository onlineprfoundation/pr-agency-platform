<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('sort_order')->orderBy('title')->paginate(20);

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:pages,slug',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string|max:50000',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $slug = ! empty($validated['slug']) ? $validated['slug'] : Str::slug($validated['title']);
        $base = $slug;
        $i = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        $validated['slug'] = $slug;
        $validated['is_published'] = (bool) ($validated['is_published'] ?? false);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if (! empty($validated['content'])) {
            $validated['content'] = $this->sanitizeHtml($validated['content']);
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:pages,slug,' . $page->id,
            'title' => 'required|string|max:255',
            'content' => 'nullable|string|max:50000',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_published'] = (bool) ($validated['is_published'] ?? false);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if (! empty($validated['content'])) {
            $validated['content'] = $this->sanitizeHtml($validated['content']);
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted.');
    }

    private function sanitizeHtml(string $html): string
    {
        if (class_exists(\Mews\Purifier\Facades\Purifier::class)) {
            return \Mews\Purifier\Facades\Purifier::clean($html);
        }

        return strip_tags($html, '<p><br><strong><em><u><a><ul><ol><li><h1><h2><h3><h4><blockquote>');
    }
}
