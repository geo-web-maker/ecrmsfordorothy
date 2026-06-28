<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class OfficerAdminLayout extends Component
{
    public function __construct(
        public ?string $activeNav = null,
        public ?string $pageTitle = null,
    ) {}

    public function render(): View
    {
        return view('layouts.officer-admin');
    }
}
