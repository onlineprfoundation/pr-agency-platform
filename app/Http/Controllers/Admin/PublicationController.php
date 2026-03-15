<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function index()
    {
        $publications = Publication::orderBy('sort_order')->paginate(20);

        return view('admin.publications.index', compact('publications'));
    }

    public function create()
    {
        return view('admin.publications.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePublication($request);

        Publication::create($validated);

        return redirect()->route('admin.publications.index')->with('success', 'Publication created.');
    }

    public function edit(Publication $publication)
    {
        return view('admin.publications.edit', compact('publication'));
    }

    public function update(Request $request, Publication $publication)
    {
        $validated = $this->validatePublication($request);

        $publication->update($validated);

        return redirect()->route('admin.publications.index')->with('success', 'Publication updated.');
    }

    public function destroy(Publication $publication)
    {
        $publication->delete();

        return redirect()->route('admin.publications.index')->with('success', 'Publication deleted.');
    }

    private function validatePublication(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo_path' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:500',
            'price_usd' => 'nullable|numeric|min:0',
            'words_allowed' => 'nullable|string|max:100',
            'backlinks_count' => 'nullable|integer|min:0',
            'tat' => 'nullable|string|max:100',
            'indexed' => 'boolean',
            'dofollow' => 'boolean',
            'genre' => 'nullable|string|max:255',
            'disclaimer' => 'nullable|string|max:2000',
            'region' => 'nullable|string|max:255',
            'da' => 'nullable|integer|min:0|max:100',
            'traffic' => 'nullable|integer|min:0',
            'last_modified_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['indexed'] = $request->boolean('indexed');
        $validated['dofollow'] = $request->boolean('dofollow');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['price_usd'] = ! empty($validated['price_usd']) ? (float) $validated['price_usd'] : null;
        $validated['backlinks_count'] = ! empty($validated['backlinks_count']) ? (int) $validated['backlinks_count'] : null;
        $validated['da'] = ! empty($validated['da']) ? (int) $validated['da'] : null;
        $validated['traffic'] = ! empty($validated['traffic']) ? (int) $validated['traffic'] : null;
        $validated['last_modified_at'] = ! empty($validated['last_modified_at']) ? $validated['last_modified_at'] : null;

        return $validated;
    }
}
