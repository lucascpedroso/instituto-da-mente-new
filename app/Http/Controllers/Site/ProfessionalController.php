<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Professional;

class ProfessionalController extends Controller
{
    public function index()
    {
        return view('site.professionals.index', [
            'professionals' => Professional::active()->get(),
        ]);
    }

    public function show(Professional $professional)
    {
        abort_unless($professional->is_active, 404);

        return view('site.professionals.show', [
            'professional' => $professional,
            'posts' => $professional->posts()->published()->take(3)->get(),
        ]);
    }
}
