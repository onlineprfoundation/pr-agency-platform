<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\Publication;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('client')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->paginate(20);

        return view('admin.projects.index', compact('projects'));
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('name')->get();
        $publications = Publication::orderBy('sort_order')->get();
        $selectedClientId = $request->query('client_id');

        return view('admin.projects.create', compact('clients', 'publications', 'selectedClientId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:draft,active,review,completed,cancelled',
            'value_dollars' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string|max:5000',
            'style_guide' => 'nullable|string|max:2000',
            'publications' => 'nullable|array',
            'publications.*' => 'exists:publications,id',
        ]);

        $publications = $validated['publications'] ?? [];
        unset($validated['publications'], $validated['value_dollars']);

        $validated['value_cents'] = isset($request->value_dollars) && $request->value_dollars !== '' && $request->value_dollars !== null
            ? (int) round((float) $request->value_dollars * 100)
            : null;

        $project = Project::create($validated);

        foreach ($publications as $pubId) {
            $pub = Publication::find($pubId);
            $project->publications()->attach($pubId, [
                'price_cents' => $pub?->price_usd ? (int) round($pub->price_usd * 100) : null,
            ]);
        }

        return redirect()->route('admin.projects.show', $project)->with('success', 'Project created.');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'publications', 'documents', 'messages', 'invoices']);

        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $clients = Client::orderBy('name')->get();
        $publications = Publication::orderBy('sort_order')->get();
        $project->load('publications');

        return view('admin.projects.edit', compact('project', 'clients', 'publications'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:draft,active,review,completed,cancelled',
            'value_dollars' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string|max:5000',
            'style_guide' => 'nullable|string|max:2000',
            'publications' => 'nullable|array',
            'publications.*' => 'exists:publications,id',
        ]);

        $publications = $validated['publications'] ?? [];
        unset($validated['publications'], $validated['value_dollars']);

        $validated['value_cents'] = isset($request->value_dollars) && $request->value_dollars !== '' && $request->value_dollars !== null
            ? (int) round((float) $request->value_dollars * 100)
            : null;

        $project->update($validated);

        $sync = [];
        foreach ($publications as $id) {
            $pub = Publication::find($id);
            $sync[$id] = ['price_cents' => $pub && $pub->price_usd ? (int) round($pub->price_usd * 100) : null];
        }
        $project->publications()->sync($sync);

        return redirect()->route('admin.projects.show', $project)->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
    }
}
