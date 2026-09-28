<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Faq;
use App\Models\Professional;
use App\Models\Therapy;

class PageController extends Controller
{
    public function about()
    {
        return view('site.about', [
            'founder' => Professional::active()->where('is_featured', true)->first(),
        ]);
    }

    public function faq()
    {
        return view('site.faq', [
            'groups' => Faq::active()->get()->groupBy('group'),
        ]);
    }

    public function contact()
    {
        return view('site.contact', [
            'therapies' => Therapy::active()->get(['id', 'title']),
            'courses' => Course::published()->get(['id', 'title']),
            'professionals' => Professional::active()->get(['id', 'name']),
        ]);
    }

    public function privacy()
    {
        return view('site.legal.privacy');
    }

    public function terms()
    {
        return view('site.legal.terms');
    }
}
