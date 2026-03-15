<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class InstallLayout extends Component
{
    public function __construct(
        public bool $step = false
    ) {}

    public function render(): View
    {
        return view('install.layout');
    }
}
