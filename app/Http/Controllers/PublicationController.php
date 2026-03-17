<?php

namespace App\Http\Controllers;

use App\Models\Publication;

class PublicationController extends Controller
{
    public function index()
    {
        $publications = Publication::orderBy('sort_order')->get();

        return view('publications.index', compact('publications'));
    }

    public function show(Publication $publication)
    {
        return view('publications.show', compact('publication'));
    }
}
