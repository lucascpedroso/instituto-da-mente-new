<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Therapy;

class TherapyController extends Controller
{
    public function index()
    {
        return view('site.therapies.index', [
            'therapies' => Therapy::active()->get(),
        ]);
    }

    public function show(Therapy $therapy)
    {
        abort_unless($therapy->is_active, 404);

        return view('site.therapies.show', [
            'therapy' => $therapy,
            'others' => Therapy::active()->whereKeyNot($therapy->id)->take(3)->get(),
            'faqs' => Faq::active()->where('group', 'clinica')->take(4)->get(),
        ]);
    }
}
