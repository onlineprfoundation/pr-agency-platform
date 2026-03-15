<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount('projects')->latest()->paginate(20);

        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'logo_path' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:500',
        ]);

        Client::create($validated);

        return redirect()->route('admin.clients.index')->with('success', 'Client created.');
    }

    public function show(Client $client)
    {
        $client->load(['projects.publications', 'user']);

        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'logo_path' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:500',
        ]);

        $client->update($validated);

        return redirect()->route('admin.clients.index')->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Client deleted.');
    }
}
