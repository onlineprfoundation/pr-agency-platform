<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Publication;

class HomeController extends Controller
{
    public function __invoke()
    {
        $packages = Package::where('is_active', true)->orderBy('sort_order')->limit(3)->get();
        $publications = Publication::orderBy('sort_order')->limit(6)->get();

        return view('home', compact('packages', 'publications'));
    }
}
