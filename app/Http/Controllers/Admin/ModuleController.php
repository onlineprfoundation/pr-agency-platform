<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ModuleService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $available = ModuleService::available();
        $installed = \App\Models\Module::all()->keyBy('identifier');

        return view('admin.modules.index', compact('available', 'installed'));
    }

    public function enable(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string|max:255',
        ]);

        ModuleService::enable($request->identifier);

        return redirect()->route('admin.modules.index')->with('success', 'Module enabled.');
    }

    public function disable(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string|max:255',
        ]);

        ModuleService::disable($request->identifier);

        return redirect()->route('admin.modules.index')->with('success', 'Module disabled.');
    }
}
