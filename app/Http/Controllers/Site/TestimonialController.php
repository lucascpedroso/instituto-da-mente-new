<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TestimonialController extends Controller
{
    public function index()
    {
        return view('site.testimonials', [
            'testimonials' => Testimonial::approved()->paginate(config('instituto.static_preview') ? 1000 : 12),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'kind' => ['required', Rule::in(array_keys(Testimonial::KINDS))],
            'content' => ['required', 'string', 'min:20', 'max:1500'],
            'consent' => ['accepted'],
        ], [
            'consent.accepted' => 'Para enviar, é preciso autorizar a publicação do depoimento.',
            'content.min' => 'Conte um pouco mais sobre a sua experiência (mínimo de 20 caracteres).',
        ], [
            'name' => 'nome',
            'kind' => 'tipo',
            'content' => 'depoimento',
        ]);

        // Todo depoimento entra como pendente e só vai ao ar após aprovação no painel.
        Testimonial::create([
            'name' => $data['name'],
            'kind' => $data['kind'],
            'content' => $data['content'],
            'status' => 'pendente',
            'consent_at' => now(),
        ]);

        return back()->with('testimonial_sent', true);
    }
}
