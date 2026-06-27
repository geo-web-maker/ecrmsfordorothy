<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CitizenController extends Controller
{
    public function dashboard(): View
    {
        return view('citizen.dashboard', [
            'reports' => auth()->user()->reports()->with(['crime', 'evidence', 'statusHistory'])->latest('created_at')->get(),
        ]);
    }
}
