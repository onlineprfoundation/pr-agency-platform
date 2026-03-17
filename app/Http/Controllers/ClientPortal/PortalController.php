<?php

namespace App\Http\Controllers\ClientPortal;

use App\Http\Controllers\Controller;
use App\Models\PackageOrder;
use App\Models\Project;
use App\Models\PublicationOrder;
use App\Models\ProjectDocument;
use App\Models\ProjectMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (! $user->client_id) {
            return view('portal.pending');
        }

        $projects = Project::where('client_id', $user->client_id)
            ->with(['publications', 'documents'])
            ->latest()
            ->get();

        $orders = PackageOrder::with('package')
            ->when($user->client_id, fn ($q) => $q->where('client_id', $user->client_id))
            ->when(! $user->client_id, fn ($q) => $q->where('email', $user->email))
            ->latest()
            ->get();

        $publicationOrders = PublicationOrder::with('publication')
            ->when($user->client_id, fn ($q) => $q->where('client_id', $user->client_id))
            ->when(! $user->client_id, fn ($q) => $q->where('email', $user->email))
            ->latest()
            ->get();

        return view('portal.index', compact('projects', 'orders', 'publicationOrders'));
    }

    public function show(Project $project)
    {
        $this->authorizeClient($project);

        $project->load(['publications', 'documents', 'messages']);

        return view('portal.project', compact('project'));
    }

    public function storeDocument(Request $request, Project $project)
    {
        $this->authorizeClient($project);

        $request->validate([
            'file' => 'required|file|max:10240',
            'name' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $name = $request->input('name') ?: $file->getClientOriginalName();
        $path = $file->store('project-' . $project->id, 'project_documents');

        \App\Models\ProjectDocument::create([
            'project_id' => $project->id,
            'name' => $name,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => null, // client upload
        ]);

        return redirect()->route('portal.project', $project)->with('success', 'Document uploaded.');
    }

    public function storeMessage(Request $request, Project $project)
    {
        $this->authorizeClient($project);

        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        ProjectMessage::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'sender_type' => 'client',
            'content' => $request->content,
        ]);

        return redirect()->route('portal.project', $project)->with('success', 'Message sent.');
    }

    public function downloadDocument(Project $project, ProjectDocument $document)
    {
        $this->authorizeClient($project);

        if ($document->project_id !== $project->id) {
            abort(404);
        }

        return Storage::disk('project_documents')->download(
            $document->file_path,
            $document->name
        );
    }

    private function authorizeClient(Project $project): void
    {
        $user = auth()->user();
        if (! $user->client_id || $project->client_id !== $user->client_id) {
            abort(403, 'Access denied.');
        }
    }
}
