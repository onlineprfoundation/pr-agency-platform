<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectDocumentController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB
            'name' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $name = $request->input('name') ?: $file->getClientOriginalName();
        $path = $file->store(
            'project-' . $project->id,
            'project_documents'
        );

        ProjectDocument::create([
            'project_id' => $project->id,
            'name' => $name,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.projects.show', $project)->with('success', 'Document uploaded.');
    }

    public function destroy(Project $project, ProjectDocument $document)
    {
        if ($document->project_id !== $project->id) {
            abort(404);
        }

        Storage::disk('project_documents')->delete($document->file_path);
        $document->delete();

        return redirect()->route('admin.projects.show', $project)->with('success', 'Document deleted.');
    }

    public function download(Project $project, ProjectDocument $document)
    {
        if ($document->project_id !== $project->id) {
            abort(404);
        }

        return Storage::disk('project_documents')->download(
            $document->file_path,
            $document->name
        );
    }
}
