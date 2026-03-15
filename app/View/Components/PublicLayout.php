<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PublicLayout extends Component
{
    public ?string $metaTitle = null;

    public ?string $metaDescription = null;

    public function __construct(?string $metaTitle = null, ?string $metaDescription = null)
    {
        $this->metaTitle = $metaTitle;
        $this->metaDescription = $metaDescription;
    }

    public function render(): View
    {
        return view('layouts.public');
    }
}
