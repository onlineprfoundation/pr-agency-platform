<?php

namespace App\Http\Controllers;

use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)->orderBy('sort_order')->get();

        return view('packages.index', compact('packages'));
    }

    public function show(Package $package)
    {
        if (! $package->is_active) {
            abort(404);
        }

        return view('packages.show', compact('package'));
    }
}
