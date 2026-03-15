<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMessage;
use Illuminate\Http\Request;

class ProjectMessageController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        ProjectMessage::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'sender_type' => 'agency',
            'content' => $request->content,
        ]);

        return redirect()->route('admin.projects.show', $project)->with('success', 'Message sent.');
    }
}
